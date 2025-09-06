<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>تشخيص الأمراض بالصور — CNN + KBS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="preload" as="image" href="{{ asset('/background/ai/ima1.webp') }}" fetchpriority="high">
    <link rel="preload" as="image" href="{{ asset('/background/ai/ima2.webp') }}">
    <style>
                :root {
            --bg: #f6faf8;
            --card: #ffffff;
            --text: #112019;
            --muted: #5f6b66;
            --accent: #2fb46e;
            --accent-2: #1e8f57;
            --accent-light: #e8f7ef;
            --stroke: #e6efe9;
            --shadow: 0 8px 28px rgba(16, 24, 20, .09), 0 2px 10px rgba(16, 24, 20, .05);
            --radius: 16px;
            --transition: all 0.3s ease;
        }


        * {
            box-sizing: border-box;
            font-family: "Segoe UI", Tahoma, Arial, system-ui;
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
        }

        .wrap {
            max-width: 1000px;
            margin: 24px auto;
            padding: 12px;
        }

        header {
            background: linear-gradient(180deg, #ffffffee, #ffffffcc);
            backdrop-filter: blur(6px);
            border: 1px solid var(--stroke);
            border-radius: var(--radius);
            padding: 16px 20px;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        h1 {
            margin: 0;
            font-size: 24px;
            background: linear-gradient(135deg, var(--accent-2), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
        }

        .btn {
            all: unset;
            cursor: pointer;
            padding: 12px 24px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            color: #fff;
            font-weight: 700;
            box-shadow: var(--shadow);
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }


        .btn:disabled {
            opacity: .5;
            filter: grayscale(.2);
            cursor: not-allowed;
        }

        .btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 32px rgba(16, 24, 20, .15);
        }

        .btn-secondary {
            background: var(--accent-light);
            color: var(--accent-2);
        }

        .grid {
            display: grid;
            gap: 24px;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--stroke);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 24px;
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: 0 12px 36px rgba(16, 24, 20, .12);
        }

        .row {
            display: flex;
            gap: 16px;
            align-items: center;
            flex-wrap: wrap;
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-width: 160px;
            flex: 1;
        }

        label {
            font-size: 14px;
            color: var(--muted);
            font-weight: 500;
        }

        input[type="number"],
        input[type="text"],
        .fake {
            padding: 12px 16px;
            border: 1px solid var(--stroke);
            border-radius: 12px;
            background: #fff;
            min-width: 120px;
            transition: var(--transition);
        }

        input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(47, 180, 110, 0.1);
        }

        .upload-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
            padding: 40px 20px;
            border: 2px dashed var(--stroke);
            border-radius: var(--radius);
            background: var(--accent-light);
            text-align: center;
            transition: var(--transition);
            cursor: pointer;
            margin-bottom: 20px;
        }

        .upload-container:hover {
            border-color: var(--accent);
            background: #e2f4e9;
        }

        .upload-icon {
            font-size: 48px;
            color: var(--accent);
        }

        .upload-text {
            color: var(--muted);
        }

        .upload-text b {
            color: var(--accent-2);
        }

        input[type="file"] {
            display: none;
        }

        .img-prev {
            max-height: 200px;
            border: 1px solid var(--stroke);
            border-radius: 12px;
            box-shadow: var(--shadow);
        }

        .pred-list {
            display: grid;
            gap: 12px;
        }

        .pred {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid var(--stroke);
            border-radius: 12px;
            padding: 16px;
            background: #fcfffd;
            transition: var(--transition);
        }

        .pred:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .pred b {
            font-size: 16px;
        }

        .muted {
            color: var(--muted);
            font-size: 14px;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 999px;
            background: #f2fbf6;
            border: 1px solid var(--stroke);
            font-size: 14px;
            font-weight: 600;
        }

        .instructions {
            list-style: decimal;
            padding-inline-start: 24px;
            margin: 16px 0 0 0;
        }

        .instructions li {
            margin-bottom: 8px;
            padding-right: 8px;
        }

        .sym-row {
            display: grid;
            grid-template-columns: auto 120px 180px;
            gap: 12px;
            align-items: center;
            border: 1px solid var(--stroke);
            border-radius: 12px;
            padding: 12px;
            background: #fff;
            transition: var(--transition);
        }

        .sym-row:hover {
            box-shadow: var(--shadow);
        }

        .sym-name {
            font-size: 15px;
            font-weight: 500;
        }

        .switch {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .range {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .range input[type="range"] {
            width: 120px;
        }

        .status {
            margin-top: 16px;
            font-size: 14px;
            padding: 8px 12px;
            border-radius: 8px;
            background: #f8f9fa;
        }

        .hr {
            height: 1px;
            background: var(--stroke);
            margin: 20px 0;
        }

        .result-card {
            background: linear-gradient(135deg, #f2fbf6, #e8f7ef);
            border: 1px solid var(--accent);
            padding: 24px;
            border-radius: var(--radius);
            margin-bottom: 20px;
        }

        .result-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .result-icon {
            font-size: 32px;
            color: var(--accent-2);
        }

        .result-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--accent-2);
            margin: 0;
        }

        .result-content {
            display: grid;
            gap: 16px;
        }

        .result-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
        }

        .result-score {
            font-weight: 700;
            color: var(--accent-2);
            font-size: 18px;
        }

        .confidence-bar {
            height: 8px;
            background: #e6efe9;
            border-radius: 4px;
            margin-top: 8px;
            overflow: hidden;
        }

        .confidence-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--accent), var(--accent-2));
            border-radius: 4px;
        }

        .treatment-box {
            background: white;
            border-radius: 12px;
            padding: 16px;
            margin-top: 16px;
            box-shadow: var(--shadow);
        }

        .treatment-title {
            font-weight: 700;
            color: var(--accent-2);
            margin-bottom: 12px;
        }

        .treatment-list {
            padding-inline-start: 24px;
        }

        .treatment-list li {
            margin-bottom: 8px;
        }

        .hidden {
            display: none !important;
        }

        .ok {
            color: #0a7f49;
            background: #e8f7ef;
        }

        .err {
            color: #b00020;
            background: #ffebee;
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(47, 180, 110, 0.3);
            border-radius: 50%;
            border-top-color: var(--accent);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .bg-slideshow {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .bg-slideshow img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transform: scale(1);
            transition: opacity 1.2s ease, transform 12s ease;
            will-change: opacity, transform;
        }

        .bg-slideshow img.active {
            opacity: 1;
            transform: scale(1.04);
        }

        .bg-slideshow::after {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, .35);
            backdrop-filter: saturate(115%) blur(1px);
        }

        .wrap {
            position: relative;
            z-index: 5;
        }

        header {
            position: relative;
            z-index: 6;
        }

        @media (prefers-reduced-motion: reduce) {
            .bg-slideshow img {
                transition: none;
                transform: none;
            }
        }

        .cause-box {
            background: white;
            border-radius: 12px;
            padding: 16px;
            margin-top: 16px;
            box-shadow: var(--shadow);
        }

        .cause-title {
            font-weight: 700;
            color: var(--accent-2);
            margin-bottom: 12px;
        }

        .cause-content {
            line-height: 1.6;
            color: var(--text);
        }

        </style>



