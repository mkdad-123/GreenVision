<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>إنشاء حساب — Nature UI</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">

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
