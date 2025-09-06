<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>مخازني — إدارة المخزون</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preload" as="image" href="{{ asset('/background/inventory/ima1.webp') }}" fetchpriority="high">
    <link rel="preload" as="image" href="{{ asset('/background/inventory/ima2.webp') }}">
    <style>
        :root {
            --bg: #f3faf7;
            --card: #ffffff;
            --text: #0e1a14;
            --muted: #6d7a74;
            --accent: #2fb46e;
            --accent-2: #0f8a54;
            --stroke: #e6efe9;
            --shadow: 0 10px 30px rgba(16, 24, 20, .10), 0 2px 10px rgba(16, 24, 20, .05);
            --radius: 16px;
            --transition: .25s ease;

            --card-white: #fffffffa;
            --card-green: #b2efc9;
            --card-green-stroke: #a0e6bb;
        }

        * {
            box-sizing: border-box;
            font-family: "Segoe UI", Tahoma, Arial, system-ui
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text)
        }

        .bg-slideshow {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none
        }

        .bg-slideshow img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transform: scale(1);
            transition: opacity 1.1s ease, transform 12s ease
        }

        .bg-slideshow img.active {
            opacity: 1;
            transform: scale(1.04)
        }

        .bg-slideshow::after {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, .35);
            backdrop-filter: saturate(115%) blur(1px)
        }

        .wrap {
            position: relative;
            z-index: 5;
            max-width: 1200px;
            margin: 28px auto;
            padding: 0 14px
        }

        header {
            background: #ffffffcc;
            backdrop-filter: blur(8px);
            border: 1px solid var(--stroke);
            border-radius: calc(var(--radius) + 4px);
            padding: 18px 22px;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        h1 {
            margin: 0;
            font-size: 22px;
            background: linear-gradient(135deg, var(--accent-2), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text
        }

        .btn {
            all: unset;
            cursor: pointer;
            padding: 12px 16px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--accent), #43d184);
            color: #fff;
            font-weight: 800;
            box-shadow: var(--shadow);
            transition: transform var(--transition), box-shadow var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn:hover {
            transform: translateY(-2px)
        }

        .btn:disabled {
            opacity: .55;
            cursor: not-allowed
        }

        .btn-ghost {
            background: #f5fff9;
            color: var(--accent-2);
            border: 1px solid var(--stroke)
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin: 16px 0
        }

        .search {
            display: flex;
            gap: 8px;
            align-items: center
        }

        .input {
            background: #fff;
            border: 1px solid var(--stroke);
            border-radius: 999px;
            padding: 10px 14px;
            min-width: 230px
        }

        .grid {
            display: grid;
            gap: 16px
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 16px;
        }

        .cards .card {
            --zig: 0px;
            --card-bg: var(--card-white);
            --card-st: var(--stroke);

            background: var(--card-bg);
            border: 1px solid var(--card-st);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            transition: transform var(--transition), box-shadow var(--transition);
            cursor: pointer;
            transform: translateY(var(--zig));
        }

        .cards .card:nth-child(odd) {
            --zig: -8px;
            --card-bg: var(--card-white);
            --card-st: var(--stroke);
        }

        .cards .card:nth-child(even) {
            --zig: 8px;
            --card-bg: var(--card-green);
            --card-st: var(--card-green-stroke);
        }

        .cards .card:hover {
            transform: translateY(calc(var(--zig) - 2px));
            box-shadow: 0 12px 36px rgba(0, 0, 0, .12);
        }

        .title {
            font-weight: 900;
            font-size: 16px
        }

        .meta {
            color: var(--muted);
            font-size: 13px
        }

        .row {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap
        }

        .actions {
            display: flex;
            gap: 8px;
            margin-top: auto
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            background: #f1fbf6;
            border: 1px solid var(--stroke);
            font-size: 12px
        }

        .status {
            margin-top: 12px;
            font-size: 14px
        }

        .ok {
            color: #0d7d4b;
            background: #e8f7ef;
            padding: 8px 12px;
            border-radius: 10px
        }

        .err {
            color: #b00020;
            background: #ffebee;
            padding: 8px 12px;
            border-radius: 10px
        }

        .modal {
            position: fixed;
            inset: 0;
            z-index: 20;
            display: none;
            align-items: center;
            justify-content: center
        }

        .modal.on {
            display: flex
        }

        .overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .25);
            backdrop-filter: blur(2px)
        }

        .sheet {
            position: relative;
            z-index: 1;
            width: min(92vw, 720px);
            background: #ffffff;
            border: 1px solid var(--stroke);
            border-radius: 22px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .25);
            padding: 18px
        }

        .sheet h3 {
            margin: 0 0 8px 0
        }

        .sheet .close {
            position: absolute;
            top: 10px;
            left: 10px
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 6px
        }

        .field input,
        .field select,
        .field textarea {
            padding: 10px 12px;
            border: 1px solid var(--stroke);
            border-radius: 12px;
            background: #fff
        }

        .sheet .footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 12px
        }

        .detail-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px
        }

        .detail {
            background: #fcfffd;
            border: 1px dashed var(--stroke);
            border-radius: 12px;
            padding: 10px
        }

        .detail b {
            font-size: 13px
        }

        .detail div {
            color: var(--muted);
            font-size: 13px
        }

        @media (prefers-reduced-motion:reduce) {
            .bg-slideshow img {
                transition: none;
                transform: none
            }

            .btn,
            .card {
                transition: none
            }
        }

        .btn-nav {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding-inline: 12px 14px;
        }

        .btn-nav .icon {
            width: 18px;
            height: 18px;
            display: inline-block;
            line-height: 0;
        }

        .btn-nav .label {
            font-weight: 800;
        }

        @media (max-width: 560px) {
            .btn-nav .label {
                display: none;
            }

            .btn-nav {
                padding-inline: 10px;
            }
        }

        .btn-nav:hover {
            transform: translateY(-2px);
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
            <a class="btn btn-ghost btn-nav" href="{{ route('home') ?? '/' }}" aria-label="العودة للواجهة الرئيسية"
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
            <h1>مخازني</h1>
            <div class="row">
                <button class="btn" id="btnOpenCreate">➕ إضافة عنصر مخزون</button>
            </div>
        </header>

        <div class="toolbar">
            <div class="search">
                <input class="input" id="qGlobal" type="search"
                    placeholder="بحث عام (اسم، نوع، مورد، موقع التخزين…)">
                <button class="btn btn-ghost" id="btnSearch" type="button">بحث</button>
                <button class="btn btn-ghost" id="btnToggleFilters" type="button">فلترة متقدمة</button>
                <button class="btn btn-ghost" id="btnReset" type="button">عرض الكل</button>
            </div>
            <span class="badge" id="countBadge">—</span>
        </div>

        <section class="card" id="filtersPanel" style="display:none; margin-top:-8px">
            <div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(220px,1fr)); gap:12px">
                <div class="field">
                    <label>الاسم</label>
                    <input class="input" id="f_name" type="text" placeholder="مثال: بذور قمح">
                </div>
                <div class="field">
                    <label>النوع</label>
                    <input class="input" id="f_type" type="text" placeholder="أسمدة، بذور…">
                </div>
                <div class="field">
                    <label>المورّد</label>
                    <input class="input" id="f_supplier" type="text" placeholder="اسم المورّد">
                </div>
                <div class="field">
                    <label>موقع التخزين</label>
                    <input class="input" id="f_storage_location" type="text" placeholder="المخزن A / مستودع…">
                </div>
                <div class="field">
                    <label>الوحدة</label>
                    <input class="input" id="f_unit" type="text" placeholder="كغ، لتر…">
                </div>
                <div class="field">
                    <label>الكمية من</label>
                    <input class="input" id="f_qty_min" type="number" step="0.01" min="0">
                </div>
                <div class="field">
                    <label>الكمية إلى</label>
                    <input class="input" id="f_qty_max" type="number" step="0.01" min="0">
                </div>
                <div class="field">
                    <label>شراء من</label>
                    <input class="input" id="f_purchase_from" type="date">
                </div>
                <div class="field">
                    <label>شراء إلى</label>
                    <input class="input" id="f_purchase_to" type="date">
                </div>
                <div class="field">
                    <label>انتهاء من</label>
                    <input class="input" id="f_expiry_from" type="date">
                </div>
                <div class="field">
                    <label>انتهاء إلى</label>
                    <input class="input" id="f_expiry_to" type="date">
                </div>
                <div class="field">
                    <label>ترتيب حسب</label>
                    <select id="f_sort_by">
                        <option value="created_at">تاريخ الإنشاء</option>
                        <option value="name">الاسم</option>
                        <option value="quantity">الكمية</option>
                        <option value="purchase_date">تاريخ الشراء</option>
                        <option value="expiry_date">تاريخ الانتهاء</option>
                        <option value="updated_at">آخر تعديل</option>
                    </select>
                </div>
                <div class="field">
                    <label>الاتجاه</label>
                    <select id="f_sort_dir">
                        <option value="desc">تنازلي</option>
                        <option value="asc">تصاعدي</option>
                    </select>
                </div>
                <div class="field">
                    <label>Limit (اختياري)</label>
                    <input class="input" id="f_limit" type="number" min="1" placeholder="مثال: 50">
                </div>
            </div>
            <div class="row" style="justify-content:flex-end; margin-top:12px">
                <button class="btn btn-ghost" id="btnFiltersReset" type="button">مسح الحقول</button>
                <button class="btn" id="btnFiltersApply" type="button">تطبيق الفلترة</button>
            </div>
        </section>

        <div class="grid">
            <div class="cards" id="cards"></div>
            <div class="status" id="status"></div>
        </div>
    </div>

    <div class="modal" id="modalDetails" role="dialog" aria-modal="true">
        <div class="overlay"></div>
        <div class="sheet">
            <button class="btn btn-ghost close" data-close="modalDetails">✕</button>
            <h3>تفاصيل المخزون</h3>
            <div class="detail-row" id="detailsBody"></div>
            <div class="footer">
                <button class="btn btn-ghost" data-close="modalDetails">إغلاق</button>
            </div>
        </div>
    </div>

    <div class="modal" id="modalForm" role="dialog" aria-modal="true">
        <div class="overlay"></div>
        <div class="sheet">
            <button class="btn btn-ghost close" data-close="modalForm">✕</button>
            <h3 id="formTitle">إضافة عنصر</h3>

            <form id="invForm">
                <input type="hidden" id="invId">
                <div class="form-grid">
                    <div class="field">
                        <label>اسم العنصر</label>
                        <input type="text" id="name" required>
                    </div>
                    <div class="field">
                        <label>النوع</label>
                        <input type="text" id="type">
                    </div>
                    <div class="field">
                        <label>الكمية</label>
                        <input type="number" id="quantity" step="0.01" min="0">
                    </div>
                    <div class="field">
                        <label>الوحدة</label>
                        <input type="text" id="unit" placeholder="كغ، لتر…">
                    </div>
                    <div class="field">
                        <label>تاريخ الشراء</label>
                        <input type="date" id="purchase_date">
                    </div>
                    <div class="field">
                        <label>تاريخ الانتهاء</label>
                        <input type="date" id="expiry_date">
                    </div>
                    <div class="field">
                        <label>حد التنبيه الأدنى</label>
                        <input type="number" id="min_threshold" step="0.01" min="0" value="0">
                    </div>
                    <div class="field">
                        <label>المورّد</label>
                        <input type="text" id="supplier">
                    </div>
                    <div class="field">
                        <label>موقع التخزين</label>
                        <input type="text" id="storage_location">
                    </div>
                    <div class="field" style="grid-column:1/-1">
                        <label>ملاحظات</label>
                        <textarea id="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="footer">
                    <button type="button" class="btn btn-ghost" data-close="modalForm">إلغاء</button>
                    <button type="submit" class="btn" id="btnSave">حفظ</button>
                </div>
                <div class="status" id="formStatus"></div>
            </form>
        </div>
    </div>

    <script>
        (function() {
            const token = document.querySelector('meta[name="csrf-token"]').content;

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
                    throw new Error(`Non-JSON response (status ${resp.status}). ربما HTML أو تحويل.\n${snippet}…`);
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

            function debounce(fn, d = 350) {
                let t;
                return (...a) => {
                    clearTimeout(t);
                    t = setTimeout(() => fn(...a), d);
                }
            }

            (function() {
                const imgs = [
                    "{{ asset('/background/inventory/ima1.jpg') }}",
                    "{{ asset('/background/inventory/ima2.jpg') }}",
                    "{{ asset('/background/inventory/ima3.jpg') }}",
                    "{{ asset('/background/inventory/ima4.jpg') }}"
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

            const cards = document.getElementById('cards');
            const statusEl = document.getElementById('status');
            const countBadge = document.getElementById('countBadge');

            const modalDetails = document.getElementById('modalDetails');
            const detailsBody = document.getElementById('detailsBody');

            const modalForm = document.getElementById('modalForm');
            const form = document.getElementById('invForm');
            const formTitle = document.getElementById('formTitle');
            const formStatus = document.getElementById('formStatus');

            const btnOpenCreate = document.getElementById('btnOpenCreate');
            const btnSave = document.getElementById('btnSave');

            const qGlobal = document.getElementById('qGlobal');
            const btnSearch = document.getElementById('btnSearch');
            const btnReset = document.getElementById('btnReset');

            const filtersPanel = document.getElementById('filtersPanel');
            const btnToggleFilters = document.getElementById('btnToggleFilters');
            const btnFiltersApply = document.getElementById('btnFiltersApply');
            const btnFiltersReset = document.getElementById('btnFiltersReset');

            const f = {
                id: document.getElementById('invId'),
                name: document.getElementById('name'),
                type: document.getElementById('type'),
                quantity: document.getElementById('quantity'),
                unit: document.getElementById('unit'),
                purchase_date: document.getElementById('purchase_date'),
                expiry_date: document.getElementById('expiry_date'),
                min_threshold: document.getElementById('min_threshold'),
                supplier: document.getElementById('supplier'),
                storage_location: document.getElementById('storage_location'),
                notes: document.getElementById('notes'),
            };

            const flt = {
                name: document.getElementById('f_name'),
                type: document.getElementById('f_type'),
                supplier: document.getElementById('f_supplier'),
                storage_location: document.getElementById('f_storage_location'),
                unit: document.getElementById('f_unit'),
                qty_min: document.getElementById('f_qty_min'),
                qty_max: document.getElementById('f_qty_max'),
                purchase_from: document.getElementById('f_purchase_from'),
                purchase_to: document.getElementById('f_purchase_to'),
                expiry_from: document.getElementById('f_expiry_from'),
                expiry_to: document.getElementById('f_expiry_to'),
                sort_by: document.getElementById('f_sort_by'),
                sort_dir: document.getElementById('f_sort_dir'),
                limit: document.getElementById('f_limit'),
            };

            function setStatus(msg, ok = false, target = statusEl) {
                target.textContent = msg || '';
                target.className = 'status ' + (msg ? (ok ? 'ok' : 'err') : '');
            }

            function openModal(el) {
                el.classList.add('on');
            }

            function closeModal(el) {
                el.classList.remove('on');
                setStatus('', false, formStatus);
            }
            document.querySelectorAll('[data-close]').forEach(btn => {
                btn.addEventListener('click', () => closeModal(document.getElementById(btn.dataset.close)));
            });
            document.querySelectorAll('.modal .overlay').forEach(ov => {
                ov.addEventListener('click', (e) => closeModal(e.target.closest('.modal')));
            });

            function updateSummary(count) {
                const q = (qGlobal?.value || '').trim();
                countBadge.textContent = `عدد العناصر: ${count}`;
                if (q) setStatus(count ? `تم العثور على ${count} نتيجة لعبارة "${q}"` : `لا نتائج لعبارة "${q}"`, !!
                    count);
                else setStatus(count ? '' : 'لا توجد عناصر بعد', !!count);
            }

            function renderItems(items) {
                cards.innerHTML = '';
                items.forEach(item => {
                    const c = document.createElement('div');
                    c.className = 'card';
                    c.dataset.id = item.id;
                    c.innerHTML = `
        <div class="title">📦 ${item.name ?? '—'}</div>
        <div class="meta">🧩 ${item.type ?? '—'} • 🧪 ${item.unit ?? '—'}</div>
        <div class="row">
          <span class="badge">🔢 الكمية: ${item.quantity ?? '—'} ${item.unit ?? ''}</span>
          <span class="badge">🚦 حد أدنى: ${item.min_threshold ?? 0}</span>
          <span class="badge">👤 المورّد: ${item.supplier ?? '—'}</span>
          <span class="badge">📍 التخزين: ${item.storage_location ?? '—'}</span>
        </div>
        <div class="row">
          <span class="badge">🛒 الشراء: ${item.purchase_date ?? '—'}</span>
          <span class="badge">⏳ الانتهاء: ${item.expiry_date ?? '—'}</span>
        </div>
        <div class="actions">
          <button class="btn btn-ghost btn-edit" data-id="${item.id}">✏️ تعديل</button>
          <button class="btn btn-ghost btn-del" data-id="${item.id}">🗑️ حذف</button>
        </div>
      `;
                    c.addEventListener('click', (e) => {
                        if (e.target.closest('.btn-edit') || e.target.closest('.btn-del')) return;
                        openDetails(item);
                    });
                    c.querySelector('.btn-edit').addEventListener('click', () => openEdit(item));
                    c.querySelector('.btn-del').addEventListener('click', () => doDelete(item.id, item.name));
                    cards.appendChild(c);
                });
                updateSummary(items.length);
            }

            function openDetails(item) {
                detailsBody.innerHTML = '';
                const fields = [
                    ['الاسم', item.name],
                    ['النوع', item.type],
                    ['الكمية', item.quantity],
                    ['الوحدة', item.unit],
                    ['حد التنبيه الأدنى', item.min_threshold],
                    ['المورّد', item.supplier],
                    ['موقع التخزين', item.storage_location],
                    ['تاريخ الشراء', item.purchase_date],
                    ['تاريخ الانتهاء', item.expiry_date],
                    ['ملاحظات', item.notes],

                ];
                fields.forEach(([k, v]) => {
                    const d = document.createElement('div');
                    d.className = 'detail';
                    d.innerHTML = `<b>${k}</b><div>${(v ?? '—')}</div>`;
                    detailsBody.appendChild(d);
                });
                openModal(modalDetails);
            }

            /* إنشاء/تعديل */
            btnOpenCreate.addEventListener('click', () => openCreate());

            function openCreate() {
                form.reset();
                f.id.value = '';
                f.min_threshold.value = 0;
                formTitle.textContent = 'إضافة عنصر';
                btnSave.textContent = 'حفظ';
                setStatus('', false, formStatus);
                openModal(modalForm);
            }

            function openEdit(item) {
                f.id.value = item.id;
                f.name.value = item.name ?? '';
                f.type.value = item.type ?? '';
                f.quantity.value = item.quantity ?? '';
                f.unit.value = item.unit ?? '';
                f.purchase_date.value = item.purchase_date ?? '';
                f.expiry_date.value = item.expiry_date ?? '';
                f.min_threshold.value = item.min_threshold ?? 0;
                f.supplier.value = item.supplier ?? '';
                f.storage_location.value = item.storage_location ?? '';
                f.notes.value = item.notes ?? '';
                formTitle.textContent = 'تعديل عنصر';
                btnSave.textContent = 'تحديث';
                setStatus('', false, formStatus);
                openModal(modalForm);
            }
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const id = f.id.value ? Number(f.id.value) : null;
                const payload = {
                    name: f.name.value?.trim() || null,
                    type: f.type.value?.trim() || null,
                    quantity: f.quantity.value !== '' ? Number(f.quantity.value) : null,
                    unit: f.unit.value?.trim() || null,
                    purchase_date: f.purchase_date.value || null,
                    expiry_date: f.expiry_date.value || null,
                    min_threshold: f.min_threshold.value !== '' ? Number(f.min_threshold.value) : 0,
                    supplier: f.supplier.value?.trim() || null,
                    storage_location: f.storage_location.value?.trim() || null,
                    notes: f.notes.value?.trim() || null,
                };
                try {
                    btnSave.disabled = true;
                    let url, method;
                    if (id) {
                        url = `/user/inventory/update-inventory/${id}`;
                        method = 'PUT';
                    } else {
                        url = `/user/inventory/add-inventory`;
                        method = 'POST';
                    }
                    await fetchJSON(url, {
                        method,
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });
                    setStatus(id ? 'تم تحديث المخزون بنجاح' : 'تمت إضافة المخزون بنجاح', true, formStatus);
                    await doFilter();
                    setTimeout(() => closeModal(modalForm), 400);
                } catch (err) {
                    console.error(err);
                    const errs = err?.data?.errors ? Object.values(err.data.errors).flat().join(' | ') : '';
                    setStatus((errs || err.message), false, formStatus);
                } finally {
                    btnSave.disabled = false;
                }
            });

            async function doDelete(id, name) {
                const ok = confirm(`هل أنت متأكد من حذف العنصر "${name || '#'+id}"؟`);
                if (!ok) return;
                try {
                    setStatus('جاري الحذف…');
                    await fetchJSON(`/user/inventory/delete-inventory/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': token
                        }
                    });
                    setStatus('تم حذف العنصر بنجاح', true);
                    await doFilter();
                } catch (err) {
                    console.error(err);
                    setStatus('خطأ: ' + err.message);
                }
            }

            function buildFilterParams() {
                const p = new URLSearchParams();
                if (qGlobal.value.trim()) p.set('q', qGlobal.value.trim());

                if (flt.name.value.trim()) p.set('name', flt.name.value.trim());
                if (flt.type.value.trim()) p.set('type', flt.type.value.trim());
                if (flt.supplier.value.trim()) p.set('supplier', flt.supplier.value.trim());
                if (flt.storage_location.value.trim()) p.set('storage_location', flt.storage_location.value.trim());
                if (flt.unit.value.trim()) p.set('unit', flt.unit.value.trim());

                if (flt.qty_min.value !== '') p.set('min_quantity', flt.qty_min.value);
                if (flt.qty_max.value !== '') p.set('max_quantity', flt.qty_max.value);

                if (flt.purchase_from.value) p.set('purchase_from', flt.purchase_from.value);
                if (flt.purchase_to.value) p.set('purchase_to', flt.purchase_to.value);
                if (flt.expiry_from.value) p.set('expiry_from', flt.expiry_from.value);
                if (flt.expiry_to.value) p.set('expiry_to', flt.expiry_to.value);

                if (flt.sort_by.value) p.set('sort_by', flt.sort_by.value);
                if (flt.sort_dir.value) p.set('sort_dir', flt.sort_dir.value);
                if (flt.limit.value) p.set('limit', flt.limit.value);
                return p;
            }
            async function doFilter() {
                try {
                    const params = buildFilterParams();
                    const url = `/user/inventory/filter-inventory` + (params.toString() ? `?${params}` : '');
                    const data = await fetchJSON(url);
                    renderItems(data.data || []);
                } catch (e) {
                    console.error(e);
                    setStatus('خطأ في الفلترة: ' + e.message);
                    renderItems([]);
                }
            }
            const debouncedFilter = debounce(doFilter, 350);

            qGlobal.addEventListener('input', debouncedFilter);
            qGlobal.addEventListener('keydown', e => {
                if (e.key === 'Enter') e.preventDefault();
            });

            [flt.name, flt.type, flt.supplier, flt.storage_location, flt.unit,
                flt.qty_min, flt.qty_max, flt.purchase_from, flt.purchase_to,
                flt.expiry_from, flt.expiry_to, flt.limit
            ].forEach(el => el.addEventListener('input', debouncedFilter));

            [flt.sort_by, flt.sort_dir].forEach(el => el.addEventListener('change', debouncedFilter));

            btnFiltersApply.addEventListener('click', doFilter);
            btnSearch.addEventListener('click', doFilter);
            btnToggleFilters.addEventListener('click', () => {
                const st = (filtersPanel.style.display || 'none');
                filtersPanel.style.display = (st === 'none') ? 'block' : 'none';
            });
            btnFiltersReset.addEventListener('click', () => {
                Object.values(flt).forEach(el => {
                    if (el.tagName === 'SELECT' || el.type === 'date' || el.type === 'number' || el
                        .type === 'text') el.value = '';
                });
                flt.sort_by.value = 'created_at';
                flt.sort_dir.value = 'desc';
                debouncedFilter();
            });
            btnReset.addEventListener('click', () => {
                qGlobal.value = '';
                btnFiltersReset.click();
                filtersPanel.style.display = 'none';
                doFilter();
            });
            doFilter();
        })();
    </script>

</body>

</html>
