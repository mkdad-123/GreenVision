<?php

namespace App\Http\Controllers\Ai;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Helpers\LabelTranslator;
use Illuminate\Support\Facades\Cache;

class AiDiagnoseController extends Controller
{
    public function diagnose_cnn(Request $request)
    {
        $user = $request->user("user"); // تأكد أن الراوت محمي auth:sanctum مثلاً
        if (!$user) {
            return response()->json(['ok'=>false,'error'=>'UNAUTHENTICATED'], 401);
        }

        // 1) التحقق
        $data = $request->validate([
            'image'         => ['required','file','image','mimes:jpg,jpeg,png','max:5120'],
            'top_n'         => ['nullable','integer','min:1','max:10'],
            'max_diseases'  => ['nullable','integer','min:1','max:3'],
        ]);

        $topN        = $data['top_n'] ?? 3;
        $maxDiseases = min($data['max_diseases'] ?? 3, 3);

        // 2) FastAPI
        $fastapiEndpoint = rtrim(config('services.fastapi.url'), '/').'/predict';
        $resp = Http::timeout(40)->attach(
            'file',
            file_get_contents($request->file('image')->getRealPath()),
            $request->file('image')->getClientOriginalName()
        )->post($fastapiEndpoint);

        if ($resp->failed()) {
            return response()->json([
                'ok' => false,
                'error' => 'FASTAPI_FAILED',
                'status' => $resp->status(),
                'details' => $resp->json(),
            ], 502);
        }

        $json  = $resp->json();
        $preds = $json['predictions'] ?? [];

        // 3) تطبيع التوقعات
        $normalized = [];
        foreach (array_slice($preds, 0, $topN) as $p) {
            $label = $p['class'] ?? null;
            $conf  = isset($p['probability']) ? round($p['probability'], 2) : null;
            $normalized[] = [
                'id'              => $p['id'] ?? null,
                'label'           => $label,
                'label_ar'        => method_exists(LabelTranslator::class, 'toArabic')
                                        ? (LabelTranslator::toArabic($label ?? null) ?? $label)
                                        : $label,
                'confidence'      => $conf,
                'confidence_text' => $conf !== null ? ($conf.'%') : null,
            ];
        }

        // 4) IDs للنظام الخبير
        $diseaseIds = array_values(array_filter(array_map(fn($x) => $x['id'] ?? null, $normalized), fn($v) => $v !== null));
        $idsForKBS  = array_slice($diseaseIds, 0, $maxDiseases);

        // 5) طلب الأعراض من KBS
        $kbsEndpoint = rtrim(config('services.kbs.url'), '/').'/cnn/symptoms';
        $kbsResp = Http::timeout(40)->post($kbsEndpoint, [
            'ids' => $idsForKBS,
            'max_diseases' => max(1, min($maxDiseases, count($idsForKBS))),
        ]);

        // رسالة إرشادية (كمصفوفة أسطر)
        $instruction = [
            "👋 عزيزي المزارع،",
            "لقد قام نظام التشخيص الذكي بتحليل صورة الورقة وحدّد بعض الأمراض المحتملة.",
            "حرصًا على دقّة التشخيص النهائي، نرجو منك الاطّلاع بعناية على الأعراض المدرجة أدناه، ومقارنة كل عرض بما تراه فعليًا في نباتك.",
            "✔️ إذا لاحظت عرضًا بالفعل على النبات، ضع علامة (تمّت الملاحظة) وحدّد نسبة ثقتك بمدى تطابق هذا العرض (من 0 إلى 100).",
            "❌ إذا لم تلاحظ العرض في نباتك، اتركه بدون تحديد.",
            "كلما كانت إدخالاتك أدقّ وأكثر واقعية، كلما ساعد ذلك النظام في تأكيد أو نفي التوقّعات الأولية وتحسين موثوقية التشخيص.",
            "🙏 نثق بتعاونك، وشكرًا لمساهمتك في جعل التشخيص أكثر دقّة وموثوقية."
        ];

        if ($kbsResp->failed()) {
            // نخزن IDs فقط ليتابع المستخدم المرحلة الثانية لاحقاً
            Cache::put("diag:user:{$user->id}", [
                'ids' => $idsForKBS,
                'symptomIndex' => [], // لا يوجد قاموس الآن
                'saved_at' => now()->toIso8601String(),
            ], now()->addMinutes(15));

            return response()->json([
                'ok'              => true,
                'predictions'     => $normalized,
                'disease_ids'     => $idsForKBS,
                'instruction'     => $instruction,
                'symptoms_index'  => [],
                'symptoms_numbered' => [],
                'kbs_raw'         => ['ok'=>false,'error'=>'KBS_FAILED','status'=>$kbsResp->status(),'details'=>$kbsResp->json()],
            ]);
        }

       $kbsJson = $kbsResp->json();

        $rawSymptoms = [];

        // إذا KBS رجع مصفوفة نصوص مباشرة
        if (is_array($kbsJson) && isset($kbsJson['symptoms']) && is_array($kbsJson['symptoms'])) {
            foreach ($kbsJson['symptoms'] as $s) {
                $sname = $s['text'] ?? $s['name'] ?? $s['label'] ?? null;
                if ($sname) {
                    $rawSymptoms[] = $sname;
                }
            }
        }
        // 👇 هذا الفرع يشتغل لو رجع مصفوفة نصوص مباشرة (زي المثال اللي عندك)
        elseif (is_array($kbsJson)) {
            foreach ($kbsJson as $s) {
                if (is_string($s)) {
                    $rawSymptoms[] = $s;
                }
            }
        }

        // بناء القاموس والمصفوفة
        $symptomIndex = [];
        $symptomsNumbered = [];
        $no = 1;
        foreach ($rawSymptoms as $name) {
            $symptomIndex[$no] = $name;
            $symptomsNumbered[] = [
                'no'              => $no,
                'name'            => $name,
                'seen'            => false,
                'user_confidence' => null,
            ];
            $no++;
        }

        // خزنها في الكاش
        Cache::put("diag:user:{$user->id}", [
            'ids'          => $idsForKBS,
            'symptomIndex' => $symptomIndex,
            'saved_at'     => now()->toIso8601String(),
        ], now()->addMinutes(15));

        // أعدها في الرد
        return response()->json([
            'ok'                => true,
            'predictions'       => $normalized,
            'disease_ids'       => $idsForKBS,
            'instruction'       => $instruction,
            'symptoms_index'    => $symptomIndex,     // {1: "بقع زيتونية...", 2: "تشوه الأوراق...", ...}
            'symptoms_numbered' => $symptomsNumbered, // [{no:1, name:"...", ...}, ...]
            'kbs_raw'           => $kbsJson,
        ]);

    }

