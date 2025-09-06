<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>تسجيل الدخول — Nature UI</title>
    <link rel="stylesheet" href="{{ asset('css/nature.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login_user.css') }}">


</head>

<body>
    <div class="floaters" aria-hidden="true">
        <div class="leaf" style="right:5vw; top:85vh; --tx:-15vw; --dur:18s"></div>
        <div class="leaf" style="left:10vw; top:90vh; --tx:20vw; --dur:22s"></div>
        <div class="bubble" style="left:40vw; top:95vh; --tx:-5vw; --dur:24s"></div>
    </div>

    <div class="wrap">
        <section class="hero">
            <div class="brand">
                <div class="leaf-logo" aria-hidden="true">
                    <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="leafGrad" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="#22c55e" />
                                <stop offset="100%" stop-color="#38bdf8" />
                            </linearGradient>
                        </defs>
                        <path d="M54 10C30 10 12 22 10 46c-.3 3.7 2.6 6.6 6.3 6.3C40.7 50 52 32 54 10Z"
                            fill="url(#leafGrad)" />
                        <path d="M20 44c10-4 18-12 28-26" fill="none" stroke="#eafff4" stroke-width="3"
                            stroke-linecap="round" />
                    </svg>
                </div>
                <h1>منصّة المزارع الذكية — Nature UI</h1>
            </div>
            <div class="hero-copy">
                <h2>مرحباً بعودتك </h2>
                <p>قم بتسجيل الدخول للمتابعة إلى حسابك.</p>
            </div>

            <figure class="hero-art" aria-hidden="true">
                <svg class="wiggle" viewBox="0 0 520 520" xmlns="http://www.w3.org/2000/svg" role="img"
                    aria-label="Animated growing tree">

                    <path class="ground" pathLength="1" d="M80 430 Q260 390 440 430" style="--delay:.15s" />

                    <g class="tuft" style="--delay:.45s">
                        <path d="M228 426 q-6 -18 0 -34" />
                        <path d="M220 428 q-5 -14 0 -24" />
                        <path d="M236 430 q-4 -10 0 -18" />
                        <path d="M292 426 q6 -18 0 -34" />
                        <path d="M300 428 q5 -14 0 -24" />
                        <path d="M284 430 q4 -10 0 -18" />
                    </g>

                    <path class="tree-line" pathLength="1" style="--delay:0s"
                        d="M260 430 C260 380 260 330 260 280 C260 250 260 220 260 195" />

                    <path class="tree-line" pathLength="1" style="--delay:.22s" d="M260 300 C210 288 176 252 164 214" />
                    <path class="tree-line" pathLength="1" style="--delay:.30s" d="M260 294 C310 282 344 248 356 214" />

                    <path class="tree-line" pathLength="1" style="--delay:.38s" d="M260 250 C234 230 204 202 192 178" />
                    <path class="tree-line" pathLength="1" style="--delay:.46s" d="M260 246 C286 226 318 202 330 178" />

                    <path class="tree-line" pathLength="1" style="--delay:.54s" d="M260 205 C260 188 260 165 260 145" />

                    <g transform="translate(164 214)">
                        <g class="petal3" style="--delay:1.0s">
                            <circle class="petal" cx="0" cy="-8" r="5.5" />
                            <circle class="petal" cx="6.5" cy="3.5" r="5.5" />
                            <circle class="petal" cx="-6.5" cy="3.5" r="5.5" />
                        </g>
                    </g>

                    <g transform="translate(356 214)">
                        <g class="petal3" style="--delay:1.05s">
                            <circle class="petal" cx="0" cy="-8" r="5.5" />
                            <circle class="petal" cx="6.5" cy="3.5" r="5.5" />
                            <circle class="petal" cx="-6.5" cy="3.5" r="5.5" />
                        </g>
                    </g>

                    <g transform="translate(192 178)">
                        <g class="petal3" style="--delay:1.1s">
                            <circle class="petal" cx="0" cy="-7.5" r="5.2" />
                            <circle class="petal" cx="6.2" cy="3.2" r="5.2" />
                            <circle class="petal" cx="-6.2" cy="3.2" r="5.2" />
                        </g>
                    </g>

                    <g transform="translate(330 178)">
                        <g class="petal3" style="--delay:1.15s">
                            <circle class="petal" cx="0" cy="-7.5" r="5.2" />
                            <circle class="petal" cx="6.2" cy="3.2" r="5.2" />
                            <circle class="petal" cx="-6.2" cy="3.2" r="5.2" />
                        </g>
                    </g>

                    <g transform="translate(260 145)">
                        <g class="petal3" style="--delay:1.2s">
                            <circle class="petal" cx="0" cy="-7" r="5" />
                            <circle class="petal" cx="6" cy="3" r="5" />
                            <circle class="petal" cx="-6" cy="3" r="5" />
                        </g>
                    </g>
                </svg>
            </figure>

        </section>

        <section class="card">
            <header>
                <h3>تسجيل الدخول</h3>
                <span class="xsmall">ليس لديك حساب؟ <a href="{{ url('/register') }}">إنشاء حساب</a></span>
            </header>
            <div class="divider"></div>

            @if (session('status'))
                <div class="toast show" style="display:block">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="toast show" style="display:block">
                    @foreach ($errors->all() as $error)
                        <div>⚠️ {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form id="loginForm" method="POST" action="{{ route('login') }}" autocomplete="off">
                @csrf
                <div class="row">
                    <div>
                        <label>البريد الإلكتروني</label>
                        <div class="field">
                            <input type="email" name="email" value="{{ old('email') }}" required
                                autocomplete="username" spellcheck="false" autocapitalize="off" autocorrect="off"
                                inputmode="email" readonly onfocus="this.removeAttribute('readonly');">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div>
                        <label>كلمة المرور</label>
                        <div class="field">
                            <input type="password" name="password" minlength="6" required
                                autocomplete="current-password" spellcheck="false" autocapitalize="off"
                                autocorrect="off" readonly onfocus="this.removeAttribute('readonly');">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--muted)">
                        <input type="checkbox" name="remember" value="1" style="width:16px;height:16px"
                            {{ old('remember') ? 'checked' : '' }}> تذكرني
                    </label>
                </div>

                <button class="btn" type="submit" style="margin-top:6px">تسجيل الدخول</button>
                <button type="button" class="btn"
                    style="margin-top:10px;background:transparent;border:1px solid var(--stroke);color:var(--muted);box-shadow:none"
                    onclick="openOtpModal()">
                    نسيت كلمة المرور؟
                </button>
            </form>
        </section>
    </div>

    <div id="otpModalBackdrop" class="modal-backdrop" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="otpModalTitle">
            <header>
                <h4 id="otpModalTitle">إرسال رمز إعادة التعيين</h4>
                <button class="btn-ghost" type="button" onclick="closeOtpModal()">إغلاق</button>
            </header>

            <form id="otpSendForm" onsubmit="return sendOtp(event)">
                @csrf
                <label class="muted">البريد الإلكتروني</label>
                <div class="field">
                    <input type="email" id="otpEmail" name="email" value="{{ old('email') }}" required
                        autocomplete="email" inputmode="email" spellcheck="false" autocapitalize="off"
                        autocorrect="off">
                </div>

                <div class="actions">
                    <button class="btn-ghost" type="button" onclick="closeOtpModal()">إلغاء</button>
                    <button class="btn" type="submit">إرسال الرمز</button>
                </div>
                <p id="otpHint" class="xsmall" style="margin-top:8px;display:none"></p>
            </form>
        </div>
    </div>

    <div id="loadingBackdrop" class="loading">
        <div class="spinner"></div>
    </div>

    <div id="toast" class="toast"></div>

    <script>
        const otpModalBackdrop = document.getElementById('otpModalBackdrop');
        const loadingBackdrop = document.getElementById('loadingBackdrop');
        const toastEl = document.getElementById('toast');
        const otpEmailInput = document.getElementById('otpEmail');
        const otpHint = document.getElementById('otpHint');

        function openOtpModal() {
            const loginEmail = document.querySelector('input[name="email"]');
            if (loginEmail && loginEmail.value) otpEmailInput.value = loginEmail.value;

            otpHint.style.display = 'none';
            otpHint.textContent = '';
            otpModalBackdrop.classList.add('show');
            setTimeout(() => otpEmailInput.focus(), 50);
        }

        function closeOtpModal() {
            otpModalBackdrop.classList.remove('show');
        }

        function showToast(msg) {
            if (!toastEl) return;
            toastEl.innerHTML = msg;
            toastEl.classList.add('show');
            setTimeout(() => toastEl.classList.remove('show'), 3500);
        }

        async function sendOtp(e) {
            e.preventDefault();
            const email = otpEmailInput.value.trim();
            if (!email) {
                otpEmailInput.focus();
                return false;
            }

            const url = "{{ url('/user/auth/password/otp/send') }}";
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            loadingBackdrop.classList.add('show');
            otpHint.style.display = 'none';
            otpHint.textContent = '';

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: (() => {
                        const fd = new FormData();
                        fd.append('email', email);
                        return fd;
                    })()
                });

                const data = await res.json().catch(() => ({}));

                if (res.ok) {
                    showToast(data?.message || 'تم إرسال الرمز إن كان البريد موجودًا لدينا.');
                    closeOtpModal();
                    setTimeout(() => {
                        window.location.href = "{{ route('password.otp.form') }}" + "?email=" +
                            encodeURIComponent(email);
                    }, 600);
                } else {
                    const msg = (data?.message) ||
                        (data?.errors?.email?.[0]) ||
                        'تعذر إرسال الرمز، حاول لاحقًا.';
                    otpHint.textContent = msg;
                    otpHint.style.display = 'block';
                    showToast('⚠️ ' + msg);
                }
            } catch (err) {
                otpHint.textContent = 'حدث خطأ غير متوقع. تحقق من اتصالك.';
                otpHint.style.display = 'block';
                showToast('⚠️ حدث خطأ غير متوقع.');
            } finally {
                loadingBackdrop.classList.remove('show');
            }

            return false;
        }

        otpModalBackdrop?.addEventListener('click', (e) => {
            if (e.target === otpModalBackdrop) closeOtpModal();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && otpModalBackdrop?.classList.contains('show')) closeOtpModal();
        });
    </script>

</body>

</html>
