<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>الشكاوى والمقترحات</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="preload" as="image" href="{{ asset('storage/background/comp/ima1.webp') }}" fetchpriority="high">
    <link rel="preload" as="image" href="{{ asset('storage/background/comp/ima2.webp') }}">
    <link rel="stylesheet" href="{{ asset('css/compliant.css') }}">


</head>

<body>
    <div class="bg-slideshow" aria-hidden="true">
        <img id="bgA" alt="" decoding="async" loading="eager">
        <img id="bgB" alt="" decoding="async" loading="lazy">
    </div>

    <div class="wrap">

        <header>
            <a class="btn-nav" href="{{ route('home') ?? '/' }}" aria-label="العودة للواجهة الرئيسية"
                title="الواجهة الرئيسية">
                <span class="icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 11l9-8 9 8"></path>
                        <path d="M9 22V12h6v10"></path>
                    </svg>
                </span>
                <span class="label">الرئيسية</span>
            </a>

            <h1 style="text-align:center;">الشكاوى والمقترحات</h1>

            <div></div>
        </header>


        <div class="center">
            <div class="card" id="formCard">
                <h2 class="title">📮 أرسل شكواك أو مقترحك</h2>
                <form id="complaintForm">
                    <div class="form-grid">
                        <div class="field">
                            <label>العنوان</label>
                            <input type="text" id="title" class="input" required
                                placeholder="اكتب عنوانًا مختصرًا">
                        </div>
                        <div class="field">
                            <label>الوصف</label>
                            <textarea id="description" class="input" required placeholder="اشرح المشكلة أو المقترح بالتفصيل"></textarea>
                        </div>
                    </div>
                    <div class="actions">
                        <button type="submit" class="btn" id="btnSave">إرسال</button>
                    </div>
                    <div class="status" id="formStatus"></div>
                </form>
            </div>

            <div class="status" id="successOnly" style="display:none;">
                <span class="ok">تم الإرسال بنجاح، شكرًا لمشاركتك.</span>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const token = document.querySelector('meta[name="csrf-token"]').content;

            (function() {
                const imgs = [
                    "{{ asset('storage/background/comp/ima1.jpg') }}",
                    "{{ asset('storage/background/comp/ima2.jpg') }}",

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
                if (!ct.includes('application/json')) {
                    const snippet = raw.replace(/\s+/g, ' ').slice(0, 200);
                    throw new Error(`Non-JSON response (${resp.status}). ${snippet}…`);
                }
                let data;
                try {
                    data = JSON.parse(raw);
                } catch {
                    throw new Error('فشل تحليل JSON');
                }
                if (!resp.ok || data?.ok === false) {
                    const validation = data?.errors ? Object.values(data.errors).flat().join(' | ') : '';
                    const msg = data?.message || validation || 'Request failed';
                    const err = new Error(msg);
                    err.data = data;
                    err.status = resp.status;
                    throw err;
                }
                return data;
            }

            const form = document.getElementById('complaintForm');
            const btnSave = document.getElementById('btnSave');
            const formCard = document.getElementById('formCard');
            const successOnly = document.getElementById('successOnly');
            const formStatus = document.getElementById('formStatus');
            const f = {
                title: document.getElementById('title'),
                description: document.getElementById('description')
            };
            const setStatus = (msg, ok = false) => {
                formStatus.innerHTML = msg ? `<span class="${ok?'ok':'err'}">${msg}</span>` : '';
            };

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const payload = {
                    title: (f.title.value || '').trim(),
                    description: (f.description.value || '').trim()
                };
                if (!payload.title || !payload.description) {
                    setStatus('الرجاء تعبئة جميع الحقول المطلوبة');
                    return;
                }
                try {
                    btnSave.disabled = true;
                    await fetchJSON('/user/compliant/add-compliant', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });
                    formCard.style.display = 'none';
                    successOnly.style.display = 'block';
                } catch (err) {
                    const errs = (err && err.data && err.data.errors) ? Object.values(err.data.errors)
                        .flat().join(' | ') : '';
                    setStatus(errs || err.message, false);
                } finally {
                    btnSave.disabled = false;
                }
            });
        })();
    </script>
</body>

</html>