</head>

<body>
    <div class="wrap">

        <header>
            <h1>نظام التشخيص الذكي للأمراض النباتية</h1>
        </header>

        <div class="grid">
            <section class="card" id="uploaderCard">
                <h2 style="margin-top: 0;">تحميل صورة النبات للفحص</h2>

                <div class="upload-container" id="uploadArea">
                    <div class="upload-icon">📷</div>
                    <div class="upload-text">
                        <b>انقر لاختيار صورة</b> أو اسحبها إلى هنا
                        <div class="muted" style="margin-top: 8px;">(JPG, PNG - حتى 5MB)</div>
                    </div>
                    <input type="file" id="imageInput" accept="image/jpeg,image/png" />
                </div>

                <div class="row" id="previewRow" style="justify-content: center;"></div>

                <div class="row" style="justify-content: center; margin-top: 20px;">
                    <button class="btn" id="btnDiagnose">
                        <span id="btnText">بدء التشخيص</span>
                        <span class="loading hidden" id="btnLoading"></span>
                    </button>
                </div>

                <div class="status" id="status"></div>
            </section>

            <section class="card hidden" id="resultsCard">
                <div class="flex-between">
                    <h2 style="margin:0">نتائج التحليل الأولي</h2>
                    <span class="pill" id="pidCount"></span>
                </div>

                <div class="hr"></div>

                <div>
                    <div class="muted" style="margin-bottom: 12px;">التشخيات المحتملة بناءً على الصورة:</div>
                    <div class="pred-list" id="predictions"></div>
                </div>

                <div class="hr"></div>

                <div id="instructionsBox">
                    <div class="muted">إرشادات الفحص الإضافية:</div>
                    <ol class="instructions" id="instructions"></ol>
                </div>

                <div class="hr"></div>

                <div id="symptomsBox">
                    <div class="flex-between">
                        <div class="muted">الأعراض المرصودة (يرجى التأكيد):</div>
                        <div class="pill"><span>تلميح:</span> اضبط مستوى الثقة حسب تأكدك من العرض</div>
                    </div>
                    <div class="grid" id="symptomsList" style="margin-top:16px"></div>

                    <div class="row" style="justify-content:flex-end; margin-top:20px">
                        <button class="btn" id="btnConfirm">
                            <span>تأكيد وإرسال للتحليل النهائي</span>
                            <span class="loading hidden" id="btnLoading2"></span>
                        </button>
                    </div>
                    <div class="status" id="status2"></div>
                </div>

                <div id="noSymptomsBox" class="hidden">
                    <div class="err">لا توجد أعراض مسترجعة الآن. أعد التشخيص لاحقًا.</div>
                </div>
            </section>

            <section class="card hidden" id="finalCard">
                <div class="flex-between">
                    <h2 style="margin:0">التشخيص النهائي</h2>
                    <span class="pill ok">تم الانتهاء</span>
                </div>

                <div class="hr"></div>

                <div class="result-card">
                    <div class="result-header">
                        <div class="result-icon">✅</div>
                        <h3 class="result-title" id="finalDiseaseName">اسم المرض</h3>
                    </div>

                    <div class="result-content">
                        <div class="result-item">
                            <div>
                                <div>مستوى الثقة</div>
                                <div class="confidence-bar">
                                    <div class="confidence-fill" id="confidenceBar" style="width: 85%;"></div>
                                </div>
                            </div>
                            <div class="result-score" id="finalConfidence">85%</div>
                        </div>
                    </div>

                    <div class="treatment-box">
                        <div class="treatment-title">العلاج المقترح:</div>
                        <ul class="treatment-list" id="finalTreatment"></ul>
                    </div>
                </div>

                <div class="muted" style="margin-bottom: 12px;">النتائج الأخرى المحتملة:</div>
                <div class="pred-list" id="otherResults"></div>

                <details style="margin-top:24px">
                    <summary>عرض البيانات الكاملة (للمتخصصين)</summary>
                    <code class="json" id="finalJson"></code>
                </details>
            </section>
        </div>
    </div>
    <div class="bg-slideshow" aria-hidden="true">
        <img id="bgA" alt="" decoding="async" loading="eager">
        <img id="bgB" alt="" decoding="async" loading="lazy">
    </div>

    <script>
        (function() {
            const token = document.querySelector('meta[name="csrf-token"]').content;

            const uploadArea = document.getElementById('uploadArea');
            const imageInput = document.getElementById('imageInput');
            const btnDiagnose = document.getElementById('btnDiagnose');
            const btnText = document.getElementById('btnText');
            const btnLoading = document.getElementById('btnLoading');
            const statusEl = document.getElementById('status');
            const previewRow = document.getElementById('previewRow');

            const resultsCard = document.getElementById('resultsCard');
            const predictionsBox = document.getElementById('predictions');
            const instructionsOl = document.getElementById('instructions');
            const pidCount = document.getElementById('pidCount');

            const symptomsBox = document.getElementById('symptomsBox');
            const noSymptomsBox = document.getElementById('noSymptomsBox');
            const symptomsList = document.getElementById('symptomsList');
            const btnConfirm = document.getElementById('btnConfirm');
            const btnLoading2 = document.getElementById('btnLoading2');
            const status2 = document.getElementById('status2');

            const finalCard = document.getElementById('finalCard');
            const finalDiseaseName = document.getElementById('finalDiseaseName');
            const confidenceBar = document.getElementById('confidenceBar');
            const finalConfidence = document.getElementById('finalConfidence');
            const finalTreatment = document.getElementById('finalTreatment');
            const otherResults = document.getElementById('otherResults');
            const finalJson = document.getElementById('finalJson');

            let lastResponse = null;

            function setStatus(msg, ok = false, target = statusEl) {
                target.textContent = msg || '';
                target.className = 'status ' + (ok ? 'ok' : (msg ? 'err' : ''));
            }

            function setLoading(loading, btn = btnDiagnose) {
                if (btn === btnDiagnose) {
                    btn.disabled = loading;
                    btnLoading.classList.toggle('hidden', !loading);
                    btnText.textContent = loading ? 'جاري التحليل...' : 'بدء التشخيص';
                } else if (btn === btnConfirm) {
                    btn.disabled = loading;
                    btnLoading2.classList.toggle('hidden', !loading);
                }
            }

            uploadArea.addEventListener('click', () => {
                imageInput.click();
            });

            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.style.borderColor = 'var(--accent)';
                uploadArea.style.background = '#e2f4e9';
            });

            uploadArea.addEventListener('dragleave', () => {
                uploadArea.style.borderColor = 'var(--stroke)';
                uploadArea.style.background = 'var(--accent-light)';
            });

            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.style.borderColor = 'var(--stroke)';
                uploadArea.style.background = 'var(--accent-light)';

                if (e.dataTransfer.files.length) {
                    imageInput.files = e.dataTransfer.files;
                    showPreview(e.dataTransfer.files[0]);
                }
            });

            function showPreview(file) {
                previewRow.innerHTML = '';
                if (!file) return;
                const url = URL.createObjectURL(file);
                const img = document.createElement('img');
                img.src = url;
                img.className = 'img-prev';
                previewRow.appendChild(img);
            }

            imageInput.addEventListener('change', (e) => showPreview(e.target.files[0]));

            btnDiagnose.addEventListener('click', async (e) => {
                e.preventDefault();
                setStatus('', false);
                resultsCard.classList.add('hidden');
                finalCard.classList.add('hidden');

                const file = imageInput.files[0];
                if (!file) {
                    setStatus('الرجاء اختيار صورة أولاً');
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    setStatus('حجم الصورة يتجاوز 5MB');
                    return;
                }

                setLoading(true);
                setStatus('جاري تحليل الصورة وتشخيص المرض...');

                try {
                    const fd = new FormData();
                    fd.append('image', file);
                    fd.append('top_n', 3);
                    fd.append('max_diseases', 3);

                    const resp = await fetch(`{{ route('ai.cnn.diagnose') }}`, {
                        method: 'POST',
                        body: fd,
                        headers: {
                            'X-CSRF-TOKEN': token
                        },
                        credentials: 'include'
                    });

                    const data = await resp.json();
                    if (!resp.ok || data.ok === false) {
                        throw new Error((data && (data.message || data.error)) || 'فشل عملية التشخيص');
                    }

                    lastResponse = data;
                    renderStage1(data);
                    setStatus('تم تحليل الصورة بنجاح، يرجى تأكيد الأعراض', true);

                } catch (err) {
                    console.error(err);
                    setStatus('حدث خطأ: ' + err.message);
                } finally {
                    setLoading(false);
                }
            });

            function renderStage1(data) {
                resultsCard.classList.remove('hidden');


                predictionsBox.innerHTML = '';
                (data.predictions || []).forEach(p => {
                    const div = document.createElement('div');
                    div.className = 'pred';
                    const left = document.createElement('div');
                    left.innerHTML =
                        `<b>${p.label_ar ?? p.label ?? '-'}</b><div class="muted">${p.label ?? ''}</div>`;
                    const right = document.createElement('div');
                    right.innerHTML = `<span class="pill">${p.confidence_text ?? ''}</span>`;
                    div.appendChild(left);
                    div.appendChild(right);
                    predictionsBox.appendChild(div);
                });

                instructionsOl.innerHTML = '';
                (data.instruction || []).forEach(line => {
                    const li = document.createElement('li');
                    li.textContent = line;
                    instructionsOl.appendChild(li);
                });

                pidCount.textContent = `أمراض محتملة: ${ (data.disease_ids||[]).join(', ') || '-' }`;

                const nums = data.symptoms_numbered || [];
                const hasSymptoms = Array.isArray(nums) && nums.length > 0;

                symptomsBox.classList.toggle('hidden', !hasSymptoms);
                noSymptomsBox.classList.toggle('hidden', hasSymptoms);

                symptomsList.innerHTML = '';
                if (hasSymptoms) {
                    nums.forEach(s => {
                        const row = document.createElement('div');
                        row.className = 'sym-row';
                        row.dataset.no = s.no;

                        const name = document.createElement('div');
                        name.className = 'sym-name';
                        name.textContent = `${s.no}. ${s.name}`;

                        const sw = document.createElement('label');
                        sw.className = 'switch';
                        sw.innerHTML =
                            `<input type="checkbox" class="seen"> <span class="muted">ملاحظ</span>`;

                        const r = document.createElement('div');
                        r.className = 'range';
                        r.innerHTML = `
          <input type="range" class="cf" min="0" max="100" value="100" disabled>
          <span class="muted val">100%</span>
        `;

                        row.appendChild(name);
                        row.appendChild(sw);
                        row.appendChild(r);
                        symptomsList.appendChild(row);
                    });

                    symptomsList.querySelectorAll('.sym-row').forEach(row => {
                        const chk = row.querySelector('.seen');
                        const rng = row.querySelector('.cf');
                        const val = row.querySelector('.val');
                        chk.addEventListener('change', () => {
                            rng.disabled = !chk.checked;
                            if (chk.checked && rng.value === '0') {
                                rng.value = '100';
                                val.textContent = '100%';
                            }
                        });
                        rng.addEventListener('input', () => {
                            val.textContent = rng.value + '%';
                        });
                    });
                }
            }

            btnConfirm.addEventListener('click', async (e) => {
                e.preventDefault();
                setStatus('', false, status2);
                finalCard.classList.add('hidden');

                if (!lastResponse) {
                    setStatus('الرجاء تنفيذ المرحلة الأولى أولاً', false, status2);
                    return;
                }

                const rows = Array.from(symptomsList.querySelectorAll('.sym-row'));
                const payload = {
                    observations_numbers: []
                };

                rows.forEach(row => {
                    const no = parseInt(row.dataset.no, 10);
                    const seen = row.querySelector('.seen').checked;
                    const cf = parseFloat(row.querySelector('.cf').value);
                    if (seen) {
                        payload.observations_numbers.push({
                            no,
                            cf
                        });
                    }
                });

                if (payload.observations_numbers.length === 0) {
                    setStatus('اختر عرضًا واحدًا على الأقل وحدد نسبة الثقة.', false, status2);
                    return;
                }

                setLoading(true, btnConfirm);
                setStatus('جاري التحليل النهائي بواسطة النظام الخبير...', false, status2);

                try {
                    const resp = await fetch(`{{ route('ai.cnn.confirm') }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json'
                        },
                        credentials: 'include',
                        body: JSON.stringify(payload)
                    });

                    const data = await resp.json();
                    if (!resp.ok || data.ok === false) {
                        if (data.error === 'SESSION_EXPIRED') {
                            throw new Error('انتهت صلاحية الجلسة، الرجاء إعادة رفع الصورة.');
                        }
                        if (data.error === 'NO_SYMPTOM_INDEX') {
                            throw new Error('لا توجد قائمة أعراض في الجلسة. أعد المرحلة الأولى.');
                        }
                        throw new Error((data && (data.message || data.error)) || 'فشل التشخيص النهائي');
                    }

                    renderFinal(data);
                    setStatus('تم التحليل بنجاح', true, status2);

                } catch (err) {
                    console.error(err);
                    setStatus('حدث خطأ: ' + err.message, false, status2);
                } finally {
                    setLoading(false, btnConfirm);
                }
            });

            function renderFinal(data) {
                finalCard.classList.remove('hidden');

                const diag = data.diagnosis || {};
                const top = Array.isArray(diag.top) ? diag.top : [];
                const results = Array.isArray(diag.results) ? diag.results : [];

                if (top.length) {
                    const best = top[0];
                    const name = best.disease_ar || best.disease || 'غير معروف';
                    const score = best.score || 0;

                    finalDiseaseName.textContent = name;
                    confidenceBar.style.width = `${score}%`;
                    finalConfidence.textContent = `${score}%`;

                    if (best.cause) {
                        const causeElement = document.createElement('div');
                        causeElement.className = 'cause-box';
                        causeElement.innerHTML = `
                <div class="cause-title">المسبب:</div>
                <div class="cause-content">${best.cause}</div>
            `;

                        const treatmentBox = document.querySelector('.treatment-box');
                        if (treatmentBox && treatmentBox.parentNode) {
                            treatmentBox.parentNode.insertBefore(causeElement, treatmentBox);
                        }
                    }

                    // إضافة علاج افتراضي (يجب استبداله ببيانات حقيقية من الخلفية)
                    finalTreatment.innerHTML = `
            <li>عزل النباتات المصابة لمنع انتشار المرض</li>
            <li>استخدام مبيد فطري مناسب مثل ${name.includes('تبقع') ? 'كلوروثالونيل' : 'مبيد فطري نظامي'}</li>
            <li>تقليل الرطوبة حول النبات وتحسين التهوية</li>
            <li>إزالة الأوراق المصابة والتخلص منها بشكل آمن</li>
        `;
                }

                otherResults.innerHTML = '';
                if (results.length > 1) {
                    for (let i = 1; i < Math.min(results.length, 4); i++) {
                        const r = results[i];
                        const div = document.createElement('div');
                        div.className = 'pred';
                        div.innerHTML = `
                <div><b>${r.disease_ar ?? r.disease ?? '-'}</b></div>
                <div><span class="pill">${r.score_text ?? (typeof r.score==='number' ? r.score+'%' : '')}</span></div>
            `;
                        otherResults.appendChild(div);
                    }
                }

                finalJson.textContent = JSON.stringify(data, null, 2);
            }
        })();
        (function() {
            const bgImages = [
                "{{ asset('/background/diag/ima1.jpg') }}",
                "{{ asset('/background/diag/ima2.jpg') }}",
                "{{ asset('/background/diag/ima3.jpg') }}",
                "{{ asset('/background/diagima4.jpg') }}"
            ];

            if (!bgImages.length) return;

            const imgA = document.getElementById('bgA');
            const imgB = document.getElementById('bgB');
            let i = 0,
                active = imgA,
                idle = imgB;

            function setSrc(el, src) {
                if (el && src) el.src = src;
            }

            setSrc(imgA, bgImages[0]);
            imgA.classList.add('active');
            setSrc(imgB, bgImages[1 % bgImages.length]);

            const INTERVAL_MS = 8000;
            setInterval(() => {
                i = (i + 1) % bgImages.length;
                const next = bgImages[i];

                setSrc(idle, next);
                requestAnimationFrame(() => {
                    active.classList.remove('active');
                    idle.classList.add('active');
                    const tmp = active;
                    active = idle;
                    idle = tmp;
                });
            }, INTERVAL_MS);
        })();
    </script>
</body>

</html>
