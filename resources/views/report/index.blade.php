<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>تقرير مالي — ملخص الفترة</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="preload" as="image" href="{{ asset('/background/report/ima1.webp') }}" fetchpriority="high">
    <link rel="preload" as="image" href="{{ asset('/background/report/ima2.webp') }}">
    <link rel="stylesheet" href="{{ asset('css/report.css') }}">

</head>

<body>
    <div class="bg-slideshow" aria-hidden="true">
        <img id="bgA" alt="" decoding="async" loading="eager">
        <img id="bgB" alt="" decoding="async" loading="lazy">
    </div>

    <div id="toast" class="toast" role="status" aria-live="polite">—</div>

    <div class="wrap">
        <header>
            <h1>تقرير مالي — ملخص الفترة</h1>
        </header>

        <div class="center">
            <div class="card">
                <h2 class="title">📊 توليد تقرير وإرساله للبريد</h2>

                <form id="reportForm">
                    <div class="form-grid">
                        <div class="field">
                            <label>من تاريخ</label>
                            <input type="date" id="from" class="input" required>
                        </div>
                        <div class="field">
                            <label>إلى تاريخ</label>
                            <input type="date" id="to" class="input" required>
                        </div>

                        <div class="field">
                            <label>المزرعة (اختياري)</label>
                            <select id="farm_id" class="input">
                                <option value="">—</option>
                            </select>
                            <div class="hint">إن تركتها فارغة، يشمل جميع المزارع.</div>
                        </div>

                        <div class="field">
                            <label>حالة المبيعات</label>
                            <select id="sale_status" class="input">
                                <option value="تم البيع">تم البيع</option>
                                <option value="قيد البيع">قيد البيع</option>
                                <option value="محجوز">محجوز</option>
                            </select>
                        </div>

                        <div class="field" style="grid-column:1/-1">
                            <label>بريد الاستقبال (اختياري)</label>
                            <input type="email" id="email" class="input" placeholder="example@domain.com">
                            <div class="hint">إن تركته فارغًا سيتم الإرسال إلى بريدك المسجّل.</div>
                        </div>
                    </div>

                    <div class="actions">
                        <button type="submit" class="btn" id="btnSend">إرسال التقرير</button>
                    </div>
                    <div class="status" id="formStatus"></div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const token = document.querySelector('meta[name="csrf-token"]').content;

            (function() {
                const imgs = [
                    "{{ asset('/background/report/ima1.jpg') }}",
                    "{{ asset('/background/report/ima2.jpg') }}",
                    "{{ asset('/background/report/ima3.jpg') }}",
                    "{{ asset('/background/report/ima4.jpg') }}",
                ];
                const A = document.getElementById('bgA'),
                    B = document.getElementById('bgB');
                if (!A || !B) return;
                let i = 0,
                    active = A,
                    idle = B;
                const setSrc = (el, src) => {
                    if (el) el.src = src;
                };
                setSrc(A, imgs[0]);
                A.classList.add('active');
                setSrc(B, imgs[1 % imgs.length]);
                setInterval(() => {
                    i = (i + 1) % imgs.length;
                    setSrc(idle, imgs[i]);
                    requestAnimationFrame(() => {
                        active.classList.remove('active');
                        idle.classList.add('active');
                        const t = active;
                        active = idle;
                        idle = t;
                    });
                }, 8000);
            })();

            async function fetchJSON(url, init = {}) {
                const baseHeaders = {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                };
                init.headers = {
                    ...(init.headers || {}),
                    ...baseHeaders
                };
                init.credentials = 'include';
                const resp = await fetch(url, init);
                const ct = resp.headers.get('content-type') || '';
                const raw = await resp.text();
                let data;
                try {
                    data = JSON.parse(raw);
                } catch {
                    throw new Error('فشل تحليل JSON');
                }
                if (!resp.ok || data?.ok === false) {
                    const validation = data?.errors ? Object.values(data.errors).flat().join(' | ') : '';
                    const msg = data?.message || validation || ('HTTP ' + resp.status);
                    const err = new Error(msg);
                    err.data = data;
                    err.status = resp.status;
                    throw err;
                }
                return data;
            }

            function showToast(msg, isError = false) {
                const t = document.getElementById('toast');
                t.textContent = msg;
                t.classList.toggle('error', !!isError);
                t.classList.add('show');
                setTimeout(() => t.classList.remove('show'), 4500);
            }

            function setErr(msg) {
                document.getElementById('formStatus').innerHTML = msg ? `<span class="err">${msg}</span>` : '';
            }

            const f = {
                from: document.getElementById('from'),
                to: document.getElementById('to'),
                farm_id: document.getElementById('farm_id'),
                sale_status: document.getElementById('sale_status'),
                email: document.getElementById('email'),
            };
            const btnSend = document.getElementById('btnSend');

            (async function loadFarms() {
                try {
                    const data = await fetchJSON('/user/farm/show-all-farms');
                    const list = data.data || [];
                    const sel = f.farm_id;
                    const cur = sel.value;
                    sel.innerHTML = '<option value="">—</option>';
                    list.forEach(it => {
                        const opt = document.createElement('option');
                        opt.value = it.id;
                        opt.textContent = it.name || ('#' + it.id);
                        sel.appendChild(opt);
                    });
                    if (cur) sel.value = cur;
                } catch (e) {
                    }
            })();

            document.getElementById('reportForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                setErr('');
                const from = f.from.value,
                    to = f.to.value;
                if (!from || !to) {
                    setErr('الرجاء تحديد الفترة كاملة.');
                    return;
                }
                if (new Date(from) > new Date(to)) {
                    setErr('تاريخ البداية يجب أن يسبق تاريخ النهاية.');
                    return;
                }

                const params = new URLSearchParams();
                params.set('from', from);
                params.set('to', to);
                if (f.farm_id.value) params.set('farm_id', f.farm_id.value);
                if (f.sale_status.value) params.set('sale_status', f.sale_status.value);
                if (f.email.value.trim()) params.set('email', f.email.value.trim());

                try {
                    btnSend.disabled = true;
                    const data = await fetchJSON(`/user/report/summary?${params.toString()}`, {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': token
                        }
                    });
                    showToast(
                        'تم إنشاء التقرير وإرساله. يرجى التحقق من بريدك الإلكتروني لرؤية محتوى التقرير.'
                        );
                } catch (err) {
                    const errs = (err && err.data && err.data.errors) ? Object.values(err.data.errors)
                    .flat().join(' | ') : '';
                    setErr(errs || err.message);
                    showToast('تعذر إرسال التقرير. تحقق من الحقول وحاول مجددًا.', true);
                } finally {
                    btnSend.disabled = false;
                }
            });
        })();
    </script>
</body>

</html>
