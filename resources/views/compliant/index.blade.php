<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>الشكاوى والمقترحات</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preload" as="image" href="{{ asset('/background/comp/ima1.webp') }}" fetchpriority="high">
    <link rel="preload" as="image" href="{{ asset('/background/comp/ima2.webp') }}">
    <style>
        :root {
            --bg: #f3faf7;
            --card: #fff;
            --text: #0e1a14;
            --muted: #6d7a74;
            --accent: #2fb46e;
            --accent-2: #0f8a54;
            --stroke: #e6efe9;
            --shadow: 0 10px 30px rgba(16, 24, 20, .10), 0 2px 10px rgba(16, 24, 20, .05);
            --radius: 18px;
            --transition: .25s ease;
            --max-card-w: 860px;
        }

        * {
            box-sizing: border-box;
            font-family: "Segoe UI", Tahoma, Arial, system-ui;
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
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
            transition: opacity 1.1s ease, transform 12s ease;
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
            max-width: 1200px;
            margin: 28px auto;
            padding: 0 14px;
        }

        header {
            background: #ffffffcc;
            backdrop-filter: blur(8px);
            border: 1px solid var(--stroke);
            border-radius: calc(var(--radius) + 4px);
            padding: 18px 22px;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        h1 {
            margin: 0;
            font-size: 22px;
            background: linear-gradient(135deg, var(--accent-2), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .center {
            min-height: calc(100dvh - 140px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .card {
            width: min(100%, var(--max-card-w));
            background: var(--card);
            border: 1px solid var(--stroke);
            border-radius: 22px;
            box-shadow: var(--shadow);
            padding: 22px;
        }

        .title {
            font-weight: 900;
            font-size: 20px;
            margin: 0 0 12px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .input {
            background: #fff;
            border: 1px solid var(--stroke);
            border-radius: 14px;
            padding: 14px 16px;
            font-size: 16px;
            width: 100%;
        }

        textarea.input {
            min-height: 180px;
            resize: vertical;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 6px;
        }

        .btn {
            all: unset;
            cursor: pointer;
            padding: 12px 18px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--accent), #43d184);
            color: #fff;
            font-weight: 800;
            box-shadow: var(--shadow);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn:disabled {
            opacity: .55;
            cursor: not-allowed;
        }

        .status {
            margin-top: 12px;
            font-size: 15px;
            text-align: center;
        }

        .ok {
            color: #0d7d4b;
            background: #e8f7ef;
            padding: 10px 14px;
            border-radius: 12px;
            display: inline-block;
        }

        .err {
            color: #b00020;
            background: #ffebee;
            padding: 10px 14px;
            border-radius: 12px;
            display: inline-block;
        }

        header .btn-nav {
            all: unset;
            cursor: pointer;
            padding: 10px 12px;
            border-radius: 999px;
            background: #f5fff9;
            color: var(--accent-2);
            border: 1px solid var(--stroke);
            box-shadow: var(--shadow);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 800;

            position: absolute;
            left: 22px;
            top: 50%;
            transform: translateY(-50%);
            transition: none;
        }

        header .btn-nav:hover,
        header .btn-nav:focus,
        header .btn-nav:active {
            transform: translateY(-50%);
            box-shadow: var(--shadow);
        }

        .btn-nav .icon {
            width: 18px;
            height: 18px;
            line-height: 0;
        }

        .btn-nav .label {
            font-weight: 800;
        }

        @media (max-width:560px) {
            .btn-nav .label {
                display: none;
            }

            .btn-nav {
                padding-inline: 10px;
            }
        }
    </style>

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
                    "{{ asset('/background/comp/ima1.jpg') }}",
                    "{{ asset('/background/comp/ima2.jpg') }}",

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
