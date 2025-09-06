<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Services\User\Report\GenerateReportService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReportSummaryMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function __construct(private GenerateReportService $service) {}

    public function summary(Request $request)
    {
        $data = $request->validate([
            'from'        => ['required', 'date', 'before_or_equal:to'],
            'to'          => ['required', 'date', 'after_or_equal:from'],
            'farm_id'     => ['nullable', 'integer', 'exists:farms,id'],
            'sale_status' => ['nullable', Rule::in(['تم البيع', 'قيد البيع', 'محجوز'])],
            'email'       => ['nullable', 'email'],
        ]);

        $rawReport = $this->service->generate(
            $data['from'],
            $data['to'],
            $data['farm_id'] ?? null,
            $data['sale_status'] ?? 'تم البيع'
        );

        if ($rawReport instanceof JsonResponse) {
            $report = $rawReport->getData(true);
        } elseif ($rawReport instanceof Collection) {
            $report = $rawReport->toArray();
        } elseif (is_array($rawReport)) {
            $report = $rawReport;
        } else {
            $report = ['raw' => $rawReport];
        }

        $explanation = sprintf(
            'يشمل هذا التقرير الفترة من %s إلى %s مع حالة المبيعات: %s.%s',
            $data['from'],
            $data['to'],
            $data['sale_status'] ?? 'تم البيع',
            isset($data['farm_id']) ? ' وتمت تصفية النتائج على مزرعة رقم ' . $data['farm_id'] . '.' : ''
        );

        $to = $data['email'] ?? optional($request->user('user'))->email;
        if (!$to) {
            return response()->json([
                'message' => 'يرجى تزويد بريد إلكتروني صالح (email) أو تسجيل الدخول ليتم الإرسال لبريدك.'
            ], 422);
        }

        try {
            Mail::to($to)->send(new ReportSummaryMail($report, $explanation));
        } catch (\Throwable $e) {
            Log::error('Report email failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'تم إنشاء التقرير لكن حدث خطأ عند الإرسال بالبريد.',
                'error'   => 'mail_failed'
            ], 500);
        }

        return response()->json([
            'message' => 'تم إنشاء التقرير وإرساله إلى بريدك. يرجى التحقق.',
            'sent_to' => $to
        ]);
    }
}
