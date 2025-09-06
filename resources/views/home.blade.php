<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>الصفحة الرئيسية — Nature UI</title>
    <link rel="preload" as="image" href="{{ asset('storage/background/home/ima1.webp') }}" fetchpriority="high">
    <link rel="preload" as="image" href="{{ asset('storage/background/home/ima2.webp') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">

</head>

<body>
    <div class="bg" aria-hidden="true">
        <img id="bgA" alt="">
        <img id="bgB" alt="">
    </div>

    <header>
        <h2><span id="username">{{ auth('user')->user()->name }}</span></h2>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sub-btn"> تسجيل الخروج</button>
        </form>
    </header>

    <main>
        <div class="circle" id="circle">
            <button class="center-btn" id="diagnoseBtn" onclick="location.href='{{ route('ai.cnn.ui') }}'"
                title="تشخيص مرض">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path
                        d="M6 3a1 1 0 0 1 1 1v5a3 3 0 1 0 6 0V4a1 1 0 1 1 2 0v5a5 5 0 0 1-10 0V4a1 1 0 0 1 1-1zm12 9a3 3 0 0 1 3 3v2a4 4 0 0 1-4 4h-2a1 1 0 1 1 0-2h2a2 2 0 0 0 2-2v-2a1 1 0 1 0-2 0v1a3 3 0 0 1-3 3h-3a1 1 0 1 1 0-2h3a1 1 0 0 0 1-1v-1a3 3 0 0 1 3-3z" />
                </svg>
                تشخيص مرض
            </button>

            <div class="orbits" id="orbits" aria-hidden="true"></div>

            <button class="menu-item shape-circle" data-title="مزارع" title="مزارع"
                onclick="location.href='{{ route('user.farm.ui') }}'">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M3 11l9-7 9 7v8a2 2 0 0 1-2 2h-4v-6H9v6H5a2 2 0 0 1-2-2z" />
                </svg>
                <span>مزارع</span>
            </button>

            <button class="menu-item shape-hex" data-title="مهام" title="مهام"
                onclick="location.href='{{ route('user.task.ui') }}'">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path
                        d="M7 4h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2zm2 4h6v2H9V8zm0 4h6v2H9v-2zm0 4h4v2H9v-2z" />
                </svg>
                <span>مهام</span>
            </button>

            <button class="menu-item shape-circle" data-title="مخازن" title="مخازن"
                onclick="location.href='{{ route('user.inventory.ui') }}'">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M3 11l9-7 9 7v8a2 2 0 0 1-2 2h-4v-6H9v6H5a2 2 0 0 1-2-2z" />
                </svg>
                <span>مخازن</span>
            </button>

            <button class="menu-item shape-squircle" data-title="محاصيل تجارية" title="محاصيل تجارية"
                onclick="location.href='{{ route('user.crop-sale.ui') }}'">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M3 17h3v3H3v-3zm5-6h3v9H8v-9zm5 3h3v6h-3v-6zm5-8h3v14h-3V6z" />
                </svg>
                <span>محاصيل تجارية</span>
            </button>

            <button class="menu-item shape-leaf" data-title="أدوات" title="أدوات"
                onclick="location.href='{{ route('user.equipment.ui') }}'">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path
                        d="M22 7.46l-4.94 4.94-2.12-2.12L19.88 5.34A5 5 0 1 0 13 11.22L3.22 21H2v-1.22L11.78 10A5 5 0 1 0 22 7.46z" />
                </svg>
                <span>أدوات</span>
            </button>

            <button class="menu-item shape-pill" data-title="سجلات مالية" title="سجلات مالية"
                onclick="location.href='{{ route('user.financial-records.ui') }}'">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path
                        d="M6 2h11a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a3 3 0 0 1-3-3V5a3 3 0 0 1 3-3zm1 4v2h8V6H7zm0 4v2h8v-2H7zm0 4v2h6v-2H7z" />
                </svg>
                <span>سجلات مالية</span>
            </button>

            <button class="menu-item shape-hex" data-title="تقرير مالي" title="تقرير مالي"
                onclick="location.href='{{ route('user.report.ui') }}'">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path
                        d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm1 14H7v-2h8v2zm0-4H7v-2h8v2zM13 9V3.5L18.5 9H13z" />
                </svg>
                <span>تقرير مالي</span>
            </button>

            <button class="menu-item shape-squircle" data-title="شاركنا رأيك" title="شاركنا رأيك"
                onclick="location.href='{{ route('user.complaints.ui') }}'">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M20 2H4a2 2 0 0 0-2 2v14l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z" />
                </svg>
                <span>شاركنا رأيك</span>
            </button>
        </div>
    </main>

    <script>
        (function() {
            const bgImages = [
                "{{ asset('storage/background/home/ima1.jpg') }}",
                "{{ asset('storage/background/home/ima2.jpg') }}",
                "{{ asset('storage/background/home/ima3.jpg') }}",
                "{{ asset('storage/background/home/ima4.jpg') }}"
            ];
            const a = document.getElementById('bgA');
            const b = document.getElementById('bgB');
            let i = 0,
                active = a,
                idle = b;

            function setImg(imgEl, src) {
                imgEl.src = src;
            }
            setImg(a, bgImages[0]);
            a.classList.add('active');
            setImg(b, bgImages[1 % bgImages.length]);

            setInterval(() => {
                i = (i + 1) % bgImages.length;
                idle.src = bgImages[i];
                requestAnimationFrame(() => {
                    active.classList.remove('active');
                    idle.classList.add('active');
                    const t = active;
                    active = idle;
                    idle = t;
                });
            }, 8000);
        })();

        (function() {
            const circle = document.getElementById('circle');
            const items = Array.from(circle.querySelectorAll('.menu-item'));
            const orbitsWrap = document.getElementById('orbits');

            function makeOrbitSVG(title) {
                switch (title) {
                    case 'مزارع':
                        return `
          <svg viewBox="0 0 100 100" aria-hidden="true">
            <path class="line" d="M20 78 Q50 70 80 78" />
            <path class="line" d="M50 78 V40" />
            <path class="line" d="M50 52 C40 48 32 40 28 34" />
            <path class="line" d="M50 52 C60 48 68 40 72 34" />
            <circle class="fill" cx="50" cy="30" r="6"/>
          </svg>`;
                    case 'مهام':
                        return `
          <svg viewBox="0 0 100 100" aria-hidden="true">
            <rect class="line" x="24" y="22" width="52" height="64" rx="8" />
            <path class="line" d="M34 38 H66" />
            <path class="line" d="M34 52 H66" />
            <path class="line" d="M34 66 H58" />
            <path class="line" d="M30 29 l6 6 l12 -12" />
          </svg>`;
                    case 'مخازن':
                        return `
          <svg viewBox="0 0 100 100" aria-hidden="true">
            <path class="line" d="M18 44 L50 24 L82 44 V76 A6 6 0 0 1 76 82 H24 A6 6 0 0 1 18 76 Z" />
            <path class="line" d="M30 62 H70" />
            <path class="line" d="M30 72 H70" />
            <rect class="line" x="40" y="50" width="20" height="12" rx="2" />
          </svg>`;
                    case 'محاصيل تجارية':
                        return `
          <svg viewBox="0 0 100 100" aria-hidden="true">
            <path class="line" d="M24 74 H80" />
            <rect class="line" x="28" y="58" width="10" height="16" rx="2"/>
            <rect class="line" x="44" y="48" width="10" height="26" rx="2"/>
            <rect class="line" x="60" y="38" width="10" height="36" rx="2"/>
            <path class="line" d="M28 34 Q36 30 44 34 Q52 38 60 34 Q68 30 76 34" />
          </svg>`;
                    case 'أدوات':
                        return `
          <svg viewBox="0 0 100 100" aria-hidden="true">
            <path class="line" d="M38 30 a12 12 0 0 1 20 10 l18 18 l-8 8 l-18 -18 a12 12 0 0 1 -12 -18 z" />
            <circle class="fill" cx="34" cy="34" r="4"/>
          </svg>`;
                    case 'سجلات مالية':
                        return `
          <svg viewBox="0 0 100 100" aria-hidden="true">
            <rect class="line" x="24" y="18" width="52" height="64" rx="8" />
            <path class="line" d="M34 32 H66" />
            <path class="line" d="M34 44 H66" />
            <path class="line" d="M34 56 H58" />
            <path class="line" d="M50 60 q10 0 10 8 q0 8 -10 8 q-10 0 -10 -8" />
          </svg>`;
                    case 'تقرير مالي':
                        return `
          <svg viewBox="0 0 100 100" aria-hidden="true">
            <path class="line" d="M36 18 H62 L78 34 V82 A6 6 0 0 1 72 88 H36 A6 6 0 0 1 30 82 V24 A6 6 0 0 1 36 18 Z" />
            <path class="line" d="M62 18 V34 H78" />
            <path class="line" d="M38 60 L48 48 L58 56 L70 42" />
            <path class="line" d="M38 68 H70" />
          </svg>`;
                    case 'شاركنا رأيك':
                        return `
          <svg viewBox="0 0 100 100" aria-hidden="true">
            <path class="line" d="M18 28 A10 10 0 0 1 28 18 H72 A10 10 0 0 1 82 28 V56 A10 10 0 0 1 72 66 H54 L40 80 V66 H28 A10 10 0 0 1 18 56 Z" />
            <circle class="fill" cx="36" cy="42" r="3.5"/>
            <circle class="fill" cx="50" cy="42" r="3.5"/>
            <circle class="fill" cx="64" cy="42" r="3.5"/>
          </svg>`;
                    default:
                        return `
          <svg viewBox="0 0 100 100" aria-hidden="true">
            <path class="line" d="M50 20 L56 40 L78 40 L60 52 L66 74 L50 60 L34 74 L40 52 L22 40 L44 40 Z" />
          </svg>`;
                }
            }

            function ensureOrbits() {
                orbitsWrap.innerHTML = '';
                items.forEach((btn, idx) => {
                    const title = btn.getAttribute('data-title') || btn.title || `item-${idx+1}`;
                    const el = document.createElement('div');
                    el.className = 'orbit';
                    el.dataset.index = idx;
                    el.dataset.title = title;
                    el.innerHTML = makeOrbitSVG(title);
                    orbitsWrap.appendChild(el);
                });
            }

            function layout() {
                const pad = 120;
                const cw = circle.clientWidth;
                const ch = circle.clientHeight;
                const r = Math.min(cw, ch) / 2 - pad;
                const center = {
                    x: cw / 2,
                    y: ch / 2
                };
                const n = items.length;

                if (!orbitsWrap.children.length) ensureOrbits();

                const orbits = Array.from(orbitsWrap.children);
                const orbitSize = parseFloat(getComputedStyle(document.documentElement).getPropertyValue(
                    '--orbit-size')) || 90;
                const orbitGap = parseFloat(getComputedStyle(document.documentElement).getPropertyValue(
                    '--orbit-gap')) || 14;

                items.forEach((el, i) => {
                    const angle = (360 / n) * i - 90;
                    let rad = angle * Math.PI / 180;

                    const x = center.x + r * Math.cos(rad);
                    const y = center.y + r * Math.sin(rad);
                    el.style.left = (x - el.offsetWidth / 2) + 'px';
                    el.style.top = (y - el.offsetHeight / 2) + 'px';

                    const btnW = el.offsetWidth;
                    const btnH = el.offsetHeight;
                    const btnRadius = Math.max(btnW, btnH) / 2;
                    const orbitRadiusFromCenter = r + btnRadius + orbitGap + (orbitSize / 2);
                    const orb = orbits[i];
                    const ox = center.x + orbitRadiusFromCenter * Math.cos(rad);
                    const oy = center.y + orbitRadiusFromCenter * Math.sin(rad);
                    orb.style.left = ox + 'px';
                    orb.style.top = oy + 'px';

                    el.onmouseenter = () => {
                        orb.classList.add('show');
                    };
                    el.onmouseleave = () => {
                        orb.classList.remove('show');
                    };
                    el.onfocus = () => {
                        orb.classList.add('show');
                    };
                    el.onblur = () => {
                        orb.classList.remove('show');
                    };
                });
            }

            window.addEventListener('resize', layout);
            setTimeout(layout, 0);
            window.addEventListener('load', layout);
        })();
    </script>
    <div class="bottom-glow" aria-hidden="true"></div>

</body>

</html>
