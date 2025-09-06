<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>تشخيص الأمراض بالصور — CNN + KBS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="preload" as="image" href="{{ asset('/background/ai/ima1.webp') }}" fetchpriority="high">
    <link rel="preload" as="image" href="{{ asset('/background/ai/ima2.webp') }}">
    @vite(['resources/css/ai.css'])



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
