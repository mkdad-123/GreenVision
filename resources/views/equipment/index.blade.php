<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>معداتّي — إدارة المعدّات</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preload" as="image" href="{{ asset('/background/equipment/ima1.webp') }}" fetchpriority="high">
    <link rel="preload" as="image" href="{{ asset('/background/equipment/ima2.webp') }}">
    <link rel="stylesheet" href="{{ asset('css/equipment.css') }}">

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
            <h1>معداتّي</h1>
            <div class="row">
                <button class="btn" id="btnOpenCreate">➕ إضافة معدّة جديدة</button>
            </div>
        </header>

        <div class="toolbar">
            <div class="search">
                <input class="input" id="qGlobal" type="search" placeholder="بحث عام (اسم، رقم تسلسلي، نوع، موقع)…">
                <button class="btn btn-ghost" id="btnSearch" type="button">بحث</button>
                <button class="btn btn-ghost" id="btnToggleFilters" type="button">فلترة متقدمة</button>
                <button class="btn btn-ghost" id="btnReset" type="button">إعادة ضبط</button>
            </div>
            <span class="badge" id="countBadge">—</span>
        </div>

        <section class="card" id="filtersPanel" style="display:none; margin-top:-8px">
            <div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(220px,1fr)); gap:12px">
                <div class="field">
                    <label>الاسم</label>
                    <input class="input" id="f_name" type="text" placeholder="مثال: حصّادة">
                </div>
                <div class="field">
                    <label>الرقم التسلسلي</label>
                    <input class="input" id="f_serial" type="text" placeholder="SN-...">
                </div>
                <div class="field">
                    <label>النوع</label>
                    <input class="input" id="f_type" type="text" placeholder="جرار، مضخة ...">
                </div>
                <div class="field">
                    <label>الموقع</label>
                    <input class="input" id="f_location" type="text" placeholder="المخزن A ...">
                </div>
                <div class="field">
                    <label>الحالة</label>
                    <select id="f_status">
                        <option value="">—</option>
                        <option value="نشطة">نشطة</option>
                        <option value="تحت الصيانة">تحت الصيانة</option>
                        <option value="معطلة">معطلة</option>
                    </select>
                </div>
                <div class="field">
                    <label>ساعات الاستخدام</label>
                    <select id="f_usage_hours">
                        <option value="">—</option>
                        <option>0.5</option>
                        <option>1</option>
                        <option>1.5</option>
                        <option>2</option>
                        <option>2.5</option>
                        <option>3</option>
                        <option>3.5</option>
                        <option>4</option>
                        <option>4.5</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                        <option>9</option>
                        <option>10</option>
                        <option>12</option>
                        <option>24</option>
                    </select>
                </div>
                <div class="field">
                    <label>تاريخ الشراء (مطابقة)</label>
                    <input class="input" id="f_purchase_date" type="date">
                </div>
                <div class="field">
                    <label>آخر صيانة (مطابقة)</label>
                    <input class="input" id="f_last_maintenance" type="date">
                </div>
                <div class="field">
                    <label>الصيانة القادمة (مطابقة)</label>
                    <input class="input" id="f_next_maintenance" type="date">
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
            <h3>تفاصيل المعدّة</h3>
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
            <h3 id="formTitle">إضافة معدّة</h3>

            <form id="equipmentForm">
                <input type="hidden" id="eqId">
                <div class="form-grid">
                    <div class="field">
                        <label>اسم المعدّة</label>
                        <input type="text" id="name" required>
                    </div>
                    <div class="field">
                        <label>الرقم التسلسلي</label>
                        <input type="text" id="serial_number">
                    </div>
                    <div class="field">
                        <label>تاريخ الشراء</label>
                        <input type="date" id="purchase_date">
                    </div>
                    <div class="field">
                        <label>آخر صيانة</label>
                        <input type="date" id="last_maintenance">
                    </div>
                    <div class="field">
                        <label>الصيانة القادمة</label>
                        <input type="date" id="next_maintenance">
                    </div>
                    <div class="field">
                        <label>الحالة</label>
                        <select id="status" required>
                            <option value="نشطة" selected>نشطة</option>
                            <option value="تحت الصيانة">تحت الصيانة</option>
                            <option value="معطلة">معطلة</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>النوع</label>
                        <input type="text" id="type" placeholder="جرار، حصادة...">
                    </div>
                    <div class="field">
                        <label>الموقع</label>
                        <input type="text" id="location" placeholder="المخزن A...">
                    </div>
                    <div class="field">
                        <label>ساعات الاستخدام</label>
                        <select id="usage_hours">
                            <option value="">—</option>
                            <option>0.5</option>
                            <option>1</option>
                            <option>1.5</option>
                            <option>2</option>
                            <option>2.5</option>
                            <option>3</option>
                            <option>3.5</option>
                            <option>4</option>
                            <option>4.5</option>
                            <option>5</option>
                            <option>6</option>
                            <option>7</option>
                            <option>8</option>
                            <option>9</option>
                            <option>10</option>
                            <option>12</option>
                            <option>24</option>
                        </select>
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
                    'X-Requested-With': 'XMLHttpRequest',
                };
                init.headers = {
                    ...(init.headers || {}),
                    ...baseHeaders
                };
                init.credentials = 'include';

                const resp = await fetch(url, init);
                const contentType = resp.headers.get('content-type') || '';
                const raw = await resp.text();

                if (!contentType.includes('application/json')) {
                    const snippet = raw.replace(/\s+/g, ' ').slice(0, 180);
                    throw new Error(
                        `Non-JSON response (status ${resp.status}). ربما صفحة تسجيل الدخول أو HTML.\n${snippet}…`
                    );
                }
                let data;
                try {
                    data = JSON.parse(raw);
                } catch {
                    throw new Error('فشل تحليل رد JSON.');
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

            (function() {
                const bgImages = [
                    "{{ asset('/background/equipment/ima1.jpg') }}",
                    "{{ asset('/background/equipment/ima2.jpg') }}",
                    "{{ asset('/background/equipment/ima3.jpg') }}",
                    "{{ asset('/background/equipment/ima4.jpg') }}"
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
                setSrc(A, bgImages[0]);
                A.classList.add('active');
                setSrc(B, bgImages[1 % bgImages.length]);
                setInterval(() => {
                    i = (i + 1) % bgImages.length;
                    setSrc(idle, bgImages[i]);
                    requestAnimationFrame(() => {
                        active.classList.remove('active');
                        idle.classList.add('active');
                        const tmp = active;
                        active = idle;
                        idle = tmp;
                    });
                }, 8000);
            })();

            const cards = document.getElementById('cards');
            const statusEl = document.getElementById('status');
            const countBadge = document.getElementById('countBadge');

            const modalDetails = document.getElementById('modalDetails');
            const detailsBody = document.getElementById('detailsBody');

            const modalForm = document.getElementById('modalForm');
            const form = document.getElementById('equipmentForm');
            const formTitle = document.getElementById('formTitle');
            const formStatus = document.getElementById('formStatus');
            const btnOpenCreate = document.getElementById('btnOpenCreate');
            const btnSave = document.getElementById('btnSave');

            const qGlobal = document.getElementById('qGlobal');
            const btnSearch = document.getElementById('btnSearch');
            const filtersPanel = document.getElementById('filtersPanel');
            const btnToggleFilters = document.getElementById('btnToggleFilters');
            const btnFiltersApply = document.getElementById('btnFiltersApply');
            const btnFiltersReset = document.getElementById('btnFiltersReset');
            const btnReset = document.getElementById('btnReset');

            const f = {
                id: document.getElementById('eqId'),
                name: document.getElementById('name'),
                serial_number: document.getElementById('serial_number'),
                purchase_date: document.getElementById('purchase_date'),
                last_maintenance: document.getElementById('last_maintenance'),
                next_maintenance: document.getElementById('next_maintenance'),
                status: document.getElementById('status'),
                type: document.getElementById('type'),
                location: document.getElementById('location'),
                usage_hours: document.getElementById('usage_hours'),
                notes: document.getElementById('notes'),
            };

            const flt = {
                name: document.getElementById('f_name'),
                serial_number: document.getElementById('f_serial'),
                type: document.getElementById('f_type'),
                location: document.getElementById('f_location'),
                status: document.getElementById('f_status'),
                usage_hours: document.getElementById('f_usage_hours'),
                purchase_date: document.getElementById('f_purchase_date'),
                last_maintenance: document.getElementById('f_last_maintenance'),
                next_maintenance: document.getElementById('f_next_maintenance'),
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

            let lastItems = [];

            async function loadEquipments() {
                setStatus('جاري تحميل المعدّات…');
                try {
                    const data = await fetchJSON(`/user/equipment/show-all-equipments`);
                    const items = data.data ?? data;
                    lastItems = Array.isArray(items) ? items : [];
                    renderEquipments(lastItems);
                    setStatus('', true);
                } catch (e) {
                    console.error(e);
                    setStatus('حدث خطأ أثناء التحميل: ' + e.message);
                }
            }

            function renderEquipments(items) {
                cards.innerHTML = '';
                countBadge.textContent = `عدد المعدّات: ${items.length}`;
                items.forEach(item => {
                    const c = document.createElement('div');
                    c.className = 'card';
                    c.dataset.id = item.id;
                    c.innerHTML = `
                <div class="title">🔧 ${item.name ?? '—'}</div>
                <div class="meta">📍 ${item.location ?? '—'}</div>
                <div class="row">
                  <span class="badge">#️⃣ ${item.serial_number ?? '—'}</span>
                  <span class="badge">🧩 ${item.type ?? '—'}</span>
                  <span class="badge">⚙️ ${item.status ?? '—'}</span>
                  <span class="badge">⏱️ ${item.usage_hours ?? '—'} س</span>
                </div>
                <div class="row">
                  <span class="badge">🛒 شراء: ${item.purchase_date ?? '—'}</span>
                  <span class="badge">🛠️ آخر صيانة: ${item.last_maintenance ?? '—'}</span>
                  <span class="badge">📅 القادمة: ${item.next_maintenance ?? '—'}</span>
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
            }

            function openDetails(item) {
                detailsBody.innerHTML = '';
                const fields = [
                    ['الاسم', item.name],
                    ['الرقم التسلسلي', item.serial_number],
                    ['النوع', item.type],
                    ['الموقع', item.location],
                    ['الحالة', item.status],
                    ['ساعات الاستخدام', item.usage_hours],
                    ['تاريخ الشراء', item.purchase_date],
                    ['آخر صيانة', item.last_maintenance],
                    ['الصيانة القادمة', item.next_maintenance],
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

            btnOpenCreate.addEventListener('click', () => openCreate());

            function openCreate() {
                form.reset();
                f.id.value = '';
                f.status.value = 'نشطة';
                formTitle.textContent = 'إضافة معدّة';
                btnSave.textContent = 'حفظ';
                setStatus('', false, formStatus);
                openModal(modalForm);
            }

            function openEdit(item) {
                f.id.value = item.id;
                f.name.value = item.name ?? '';
                f.serial_number.value = item.serial_number ?? '';
                f.purchase_date.value = item.purchase_date ?? '';
                f.last_maintenance.value = item.last_maintenance ?? '';
                f.next_maintenance.value = item.next_maintenance ?? '';
                f.status.value = item.status ?? 'نشطة';
                f.type.value = item.type ?? '';
                f.location.value = item.location ?? '';
                f.usage_hours.value = item.usage_hours ?? '';
                f.notes.value = item.notes ?? '';
                formTitle.textContent = 'تعديل معدّة';
                btnSave.textContent = 'تحديث';
                setStatus('', false, formStatus);
                openModal(modalForm);
            }

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const id = f.id.value ? Number(f.id.value) : null;

                const payload = {
                    name: f.name.value?.trim() || null,
                    serial_number: f.serial_number.value?.trim() || null,
                    purchase_date: f.purchase_date.value || null,
                    last_maintenance: f.last_maintenance.value || null,
                    next_maintenance: f.next_maintenance.value || null,
                    status: (f.status.value || 'نشطة'),
                    type: f.type.value?.trim() || null,
                    location: f.location.value?.trim() || null,
                    usage_hours: f.usage_hours.value || null,
                    notes: f.notes.value?.trim() || null,
                };

                try {
                    btnSave.disabled = true;
                    let url, method;
                    if (id) {
                        url = `/user/equipment/update-equipments/${id}`;
                        method = 'PUT';
                    } else {
                        url = `/user/equipment/add-equipments`;
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
                    setStatus(id ? 'تم تحديث المعدّة بنجاح' : 'تم إضافة المعدّة بنجاح', true, formStatus);
                    await loadEquipments();
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
                const ok = confirm(`هل أنت متأكد من حذف المعدّة "${name || '#'+id}"؟`);
                if (!ok) return;
                try {
                    setStatus('جاري الحذف…');
                    await fetchJSON(`/user/equipment/delete-equipments/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': token
                        }
                    });
                    setStatus('تم حذف المعدّة بنجاح', true);
                    await loadEquipments();
                } catch (err) {
                    console.error(err);
                    setStatus('خطأ: ' + err.message);
                }
            }

            btnToggleFilters?.addEventListener('click', () => {
                const st = (filtersPanel.style.display || 'none');
                filtersPanel.style.display = (st === 'none') ? 'block' : 'none';
            });

            function buildFilterParams() {
                const params = new URLSearchParams();
                if (flt.name.value.trim()) params.set('name', flt.name.value.trim());
                if (flt.serial_number.value.trim()) params.set('serial_number', flt.serial_number.value.trim());
                if (flt.type.value.trim()) params.set('type', flt.type.value.trim());
                if (flt.location.value.trim()) params.set('location', flt.location.value.trim());
                if (flt.status.value) params.set('status', flt.status.value);
                if (flt.usage_hours.value) params.set('usage_hours', flt.usage_hours.value);
                if (flt.purchase_date.value) params.set('purchase_date', flt.purchase_date.value);
                if (flt.last_maintenance.value) params.set('last_maintenance', flt.last_maintenance.value);
                if (flt.next_maintenance.value) params.set('next_maintenance', flt.next_maintenance.value);
                return params;
            }

            async function doFilter() {
                setStatus('جاري الفلترة…');
                try {
                    const params = buildFilterParams();
                    const url = `/user/equipment/filter-equipments` + (params.toString() ? `?${params}` : '');
                    const data = await fetchJSON(url);
                    const items = data.data ?? data;
                    lastItems = Array.isArray(items) ? items : [];
                    renderEquipments(lastItems);
                    setStatus('', true);
                } catch (e) {
                    console.error(e);
                    setStatus('خطأ في الفلترة: ' + e.message);
                }
            }

            btnFiltersApply?.addEventListener('click', doFilter);
            btnFiltersReset?.addEventListener('click', () => {
                Object.values(flt).forEach(el => {
                    el.value = '';
                });
            });

            function doGlobalFilter() {
                if (!qGlobal) return;
                const q = qGlobal.value.trim().toLowerCase();
                if (!q) {
                    renderEquipments(lastItems);
                    setStatus('', true);
                    return;
                }
                const filtered = lastItems.filter(it => {
                    const hay = [
                        it.name, it.serial_number, it.type, it.location,
                        it.status, it.usage_hours, it.notes,
                        it.purchase_date, it.last_maintenance, it.next_maintenance
                    ].map(v => (v ?? '').toString().toLowerCase());
                    return hay.some(v => v.includes(q));
                });
                renderEquipments(filtered);
                setStatus(`تم العثور على ${filtered.length} من ${lastItems.length}`, true);
            }
            btnSearch?.addEventListener('click', doGlobalFilter);
            qGlobal?.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') doGlobalFilter();
            });
            qGlobal?.addEventListener('input', () => {
                doGlobalFilter();
            });

            btnReset?.addEventListener('click', () => {
                if (qGlobal) qGlobal.value = '';
                btnFiltersReset?.click();
                filtersPanel.style.display = 'none';
                loadEquipments();
            });
            loadEquipments();
        })();
    </script>


</body>

</html>
