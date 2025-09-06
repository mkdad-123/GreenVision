<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>تسجيل الدخول — Nature UI</title>
    <link rel="stylesheet" href="{{ asset('css/nature.css') }}">
    <style>
        :root {
            --bg: #123329;
            --bg2: #123d60;
            --earth: #6b4c36;
            --leaf: #4ade80;
            --leaf-d: #22c55e;
            --sky: #60d4fb;
            --sand: #e4c7a8;
            --surface: #163529;
            --surface-2: #183a4d;
            --stroke: #255a46;
            --text: #f0fdf4;
            --muted: #bbf7d0;

            --radius: 18px;
            --shadow: 0 20px 60px rgba(0, 0, 0, .25);
        }

        * {
            box-sizing: border-box;
            font-family: "Segoe UI", Tahoma, Arial, system-ui
        }

        html,
        body {
            height: 100%
        }

        body {
            margin: 0;
            color: var(--text);
            background:
                radial-gradient(1000px 700px at 10% -10%, #1a5c44 0%, var(--bg) 60%),
                radial-gradient(1200px 900px at 120% 10%, #165c88 0%, var(--bg2) 55%),
                linear-gradient(180deg, rgba(228, 199, 168, .08), transparent 30%);
            overflow: hidden;
        }

        .floaters {
            position: fixed;
            inset: 0;
            pointer-events: none;
            overflow: hidden
        }

        .leaf,
        .bubble {
            position: absolute;
            opacity: .12;
            filter: blur(0.2px);
            animation: drift var(--dur, 16s) linear infinite;
        }

        .leaf {
            width: 120px;
            height: 120px;
            border-radius: 40% 60% 60% 40%/60% 40% 60% 40%;
            background: radial-gradient(circle at 30% 30%, #6ee7b7 0%, var(--leaf-d) 50%, transparent 70%);
        }

        .bubble {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, #dbf4ff 0%, rgba(96, 212, 251, .5) 60%, transparent 70%);
        }

        @keyframes drift {
            to {
                transform: translate3d(var(--tx, 0px), -120vh, 0) rotate(360deg);
            }
        }

        .wrap {
            position: relative;
            display: grid;
            grid-template-columns: 1fr;
            min-height: 100vh
        }

        @media(min-width:980px) {
            .wrap {
                grid-template-columns: 1.1fr .9fr
            }
        }

        .hero {
            position: relative;
            padding: 32px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: linear-gradient(180deg, rgba(74, 222, 128, .07), transparent 50%),
                linear-gradient(0deg, rgba(96, 212, 251, .06), transparent 40%);
            border-inline-start: 1px solid var(--stroke);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px
        }

        .leaf-logo {
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(255, 255, 255, .06), rgba(255, 255, 255, .02));
            box-shadow: 0 0 0 6px rgba(74, 222, 128, .12), 0 0 0 12px rgba(96, 212, 251, .06)
        }

        .leaf-logo svg {
            width: 28px;
            height: 28px;
            display: block
        }

        .brand h1 {
            margin: 0;
            font-size: 20px
        }

        .hero-copy {
            max-width: 560px;
            margin-top: 18vh
        }

        .hero-copy h2 {
            margin: 0 0 10px;
            font-size: 40px;
            line-height: 1.1
        }

        .hero-copy p {
            margin: 0;
            color: var(--muted)
        }

        .card {
            position: relative;
            align-self: center;
            justify-self: center;
            width: min(94vw, 520px);
            background: linear-gradient(180deg, rgba(255, 255, 255, .02), rgba(255, 255, 255, .00));
            border: 1px solid var(--stroke);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 24px;
            backdrop-filter: blur(10px);
            animation: pop .6s cubic-bezier(.2, .8, .2, 1);
        }

        @keyframes pop {
            from {
                transform: translateY(10px) scale(.98);
                opacity: 0
            }

            to {
                transform: none;
                opacity: 1
            }
        }

        .card header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px
        }

        .card header h3 {
            margin: 0;
            font-size: 22px
        }

        .muted {
            color: var(--muted);
            font-size: 13px
        }

        form {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
            margin-top: 10px
        }

        .row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px
        }

        @media(min-width:520px) {
            .row {
                grid-template-columns: 1fr 1fr
            }
        }

        label {
            font-size: 13px;
            color: var(--muted)
        }

        .field {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #154236;
            border: 1px solid var(--stroke);
            border-radius: 14px;
            padding: 10px 12px;
            transition: box-shadow .3s
        }

        .field:focus-within {
            box-shadow: 0 0 0 3px rgba(74, 222, 128, .25)
        }

        .field input {
            all: unset;
            direction: rtl;
            color: var(--text);
            width: 100%;
            font-size: 14px
        }

        .btn {
            all: unset;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: linear-gradient(135deg, var(--leaf), var(--leaf-d));
            color: #052e16;
            border-radius: 14px;
            padding: 12px 14px;
            cursor: pointer;
            font-weight: 700;
            box-shadow: 0 10px 30px rgba(34, 197, 94, .25);
        }

        .btn:hover {
            transform: translateY(-1px)
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .08), transparent);
            margin: 10px 0
        }

        .toast {
            position: fixed;
            bottom: 16px;
            left: 50%;
            transform: translateX(-50%);
            background: #154236;
            border: 1px solid var(--stroke);
            padding: 12px 16px;
            border-radius: 12px;
            display: none;
            min-width: 280px;
            text-align: center
        }

        .toast.show {
            display: block
        }

        .loading {
            position: fixed;
            inset: 0;
            display: none;
            place-items: center;
            background: rgba(2, 6, 23, .35)
        }

        .loading.show {
            display: grid
        }

        .spinner {
            width: 56px;
            height: 56px;
            border: 6px solid #154236;
            border-top-color: var(--leaf);
            border-radius: 50%;
            animation: spin 1s linear infinite
        }

        @keyframes spin {
            to {
                transform: rotate(360deg)
            }
        }

        .xsmall {
            font-size: 12px;
            color: var(--muted)
        }

        a {
            color: var(--sky);
            text-decoration: none
        }

        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            display: none;
            place-items: center;
            z-index: 50;
        }

        .modal-backdrop.show {
            display: grid;
        }

        .modal {
            width: min(92vw, 480px);
            background: #154236;
            border: 1px solid var(--stroke);
            border-radius: 14px;
            box-shadow: var(--shadow);
            padding: 18px;
            color: var(--text);
            animation: pop .25s ease;
        }

        .modal header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px
        }

        .modal h4 {
            margin: 0;
            font-size: 18px
        }

        .modal .field {
            background: #133a2f;
            border-color: #2a6b57;
        }

        .modal .actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 12px
        }

        .btn-ghost {
            all: unset;
            cursor: pointer;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid #2a6b57;
            color: #bbf7d0;
        }

        .hero {
            overflow: hidden;
        }

        .hero-art {
            position: absolute;
            right: 42px;
            bottom: 38px;
            width: min(43vw, 560px);
            aspect-ratio: 1/1;
            opacity: .95;
            pointer-events: none;
            user-select: none;
            filter: drop-shadow(0 6px 30px rgba(255, 255, 255, .10));
        }

        @media(max-width: 980px) {
            .hero-art {
                display: none;
            }
        }

        .tree-line {
            fill: none;
            stroke: #fff;
            stroke-width: 4.2;
            stroke-linecap: round;
            stroke-linejoin: round;
            pathLength: 1;
            stroke-dasharray: 1;
            stroke-dashoffset: 1;
            animation: tree-draw 2.2s cubic-bezier(.22, .7, .18, 1) forwards;
            animation-delay: var(--delay, 0s);
            filter: drop-shadow(0 2px 10px rgba(255, 255, 255, .10));
        }


        .petal {
            fill: #fff;
            opacity: .95;
        }

        .petal3 {
            opacity: 0;
            transform-box: fill-box;
            transform-origin: center;
            animation: petal-in .7s ease forwards;
            animation-delay: var(--delay, 0s);
        }

        @keyframes petal-in {
            0% {
                opacity: 0;
                transform: scale(.35);
            }

            70% {
                opacity: 1;
                transform: scale(1.12);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }


        .fruit {
            fill: #fff;
            opacity: 0;
            transform-origin: center;
            animation: fruit-in .85s cubic-bezier(.2, .9, .2, 1) forwards;
            animation-delay: var(--delay, 0s);
            filter: drop-shadow(0 0 14px rgba(255, 255, 255, .18));
        }

        .ground {
            fill: none;
            stroke: #fff;
            stroke-opacity: .9;
            stroke-width: 4.2;
            stroke-linecap: round;
            stroke-dasharray: 1;
            stroke-dashoffset: 1;
            animation: ground-in .9s ease forwards;
            filter: drop-shadow(0 8px 30px rgba(0, 0, 0, .20));
        }

        .tuft path {
            fill: none;
            stroke: #fff;
            stroke-width: 2.2;
            stroke-linecap: round;
            opacity: 0;
            transform-origin: bottom center;
            animation: tuft-in .6s ease forwards, wind 5.5s ease-in-out .6s infinite alternate;
            animation-delay: var(--delay, 0s);
        }

        .wiggle {
            animation: sway 6s ease-in-out infinite alternate;
        }

        @keyframes tree-draw {
            to {
                stroke-dashoffset: 0;
            }
        }

        @keyframes flower-in {
            0% {
                opacity: 0;
                transform: scale(.35) rotate(-8deg);
            }

            60% {
                opacity: 1;
                transform: scale(1.15) rotate(2deg);
            }

            100% {
                opacity: 1;
                transform: scale(1) rotate(0deg);
            }
        }

        @keyframes fruit-in {
            0% {
                opacity: 0;
                transform: scale(.25);
            }

            70% {
                opacity: 1;
                transform: scale(1.18);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes ground-in {
            to {
                stroke-dashoffset: 0;
            }
        }

        @keyframes tuft-in {
            from {
                opacity: 0;
                transform: scale(.7) translateY(6px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes wind {
            from {
                transform: skewX(0deg);
            }

            to {
                transform: skewX(2.2deg);
            }
        }

        @keyframes sway {
            from {
                transform: translateY(0)
            }

            to {
                transform: translateY(-6px)
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .tree-line,
            .flower,
            .fruit,
            .ground,
            .tuft path,
            .wiggle {
                animation: none !important;
                opacity: .95;
            }
        }

        .flower,
        .fruit,
        .bud {
            transform-box: fill-box;
            transform-origin: center;
        }
    </style>

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