    public function confirmSymptoms(Request $request)
    {
        $user = $request->user('user');        if (!$user) {
            return response()->json(['ok'=>false,'error'=>'UNAUTHENTICATED'], 401);
        }

        // جلب IDs + قاموس الأعراض المُرقّم من المرحلة 1
        $cacheKey = "diag:user:{$user->id}";
        $state = Cache::get($cacheKey);

        if (!$state || empty($state['ids'])) {
            return response()->json([
                'ok' => false,
                'error' => 'SESSION_EXPIRED',
                'message' => 'انتهت صلاحية الجلسة أو لا توجد بيانات متاحة. أعد إرسال الصورة للحصول على توقعات جديدة.',
            ], 410);
        }

        $ids = array_values($state['ids']);                 // [0,1,11] مثلاً
        $symptomIndex = $state['symptomIndex'] ?? [];       // {1: "بقع زيتونية...", 2: "...", ...}

        $observations = [];

        // 1) إدخال نصي مباشر: observations [{symptom, cf}]
        if ($request->has('observations')) {
            $data = $request->validate([
                'observations' => ['required','array','min:1'],
                'observations.*.symptom' => ['required','string'],
                'observations.*.cf' => ['required','numeric','min:0','max:100'],
            ]);

            foreach ($data['observations'] as $obs) {
                $cf = max(0, min(100, (float)$obs['cf']));
                $observations[] = [
                    'symptom' => trim($obs['symptom']),
                    'cf'      => round($cf, 2),
                ];
            }
        }
        // 2) إدخال قديم: selected_symptoms → تحويل لنصي
        elseif ($request->has('selected_symptoms')) {
            $data = $request->validate([
                'selected_symptoms' => ['required','array','min:1'],
                'selected_symptoms.*.seen' => ['required','boolean'],
                'selected_symptoms.*.user_confidence' => ['nullable','numeric','min:0','max:100'],
                'selected_symptoms.*.name' => ['nullable','string'],
                'selected_symptoms.*.symptom' => ['nullable','string'],
                'selected_symptoms.*.text' => ['nullable','string'],
                'selected_symptoms.*.label' => ['nullable','string'],
            ]);

            foreach ($data['selected_symptoms'] as $s) {
                if (!empty($s['seen'])) {
                    $symptomName =
                        (isset($s['name'])    && $s['name']    !== null ? trim($s['name']) :
                        (isset($s['symptom']) && $s['symptom'] !== null ? trim($s['symptom']) :
                        (isset($s['text'])    && $s['text']    !== null ? trim($s['text']) :
                        (isset($s['label'])   && $s['label']   !== null ? trim($s['label']) : null))));

                    if (!$symptomName) {
                        return response()->json([
                            'ok' => false,
                            'error' => 'MISSING_SYMPTOM_NAME',
                            'message' => 'أرسل اسم العرض ضمن name أو symptom أو text أو label.',
                        ], 422);
                    }

                    $cf = isset($s['user_confidence']) ? (float)$s['user_confidence'] : 100.0;
                    $cf = max(0, min(100, $cf));

                    $observations[] = [
                        'symptom' => $symptomName,
                        'cf'      => round($cf, 2),
                    ];
                }
            }
        }
        // 3) ✅ الإدخال الجديد بالأرقام: observations_numbers [{no, cf}]
        elseif ($request->has('observations_numbers')) {
            $data = $request->validate([
                'observations_numbers' => ['required','array','min:1'],
                'observations_numbers.*.no' => ['required','integer','min:1'],
                'observations_numbers.*.cf' => ['required','numeric','min:0','max:100'],
            ]);

            if (empty($symptomIndex)) {
                return response()->json([
                    'ok' => false,
                    'error' => 'NO_SYMPTOM_INDEX',
                    'message' => 'لا يوجد قاموس للأعراض في الجلسة. أعد المرحلة الأولى للحصول على الترقيم.',
                ], 409);
            }

            foreach ($data['observations_numbers'] as $on) {
                $no = (int) $on['no'];
                if (!isset($symptomIndex[$no])) {
                    return response()->json([
                        'ok' => false,
                        'error' => 'INVALID_SYMPTOM_NO',
                        'message' => "رقم العرض {$no} غير موجود في القاموس.",
                    ], 422);
                }
                $name = $symptomIndex[$no];
                $cf = max(0, min(100, (float)$on['cf']));
                $observations[] = ['symptom'=>$name, 'cf'=>round($cf, 2)];
            }
        }
        else {
            return response()->json([
                'ok' => false,
                'error' => 'NO_OBSERVATIONS_INPUT',
                'message' => 'أرسل observations (نصي) أو selected_symptoms (لتحويلها) أو observations_numbers (أرقام الأعراض).',
            ], 422);
        }

        if (empty($observations)) {
            return response()->json([
                'ok' => false,
                'error' => 'NO_OBSERVATIONS',
                'message' => 'اختر عرضًا واحدًا على الأقل وحدد نسبة الثقة.',
            ], 422);
        }

        // إزالة التكرارات مع الاحتفاظ بأعلى cf لنفس النص
        $seen = [];
        foreach ($observations as $o) {
            $k = mb_strtolower($o['symptom']);
            if (!isset($seen[$k]) || $o['cf'] > $seen[$k]['cf']) {
                $seen[$k] = $o;
            }
        }
        $finalObs = array_values($seen);

        // حمولة KBS النهائية
        $payload = [
            'ids' => $ids,
            'observations' => $finalObs,
        ];

        $endpoint = rtrim(config('services.kbs.url'), '/').'/cnn/diagnose';
        $resp = \Illuminate\Support\Facades\Http::timeout(60)->post($endpoint, $payload);

        if ($resp->failed()) {
            return response()->json([
                'ok' => false,
                'error' => 'KBS_DIAGNOSE_FAILED',
                'status' => $resp->status(),
                'details' => $resp->json(),
                'sent_payload' => $payload,
            ], 502);
        }

        $result = $resp->json();

        // (اختياري) التعريب والتنسيق
        foreach (['results','top'] as $key) {
            if (isset($result[$key]) && is_array($result[$key])) {
                foreach ($result[$key] as &$row) {
                    if (isset($row['disease'])) {
                        $row['disease_ar'] = \App\Helpers\LabelTranslator::toArabic($row['disease']) ?? $row['disease'];
                    }
                    if (isset($row['score']) && is_numeric($row['score'])) {
                        $row['score'] = round((float)$row['score'], 2);
                        $row['score_text'] = $row['score'].'%';
                    }
                }
                unset($row);
            }
        }

        return response()->json([
            'ok'                 => true,
            'ids_source'         => 'server-cache',
            'symptom_index_used' => !empty($symptomIndex),
            'sent_payload'       => $payload,   // سترى هنا ما أُرسل فعليًا لـ KBS (أسماء لا أرقام)
            'diagnosis'          => $result,
        ]);
    }


}

