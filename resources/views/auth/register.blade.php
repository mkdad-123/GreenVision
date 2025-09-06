<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>إنشاء حساب — Nature UI</title>
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

        .sprout-art {
            position: absolute;
            right: 42px;
            bottom: 38px;
            width: min(42vw, 540px);
            aspect-ratio: 1/1;
            opacity: .95;
            pointer-events: none;
            user-select: none;
            filter: drop-shadow(0 6px 30px rgba(255, 255, 255, .10));
        }

        @media(max-width:980px) {
            .sprout-art {
                display: none;
            }
        }

        .s-ground {
            fill: none;
            stroke: #fff;
            stroke-opacity: .9;
            stroke-width: 4.2;
            stroke-linecap: round;
            stroke-dasharray: 1;
            stroke-dashoffset: 1;
            animation: s-ground-in .9s ease forwards;
        }

        .s-stem {
            fill: none;
            stroke: #fff;
            stroke-width: 4.2;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: 1;
            stroke-dashoffset: 1;
            animation: s-draw 1.8s cubic-bezier(.22, .7, .18, 1) forwards;
            animation-delay: .15s;
            filter: drop-shadow(0 2px 10px rgba(255, 255, 255, .10));
        }

        .s-seed {
            fill: #fff;
            opacity: 0;
            transform-box: fill-box;
            transform-origin: center;
            animation: s-seed-pop .6s ease .2s forwards;
        }

        .s-tuft path {
            fill: none;
            stroke: #fff;
            stroke-width: 2.2;
            stroke-linecap: round;
            opacity: 0;
            transform-origin: bottom center;
            animation: s-tuft-in .6s ease .25s forwards, s-wind 5.5s ease-in-out .85s infinite alternate;
        }

        .leaf {
            fill: #fff;
            opacity: 0;
            transform-box: fill-box;
            transform-origin: bottom center;
            animation: leaf-open .8s ease forwards;
        }

        .leaf.left {
            animation-delay: .95s;
            transform: rotate(-18deg) scale(.35);
        }

        .leaf.right {
            animation-delay: 1.05s;
            transform: rotate(18deg) scale(.35);
        }

        .s-wiggle {
            animation: s-sway 6s ease-in-out infinite alternate;
        }

        @keyframes s-draw {
            to {
                stroke-dashoffset: 0;
            }
        }

        @keyframes s-ground-in {
            to {
                stroke-dashoffset: 0;
            }
        }

        @keyframes s-seed-pop {
            from {
                opacity: 0;
                transform: scale(.4)
            }

            to {
                opacity: 1;
                transform: scale(1)
            }
        }

        @keyframes s-tuft-in {
            from {
                opacity: 0;
                transform: scale(.8) translateY(6px)
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0)
            }
        }

        @keyframes s-wind {
            from {
                transform: skewX(0deg)
            }

            to {
                transform: skewX(2deg)
            }
        }

        @keyframes s-sway {
            from {
                transform: translateY(0)
            }

            to {
                transform: translateY(-6px)
            }
        }

        @keyframes leaf-open {
            0% {
                opacity: 0;
                transform: scale(.35) rotate(var(--rot, 0));
            }

            70% {
                opacity: 1;
                transform: scale(1.15) rotate(calc(var(--rot, 0) * .6));
            }

            100% {
                opacity: 1;
                transform: scale(1) rotate(0deg);
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .s-ground,
            .s-stem,
            .s-seed,
            .s-tuft path,
            .leaf,
            .s-wiggle {
                animation: none !important;
                opacity: .95;
                transform: none !important;
            }
        }

        .leaf-group {
            opacity: 0;
            transform-box: fill-box;
            transform-origin: bottom center;
            animation: leaf-pop .7s ease forwards;
            animation-delay: .95s;
        }

        .leaf-outline {
            fill: none;
            stroke: #fff;
            stroke-width: 3.2;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: 1;
            stroke-dashoffset: 1;
            animation: leaf-draw .9s ease forwards;
            animation-delay: 1.0s;
            filter: drop-shadow(0 2px 8px rgba(255, 255, 255, .10));
        }

        .leaf-fill {
            fill: #fff;
            opacity: 0;
            animation: leaf-fill-in .55s ease forwards;
            animation-delay: 1.12s;
        }

        .leaf-vein-cut {
            fill: none;
            stroke: #000;
            stroke-linecap: round;
            stroke-width: 2.6;
            stroke-dasharray: 1;
            stroke-dashoffset: 1;
            animation: leaf-draw .9s ease forwards;
            animation-delay: 1.18s;
        }

        @keyframes leaf-pop {
            0% {
                transform: scale(.35) rotate(-2deg);
                opacity: 0;
            }

            70% {
                transform: scale(1.12) rotate(0deg);
                opacity: 1;
            }

            100% {
                transform: scale(1) rotate(0deg);
                opacity: 1;
            }
        }

        @keyframes leaf-draw {
            to {
                stroke-dashoffset: 0;
            }
        }

        @keyframes leaf-fill-in {
            from {
                opacity: 0;
                transform: translateY(2px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }
    </style>
</head>

<body>
    <div class="floaters" aria-hidden="true">
        <div class="leaf" style="right:5vw; top:85vh; --tx:-15vw; --dur:18s"></div>
        <div class="leaf" style="left:10vw; top:90vh; --tx: 20vw; --dur:22s"></div>
        <div class="bubble" style="left:40vw; top:95vh; --tx:-5vw;  --dur:24s"></div>
        <div class="bubble" style="right:25vw; top:92vh; --tx: 8vw;  --dur:20s"></div>
    </div>

    <div class="wrap">
        <section class="hero">
            <div class="brand">
                <div class="leaf-logo" aria-hidden="true">
                    <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" role="img">
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
                <h2>ابدأ رحلتك معنا </h2>
                <p>انشىء حسابا لتبدأ رحلتك مع المنصة الذكية </p>
            </div>

            <figure class="sprout-art" aria-hidden="true">
                <svg class="s-wiggle" viewBox="0 0 520 520" xmlns="http://www.w3.org/2000/svg" role="img"
                    aria-label="Sprouting leaf">
                    <path class="s-ground" d="M80 430 Q260 390 440 430" />

                    <g class="s-tuft">
                        <path d="M228 426 q-6 -18 0 -34" />
                        <path d="M220 428 q-5 -14 0 -24" />
                        <path d="M236 430 q-4 -10 0 -18" />
                        <path d="M292 426 q6 -18 0 -34" />
                        <path d="M300 428 q5 -14 0 -24" />
                        <path d="M284 430 q4 -10 0 -18" />
                    </g>
                    <circle class="s-seed" cx="260" cy="430" r="7" />
                    <path class="s-stem" d="M260 430 C 258 404, 260 368, 262 338" />
                    <g transform="translate(262 338)">
                        <defs>
                            <mask id="leafMaskUp" maskContentUnits="userSpaceOnUse">
                                <path d="M0 0
               C -8 8,  -18 12, -26 10
               C -58 -8, -40 -52,  0 -74
               C  40 -52, 58  -8, 26  10
               C  18 12,   8   8,  0   0 Z" fill="#fff" />

                                <path class="leaf-vein-cut" pathLength="1" d="M0 -2  C 0 -24,  0 -48,  0 -72" />
                                <path class="leaf-vein-cut" pathLength="1" d="M0 -18 C 10 -22, 22 -28, 30 -36" />
                                <path class="leaf-vein-cut" pathLength="1" d="M0 -34 C  9 -38, 17 -46, 24 -54" />
                                <path class="leaf-vein-cut" pathLength="1" d="M0 -18 C -10 -22, -22 -28, -30 -36" />
                                <path class="leaf-vein-cut" pathLength="1" d="M0 -34 C  -9 -38, -17 -46, -24 -54" />
                            </mask>
                        </defs>

                        <g class="leaf-group">
                            <path class="leaf-outline" pathLength="1" d="M0 0
             C -8 8,  -18 12, -26 10
             C -58 -8, -40 -52,  0 -74
             C  40 -52, 58  -8, 26  10
             C  18 12,   8   8,  0   0 Z" />

                            <path class="leaf-fill" mask="url(#leafMaskUp)" d="M0 0
             C -8 8,  -18 12, -26 10
             C -58 -8, -40 -52,  0 -74
             C  40 -52, 58  -8, 26  10
             C  18 12,   8   8,  0   0 Z" />
                        </g>
                    </g>

                </svg>
            </figure>

        </section>

        <section class="card">
            <header>
                <h3>إنشاء حساب</h3>
                <span class="xsmall">لديك حساب؟ <a href="{{ route('login') }}">تسجيل الدخول</a></span>
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

            <form id="registerForm" method="POST" action="{{ route('register') }}" autocomplete="off">
                @csrf
                <div class="row">
                    <div>
                        <label>الاسم الكامل</label>
                        <div class="field">
                            <input type="text" name="name" value="{{ old('name') }}" required
                                autocomplete="name" spellcheck="false" autocapitalize="off" autocorrect="off">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div>
                        <label>البريد الإلكتروني</label>
                        <div class="field">
                            <input type="email" name="email" value="{{ old('email') }}" required
                                autocomplete="email" spellcheck="false" autocapitalize="off" autocorrect="off"
                                inputmode="email">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div>
                        <label>كلمة المرور</label>
                        <div class="field">
                            <input type="password" name="password" minlength="8" required
                                autocomplete="new-password" spellcheck="false" autocapitalize="off"
                                autocorrect="off">
                        </div>
                    </div>
                </div>

                <button class="btn" type="submit" style="margin-top:6px">إنشاء الحساب</button>
            </form>
        </section>
    </div>

</body>

</html>
