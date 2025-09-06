<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>مبيعات المحاصيل — إدارة المبيعات</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="preload" as="image" href="{{ asset('storage/background/crop_sale/ima1.webp') }}" fetchpriority="high">
    <link rel="preload" as="image" href="{{ asset('storage/background/crop_sale/ima2.webp') }}">
    <link rel="stylesheet" href="{{ asset('css/crop_sale.css') }}">

</head>

<body>

    <div class="bg-slideshow" aria-hidden="true">
        <img id="bgA" alt="" decoding="async" loading="eager">
        <img id="bgB" alt="" decoding="async" loading="lazy">
    </div>

    <div class="wrap">
        <header>
            <a class="btn btn-ghost btn-nav" href="{{ route('home') ?? '/' }}" aria-label="العودة للواجهة الرئيسية" title="الواجهة الرئيسية">
            <span class="icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 11l9-8 9 8"></path>
                    <path d="M9 22V12h6v10"></path>
                </svg>
            </span>
            <span class="label">الرئيسية</span>
        </a>
            <h1>مبيعات المحاصيل</h1>
            <div class="row">
                <button class="btn" id="btnOpenCreate">➕ إضافة عملية بيع</button>
            </div>
        </header>

        <div class="toolbar">
            <div class="search">
                <input class="input" id="qGlobal" type="search"
                    placeholder="بحث عام (اسم المحصول، المشتري، موقع التسليم…)">
                <button class="btn btn-ghost" id="btnSearch" type="button">بحث</button>
                <button class="btn btn-ghost" id="btnToggleFilters" type="button">فلترة متقدمة</button>
                <button class="btn btn-ghost" id="btnReset" type="button">عرض الكل</button>
            </div>
            <span class="badge" id="countBadge">—</span>
        </div>

        <section class="card" id="filtersPanel" style="display:none; margin-top:-8px">
            <div class="form-grid">
                <div class="field">
                    <label>المزرعة</label>
                    <select id="f_farm_id">
                        <option value="">—</option>
                    </select>
                </div>
                <div class="field">
                    <label>اسم المحصول</label>
                    <input class="input" id="f_crop_name" type="text" placeholder="قمح، بطاطا…">
                </div>
                <div class="field">
                    <label>الوحدة</label>
                    <input class="input" id="f_unit" type="text" placeholder="كغ، طن…">
                </div>
                <div class="field">
                    <label>الحالة</label>
                    <input class="input" id="f_status" type="text" placeholder="نص مطابق للحالة">
                </div>
                <div class="field">
                    <label>اسم المشتري</label>
                    <input class="input" id="f_buyer_name" type="text">
                </div>
                <div class="field">
                    <label>موقع التسليم</label>
                    <input class="input" id="f_delivery_location" type="text">
                </div>

                <div class="field">
                    <label>تاريخ البيع من</label>
                    <input class="input" id="f_sale_from" type="date">
                </div>
                <div class="field">
                    <label>تاريخ البيع إلى</label>
                    <input class="input" id="f_sale_to" type="date">
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
                    <label>سعر/وحدة من</label>
                    <input class="input" id="f_ppu_min" type="number" step="0.01" min="0">
                </div>
                <div class="field">
                    <label>سعر/وحدة إلى</label>
                    <input class="input" id="f_ppu_max" type="number" step="0.01" min="0">
                </div>

                <div class="field">
                    <label>الإجمالي من</label>
                    <input class="input" id="f_total_min" type="number" step="0.01" min="0">
                </div>
                <div class="field">
                    <label>الإجمالي إلى</label>
                    <input class="input" id="f_total_max" type="number" step="0.01" min="0">
                </div>

                <div class="field">
                    <label>ترتيب حسب</label>
                    <select id="f_sort_by">
                        <option value="sale_date">تاريخ البيع</option>
                        <option value="crop_name">اسم المحصول</option>
                        <option value="quantity">الكمية</option>
                        <option value="price_per_unit">سعر/وحدة</option>
                        <option value="total_price">الإجمالي</option>
                        <option value="status">الحالة</option>
                        <option value="id">ID</option>
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
                    <label>لكل صفحة</label>
                    <input class="input" id="f_per_page" type="number" min="1" value="5">
                </div>
            </div>
            <div class="row" style="justify-content:flex-end; margin-top:12px">
                <button class="btn btn-ghost" id="btnFiltersReset" type="button">مسح الحقول</button>
                <button class="btn" id="btnFiltersApply" type="button">تطبيق الفلترة</button>
            </div>
        </section>

        <div class="zigwrap" id="cards"></div>
        <div class="pager" id="pager" style="display:none"></div>
        <div class="status" id="status"></div>
    </div>

    <div class="modal" id="modalDetails" role="dialog" aria-modal="true">
        <div class="overlay"></div>
        <div class="sheet">
            <button class="btn btn-ghost close" data-close="modalDetails">✕</button>
            <h3>تفاصيل عملية البيع</h3>
            <div class="form-grid" id="detailsBody"></div>
            <div class="footer">
                <button class="btn btn-ghost" data-close="modalDetails">إغلاق</button>
            </div>
        </div>
    </div>

    <div class="modal" id="modalForm" role="dialog" aria-modal="true">
        <div class="overlay"></div>
        <div class="sheet">
            <button class="btn btn-ghost close" data-close="modalForm">✕</button>
            <h3 id="formTitle">إضافة عملية بيع</h3>

            <form id="saleForm">
                <input type="hidden" id="saleId">

                <div class="form-grid">
                    <div class="field">
                        <label>المزرعة</label>
                        <select id="farm_id" required>
                            <option value="">—</option>
                        </select>
                    </div>

                    <div class="field">
                        <label>اسم المحصول</label>
                        <input type="text" id="crop_name" required>
                    </div>

                    <div class="field">
                        <label>الكمية</label>
                        <input type="number" id="quantity" step="0.01" min="0" required>
                    </div>

                    <div class="field">
                        <label>الوحدة</label>
                        <select id="unit" required>
                            <option value="كغ" selected>كغ</option>
                            <option value="طن">طن</option>
                            <option value="صندوق">صندوق</option>
                            <option value="ربطة">ربطة</option>
                            <option value="علبة">علبة</option>
                        </select>
                    </div>

                    <div class="field">
                        <label>سعر/وحدة</label>
                        <input type="number" id="price_per_unit" step="0.01" min="0" required>
                    </div>

                    <div class="field">
                        <label>السعر الإجمالي</label>
                        <input type="number" id="total_price" step="0.01" min="0"
                            placeholder="سيُحسب تلقائيًا إذا تُرك فارغًا">
                    </div>

                    <div class="field">
                        <label>الحالة</label>
                        <select id="status" required>
                            <option value="قيد البيع" selected>قيد البيع</option>
                            <option value="تم البيع">تم البيع</option>
                            <option value="محجوز">محجوز</option>
                        </select>
                    </div>

                    <div class="field">
                        <label>اسم المشتري</label>
                        <input type="text" id="buyer_name">
                    </div>

                    <div class="field">
                        <label>موقع التسليم</label>
                        <input type="text" id="delivery_location">
                    </div>

                    <div class="field">
                        <label>تاريخ البيع</label>
                        <input type="date" id="sale_date" required>
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
        ;
        (async function() {
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
                    throw new Error(`Non-JSON response (status ${resp.status}).\n${snippet}…`);
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
                };
            }

            (function() {
                const imgs = [
                    "{{ asset('storage/background/crop_sale/ima1.jpg') }}",
                    "{{ asset('storage/background/crop_sale/ima2.jpg') }}",
                    "{{ asset('storage/background/crop_sale/ima3.jpg') }}",
                    "{{ asset('storage/background/crop_sale/ima4.jpg') }}",
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
            const pager = document.getElementById('pager');
            const statusEl = document.getElementById('status');
            const countBadge = document.getElementById('countBadge');

            const filtersPanel = document.getElementById('filtersPanel');
            const btnToggleFilters = document.getElementById('btnToggleFilters');
            const btnFiltersApply = document.getElementById('btnFiltersApply');
            const btnFiltersReset = document.getElementById('btnFiltersReset');

            const qGlobal = document.getElementById('qGlobal');
            const btnSearch = document.getElementById('btnSearch');
            const btnReset = document.getElementById('btnReset');

            const detailsBody = document.getElementById('detailsBody');
            const modalDetails = document.getElementById('modalDetails');

            const modalForm = document.getElementById('modalForm');
            const form = document.getElementById('saleForm');
            const formTitle = document.getElementById('formTitle');
            const formStatus = document.getElementById('formStatus');
            const btnOpenCreate = document.getElementById('btnOpenCreate');
            const btnSave = document.getElementById('btnSave');

            const f = {
                id: document.getElementById('saleId'),
                farm_id: document.getElementById('farm_id'),
                crop_name: document.getElementById('crop_name'),
                quantity: document.getElementById('quantity'),
                unit: document.getElementById('unit'),
                price_per_unit: document.getElementById('price_per_unit'),
                total_price: document.getElementById('total_price'),
                status: document.getElementById('status'),
                buyer_name: document.getElementById('buyer_name'),
                delivery_location: document.getElementById('delivery_location'),
                sale_date: document.getElementById('sale_date'),
                notes: document.getElementById('notes'),
            };

            const flt = {
                farm_id: document.getElementById('f_farm_id'),
                crop_name: document.getElementById('f_crop_name'),
                unit: document.getElementById('f_unit'),
                status: document.getElementById('f_status'),
                buyer_name: document.getElementById('f_buyer_name'),
                delivery_location: document.getElementById('f_delivery_location'),
                sale_from: document.getElementById('f_sale_from'),
                sale_to: document.getElementById('f_sale_to'),
                qty_min: document.getElementById('f_qty_min'),
                qty_max: document.getElementById('f_qty_max'),
                ppu_min: document.getElementById('f_ppu_min'),
                ppu_max: document.getElementById('f_ppu_max'),
                total_min: document.getElementById('f_total_min'),
                total_max: document.getElementById('f_total_max'),
                sort_by: document.getElementById('f_sort_by'),
                sort_dir: document.getElementById('f_sort_dir'),
                per_page: document.getElementById('f_per_page'),
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

            async function loadFarmsOptions() {
                try {
                    const data = await fetchJSON(`/user/farm/show-all-farms`);
                    const list = data.data || [];
                    [flt.farm_id, f.farm_id].forEach(sel => {
                        const cur = sel.value;
                        sel.innerHTML = '<option value="">—</option>';
                        list.forEach(it => {
                            const opt = document.createElement('option');
                            opt.value = it.id;
                            opt.textContent = it.name || ('#' + it.id);
                            sel.appendChild(opt);
                        });
                        if (cur) sel.value = cur;
                    });
                } catch (e) {
                    console.warn('Failed to load farms', e);
                }
            }

            function renderSales(items) {
                cards.innerHTML = '';
                items.forEach(it => {
                    const calcTotal = (Number(it.quantity) || 0) * (Number(it.price_per_unit) || 0);
                    const total = (it.total_price ?? calcTotal) || 0;
                    const card = document.createElement('div');
                    card.className = 'card';
                    card.dataset.id = it.id;
                    card.innerHTML = `
        <div class="left">
          <div class="title">🏡 ${ (it.farm && it.farm.name) ? it.farm.name : (it.farm_id ?? '—') }</div>

          <div class="row" style="margin:6px 0">
            <span class="badge">🏷️ الحالة: ${it.status ?? '—'}</span>
            <span class="badge">👤 المشتري: ${it.buyer_name ?? '—'}</span>
            <span class="badge">📍 التسليم: ${it.delivery_location ?? '—'}</span>
          </div>
          <div class="row">
            <span class="badge">📦 الكمية: ${it.quantity ?? '—'} ${it.unit ?? ''}</span>
            <span class="badge">💵 سعر/وحدة: ${it.price_per_unit ?? '—'}</span>
            <span class="badge">💰 الإجمالي: ${Number(total).toFixed(2)}</span>
            <span class="badge">🏡 مزرعة: ${(it.farm && it.farm.name) ? it.farm.name : (it.farm_id ?? '—')}</span>
          </div>
        </div>
        <div class="right">
          <button class="btn btn-ghost btn-edit">✏️ تعديل</button>
          <button class="btn btn-ghost btn-del">🗑️ حذف</button>
        </div>
      `;
                    card.addEventListener('click', (e) => {
                        if (e.target.closest('.btn-edit') || e.target.closest('.btn-del')) return;
                        openDetails(it);
                    });
                    card.querySelector('.btn-edit').addEventListener('click', () => openEdit(it));
                    card.querySelector('.btn-del').addEventListener('click', () => doDelete(it.id, it
                        .crop_name));
                    cards.appendChild(card);
                });
            }

            function openDetails(it) {
                detailsBody.innerHTML = '';
                const rows = [
                    ['المزرعة', (it.farm && it.farm.name) ? it.farm.name : it.farm_id],
                    ['اسم المحصول', it.crop_name],
                    ['الكمية', it.quantity],
                    ['الوحدة', it.unit],
                    ['سعر/وحدة', it.price_per_unit],
                    ['الإجمالي', it.total_price ?? ((Number(it.quantity) || 0) * (Number(it.price_per_unit) ||
                        0)).toFixed(2)],
                    ['الحالة', it.status],
                    ['اسم المشتري', it.buyer_name],
                    ['موقع التسليم', it.delivery_location],
                    ['تاريخ البيع', it.sale_date],
                    ['ملاحظات', it.notes],

                ];
                rows.forEach(([k, v]) => {
                    const d = document.createElement('div');
                    d.className = 'field';
                    d.innerHTML =
                        `<label style="font-weight:700">${k}</label><div class="meta">${v ?? '—'}</div>`;
                    detailsBody.appendChild(d);
                });
                openModal(modalDetails);
            }

            btnOpenCreate.addEventListener('click', () => openCreate());

            function openCreate() {
                form.reset();
                f.id.value = '';
                f.unit.value = 'كغ';
                f.status.value = 'قيد البيع';
                computeTotalPlaceholder();
                formTitle.textContent = 'إضافة عملية بيع';
                btnSave.textContent = 'حفظ';
                setStatus('', false, formStatus);
                openModal(modalForm);
            }

            function openEdit(it) {
                f.id.value = it.id;
                f.farm_id.value = it.farm_id ?? '';
                f.crop_name.value = it.crop_name ?? '';
                f.quantity.value = it.quantity ?? '';
                f.unit.value = it.unit ?? 'كغ';
                f.price_per_unit.value = it.price_per_unit ?? '';
                f.total_price.value = it.total_price ?? '';
                f.status.value = it.status ?? 'قيد البيع';
                f.buyer_name.value = it.buyer_name ?? '';
                f.delivery_location.value = it.delivery_location ?? '';
                f.sale_date.value = it.sale_date ?? '';
                f.notes.value = it.notes ?? '';
                computeTotalPlaceholder();
                formTitle.textContent = 'تعديل عملية بيع';
                btnSave.textContent = 'تحديث';
                setStatus('', false, formStatus);
                openModal(modalForm);
            }

            function computeTotalPlaceholder() {
                const q = parseFloat(f.quantity.value) || 0;
                const p = parseFloat(f.price_per_unit.value) || 0;
                const t = q * p;
                f.total_price.placeholder = t ? t.toFixed(2) : 'سيُحسب تلقائيًا إذا تُرك فارغًا';
            }
            f.quantity.addEventListener('input', computeTotalPlaceholder);
            f.price_per_unit.addEventListener('input', computeTotalPlaceholder);

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const id = f.id.value ? Number(f.id.value) : null;

                let total = f.total_price.value !== '' ? Number(f.total_price.value) : null;
                if (total === null) {
                    const q = parseFloat(f.quantity.value) || 0;
                    const p = parseFloat(f.price_per_unit.value) || 0;
                    total = Number((q * p).toFixed(2));
                }

                const payload = {
                    farm_id: f.farm_id.value ? Number(f.farm_id.value) : null,
                    crop_name: f.crop_name.value?.trim() || null,
                    quantity: f.quantity.value !== '' ? Number(f.quantity.value) : null,
                    unit: f.unit.value,
                    price_per_unit: f.price_per_unit.value !== '' ? Number(f.price_per_unit.value) :
                        null,
                    total_price: total,
                    status: f.status.value,
                    buyer_name: f.buyer_name.value?.trim() || null,
                    delivery_location: f.delivery_location.value?.trim() || null,
                    sale_date: f.sale_date.value || null,
                    notes: f.notes.value?.trim() || null,
                };

                try {
                    btnSave.disabled = true;
                    let url, method;
                    if (id) {
                        url = `/user/crop-sale/update-crop-sales/${id}`;
                        method = 'PUT';
                    } else {
                        url = `/user/crop-sale/add-crop-sale`;
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
                    setStatus(id ? 'تم تحديث العملية بنجاح' : 'تمت إضافة العملية بنجاح', true,
                        formStatus);
                    await doFilter({
                        resetPage: true
                    });
                    setTimeout(() => closeModal(modalForm), 400);
                } catch (err) {
                    console.error(err);
                    const errs = err?.data?.errors ? Object.values(err.data.errors).flat().join(' | ') :
                        '';
                    setStatus((errs || err.message), false, formStatus);
                } finally {
                    btnSave.disabled = false;
                }
            });

            async function doDelete(id, name) {
                const ok = confirm(`هل أنت متأكد من حذف عملية بيع "${name || '#'+id}"؟`);
                if (!ok) return;
                try {
                    setStatus('جاري الحذف…');
                    await fetchJSON(`/user/crop-sale/delete-crop-sales/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': token
                        }
                    });
                    setStatus('تم حذف العملية بنجاح', true);
                    await doFilter();
                } catch (err) {
                    console.error(err);
                    setStatus('خطأ: ' + err.message);
                }
            }

            let state = {
                page: 1,
                last_page: 1,
                total: 0
            };

            function buildParams() {
                const p = new URLSearchParams();
                if (qGlobal.value.trim()) p.set('q', qGlobal.value.trim());

                if (flt.farm_id.value) p.set('farm_id', flt.farm_id.value);
                if (flt.crop_name.value.trim()) p.set('crop_name', flt.crop_name.value.trim());
                if (flt.unit.value.trim()) p.set('unit', flt.unit.value.trim());
                if (flt.status.value.trim()) p.set('status', flt.status.value.trim());
                if (flt.buyer_name.value.trim()) p.set('buyer_name', flt.buyer_name.value.trim());
                if (flt.delivery_location.value.trim()) p.set('delivery_location', flt.delivery_location.value
                    .trim());

                if (flt.sale_from.value) p.set('sale_date_from', flt.sale_from.value);
                if (flt.sale_to.value) p.set('sale_date_to', flt.sale_to.value);

                if (flt.qty_min.value !== '') p.set('quantity_min', flt.qty_min.value);
                if (flt.qty_max.value !== '') p.set('quantity_max', flt.qty_max.value);
                if (flt.ppu_min.value !== '') p.set('price_per_unit_min', flt.ppu_min.value);
                if (flt.ppu_max.value !== '') p.set('price_per_unit_max', flt.ppu_max.value);
                if (flt.total_min.value !== '') p.set('total_price_min', flt.total_min.value);
                if (flt.total_max.value !== '') p.set('total_price_max', flt.total_max.value);

                if (flt.sort_by.value) p.set('sort_by', flt.sort_by.value);
                if (flt.sort_dir.value) p.set('sort_dir', flt.sort_dir.value);
                const per = Number(flt.per_page.value) || 5;
                p.set('per_page', per);
                p.set('page', state.page);
                return p;
            }

            async function doFilter(opts = {}) {
                if (opts.resetPage) state.page = 1;
                setStatus('جاري التحميل…');
                try {
                    const params = buildParams();
                    const url = `/user/crop-sale/filter-crop-sales?${params.toString()}`;
                    const data = await fetchJSON(url);
                    const items = data.data || [];
                    renderSales(items);

                    const meta = data.meta || {};
                    state.total = meta.total ?? items.length;
                    state.last_page = meta.last_page ?? 1;
                    countBadge.textContent = `الإجمالي: ${state.total}`;
                    buildPager();
                    setStatus('', true);
                } catch (e) {
                    console.error(e);
                    setStatus('خطأ في الفلترة: ' + e.message);
                    cards.innerHTML = '';
                    pager.style.display = 'none';
                    countBadge.textContent = '—';
                }
            }

            function buildPager() {
                const lp = Number(state.last_page) || 1;
                const cur = Number(state.page) || 1;
                if (lp <= 1) {
                    pager.style.display = 'none';
                    pager.innerHTML = '';
                    return;
                }
                pager.style.display = 'flex';
                const frag = document.createDocumentFragment();

                const mkBtn = (label, disabled, handler, current = false) => {
                    const b = document.createElement('button');
                    b.textContent = label;
                    if (current) b.classList.add('current');
                    if (disabled) b.setAttribute('disabled', '');
                    b.addEventListener('click', handler);
                    frag.appendChild(b);
                };

                mkBtn('السابق', cur <= 1, () => {
                    state.page = Math.max(1, cur - 1);
                    doFilter();
                });

                const show = [];
                show.push(1);
                if (cur - 1 > 1) show.push(cur - 1);
                if (cur !== 1 && cur !== lp) show.push(cur);
                if (cur + 1 < lp) show.push(cur + 1);
                if (!show.includes(lp)) show.push(lp);
                const uniq = [...new Set(show)].sort((a, b) => a - b);
                uniq.forEach(n => mkBtn(String(n), false, () => {
                    state.page = n;
                    doFilter();
                }, n === cur));

                mkBtn('التالي', cur >= lp, () => {
                    state.page = Math.min(lp, cur + 1);
                    doFilter();
                });

                pager.innerHTML = '';
                pager.appendChild(frag);
            }

            const debouncedFilter = debounce(() => doFilter({
                resetPage: true
            }), 350);

            qGlobal.addEventListener('input', debouncedFilter);
            btnSearch.addEventListener('click', () => doFilter({
                resetPage: true
            }));
            btnReset.addEventListener('click', () => {
                qGlobal.value = '';
                btnFiltersReset.click();
                filtersPanel.style.display = 'none';
                doFilter({
                    resetPage: true
                });
            });

            [flt.farm_id, flt.sort_by, flt.sort_dir].forEach(el =>
                el.addEventListener('change', () => doFilter({
                    resetPage: true
                }))
            );

            [
                flt.crop_name, flt.unit, flt.status, flt.buyer_name, flt.delivery_location,
                flt.sale_from, flt.sale_to, flt.qty_min, flt.qty_max,
                flt.ppu_min, flt.ppu_max, flt.total_min, flt.total_max, flt.per_page
            ].forEach(el => el.addEventListener('input', debouncedFilter));

            btnToggleFilters.addEventListener('click', () => {
                const st = (filtersPanel.style.display || 'none');
                filtersPanel.style.display = (st === 'none') ? 'block' : 'none';
            });

            btnFiltersApply.addEventListener('click', () => doFilter({
                resetPage: true
            }));
            btnFiltersReset.addEventListener('click', () => {
                Object.values(flt).forEach(el => el.value = (el.id === 'f_per_page' ? '5' : ''));
                debouncedFilter();
            });

            await loadFarmsOptions();
            await doFilter({
                resetPage: true
            });
        })();
    </script>



</body>

</html>
