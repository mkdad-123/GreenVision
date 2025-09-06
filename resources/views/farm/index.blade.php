<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>مزارعي — إدارة المزارع</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/farm.css') }}">


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
            <h1>مزارعي</h1>
            <div class="row">
                <button class="btn" id="btnOpenCreate">➕ إضافة مزرعة جديدة</button>
            </div>
        </header>

        <div class="toolbar">
            <div class="search">
                <input class="input" id="qGlobal" type="search" placeholder="بحث عام (اسم، موقع، محصول، …)">
                <button class="btn btn-ghost" id="btnSearch" type="button">بحث</button>
                <button class="btn btn-ghost" id="btnToggleFilters" type="button">بحث متقدم </button>
                <button class="btn btn-ghost" id="btnReset" type="button">عرض الكل</button>
            </div>
            <span class="badge" id="countBadge">—</span>
        </div>

        <section class="card" id="filtersPanel" style="display:none; margin-top:-8px">
            <div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(220px,1fr)); gap:12px">
                <div class="field">
                    <label>الاسم</label>
                    <input class="input" id="f_name" type="text" placeholder="مثال: مزرعة السلام">
                </div>
                <div class="field">
                    <label>الموقع</label>
                    <input class="input" id="f_location" type="text" placeholder="المدينة/المنطقة">
                </div>
                <div class="field">
                    <label>نوع المحصول</label>
                    <input class="input" id="f_crop_type" type="text" placeholder="بطاطا، قمح، …">
                </div>
                <div class="field">
                    <label>نوع الري</label>
                    <select id="f_irrigation_type">
                        <option value="">—</option>
                        <option value="سطحي">سطحي</option>
                        <option value="تنقيط">تنقيط</option>
                        <option value="رش">رش</option>
                    </select>
                </div>
                <div class="field">
                    <label>نوع التربة</label>
                    <select id="f_soil_type">
                        <option value="">—</option>
                        <option value="طميية">طميية</option>
                        <option value="رملية">رملية</option>
                        <option value="طينية">طينية</option>
                    </select>
                </div>
                <div class="field">
                    <label>المساحة من (هـ)</label>
                    <input class="input" id="f_area_min" type="number" step="0.01" min="0">
                </div>
                <div class="field">
                    <label>المساحة إلى (هـ)</label>
                    <input class="input" id="f_area_max" type="number" step="0.01" min="0">
                </div>
                <div class="field">
                    <label>الحالة</label>
                    <div class="row" style="gap:10px">
                        <label class="badge" style="cursor:pointer"><input type="checkbox" id="f_status_active">
                            نشطة</label>
                        <label class="badge" style="cursor:pointer"><input type="checkbox" id="f_status_paused">
                            متوقفة</label>
                    </div>
                </div>
                <div class="field">
                    <label>من تاريخ (إنشاء)</label>
                    <input class="input" id="f_created_from" type="date">
                </div>
                <div class="field">
                    <label>إلى تاريخ (إنشاء)</label>
                    <input class="input" id="f_created_to" type="date">
                </div>
                <div class="field">
                    <label>الترتيب حسب</label>
                    <select id="f_sort_by">
                        <option value="created_at">تاريخ الإنشاء</option>
                        <option value="name">الاسم</option>
                        <option value="area">المساحة</option>
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
            <div id="pagination" class="pagination" aria-label="ترقيم الصفحات"></div>
            <div class="status" id="status"></div>

        </div>
    </div>

    <div class="modal" id="modalDetails" role="dialog" aria-modal="true">
        <div class="overlay"></div>
        <div class="sheet">
            <button class="btn btn-ghost close" data-close="modalDetails">✕</button>
            <h3>تفاصيل المزرعة</h3>
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
            <h3 id="formTitle">إضافة مزرعة</h3>

            <form id="farmForm">
                <input type="hidden" id="farmId">
                <div class="form-grid">
                    <div class="field">
                        <label>اسم المزرعة</label>
                        <input type="text" id="name" required>
                    </div>
                    <div class="field">
                        <label>الموقع</label>
                        <input type="text" id="location">
                    </div>
                    <div class="field">
                        <label>المساحة (هـ)</label>
                        <input type="number" id="area" step="0.01" min="0">
                    </div>
                    <div class="field">
                        <label>نوع المحصول</label>
                        <input type="text" id="crop_type">
                    </div>
                    <div class="field">
                        <label>نوع الري</label>
                        <select id="irrigation_type">
                            <option value="">—</option>
                            <option value="سطحي">سطحي</option>
                            <option value="تنقيط">تنقيط</option>
                            <option value="رش">رش</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>نوع التربة</label>
                        <select id="soil_type">
                            <option value="">—</option>
                            <option value="طميية">طميية</option>
                            <option value="رملية">رملية</option>
                            <option value="طينية">طينية</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>الحالة</label>
                        <select id="status" required>
                            <option value="نشطة" selected>نشطة</option>
                            <option value="متوقفة">متوقفة</option>
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
                    throw new Error(
                        `Non-JSON response (status ${resp.status}). ربما صفحة تسجيل الدخول أو HTML.\n${snippet}…`
                    );
                }
                let data;
                try {
                    data = JSON.parse(raw);
                } catch {
                    throw new Error('فشل تحليل رد JSON من الخادم.');
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

            function debounce(fn, delay = 350) {
                let t;
                return (...args) => {
                    clearTimeout(t);
                    t = setTimeout(() => fn(...args), delay);
                };
            }

            (function() {
                const bgImages = [
                    "{{ asset('storage/background/farm/ima2.jpg') }}",
                    "{{ asset('storage/background/farm/ima1.jpg') }}",
                    "{{ asset('storage/background/farm/ima3.jpg') }}",
                    "{{ asset('storage/background/farm/ima4.jpg') }}"
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
            const form = document.getElementById('farmForm');
            const formTitle = document.getElementById('formTitle');
            const formStatus = document.getElementById('formStatus');

            const btnOpenCreate = document.getElementById('btnOpenCreate');
            const btnSave = document.getElementById('btnSave');

            const filtersPanel = document.getElementById('filtersPanel');
            const btnToggleFilters = document.getElementById('btnToggleFilters');
            const btnFiltersApply = document.getElementById('btnFiltersApply');
            const btnFiltersReset = document.getElementById('btnFiltersReset');
            const btnReset = document.getElementById('btnReset');
            const btnSearch = document.getElementById('btnSearch');
            const qGlobal = document.getElementById('qGlobal');

            const f = {
                id: document.getElementById('farmId'),
                name: document.getElementById('name'),
                location: document.getElementById('location'),
                area: document.getElementById('area'),
                crop_type: document.getElementById('crop_type'),
                irrigation_type: document.getElementById('irrigation_type'),
                soil_type: document.getElementById('soil_type'),
                status: document.getElementById('status'),
                notes: document.getElementById('notes'),
            };

            const f_name = document.getElementById('f_name');
            const f_location = document.getElementById('f_location');
            const f_crop_type = document.getElementById('f_crop_type');
            const f_irrigation = document.getElementById('f_irrigation_type');
            const f_soil = document.getElementById('f_soil_type');
            const f_area_min = document.getElementById('f_area_min');
            const f_area_max = document.getElementById('f_area_max');
            const f_status_active = document.getElementById('f_status_active');
            const f_status_paused = document.getElementById('f_status_paused');
            const f_created_from = document.getElementById('f_created_from');
            const f_created_to = document.getElementById('f_created_to');
            const f_sort_by = document.getElementById('f_sort_by');
            const f_sort_dir = document.getElementById('f_sort_dir');
            const f_limit = document.getElementById('f_limit');

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
                countBadge.textContent = `عدد المزارع: ${count}`;
                if (q) setStatus(count ? `تم العثور على ${count} نتيجة لعبارة "${q}"` : `لا نتائج لعبارة "${q}"`, !!
                    count);
                else setStatus(count ? '' : 'لا توجد مزارع بعد', !!count);
            }
            let allFarms = [];
            let currentPage = 1;
            const pageSize = 3;
            const paginationEl = document.getElementById('pagination');

            function renderPage() {
                const total = allFarms.length;
                const totalPages = Math.max(1, Math.ceil(total / pageSize));
                currentPage = Math.min(Math.max(1, currentPage), totalPages);

                const start = (currentPage - 1) * pageSize;
                const end = start + pageSize;
                const slice = allFarms.slice(start, end);

                cards.innerHTML = '';
                slice.forEach(item => {
                    const c = document.createElement('div');
                    c.className = 'card';
                    c.dataset.id = item.id;
                    c.innerHTML = `
      <div class="title">${item.name ?? '—'}</div>
      <div class="meta">📍 ${item.location ?? '—'}</div>
      <div class="row">
        <span class="badge">🌾 ${item.crop_type ?? '—'}</span>
        <span class="badge">🧱 ${item.soil_type ?? '—'}</span>
        <span class="badge">💧 ${item.irrigation_type ?? '—'}</span>
        <span class="badge">📐 ${item.area ?? '—'} هـ</span>
        <span class="badge">⚙️ ${item.status ?? '—'}</span>
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

                renderPagination(totalPages);
            }

            function renderPagination(totalPages) {
                if (!paginationEl) return;
                if (allFarms.length === 0) {
                    paginationEl.innerHTML = '';
                    return;
                }

                let html = `
    <button class="p-btn" ${currentPage===1?'disabled':''} data-nav="prev">السابق</button>
  `;

                const windowSize = 5;
                let start = Math.max(1, currentPage - Math.floor(windowSize / 2));
                let end = Math.min(totalPages, start + windowSize - 1);
                start = Math.max(1, end - windowSize + 1);

                for (let p = start; p <= end; p++) {
                    html +=
                        `<button class="p-btn p-page ${p===currentPage?'active':''}" data-page="${p}">${p}</button>`;
                }

                html += `
    <button class="p-btn" ${currentPage===totalPages?'disabled':''} data-nav="next">التالي</button>
  `;

                paginationEl.innerHTML = html;

                paginationEl.querySelectorAll('[data-page]').forEach(b => {
                    b.addEventListener('click', () => {
                        currentPage = parseInt(b.getAttribute('data-page'), 10);
                        renderPage();
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    });
                });
                const prev = paginationEl.querySelector('[data-nav="prev"]');
                const next = paginationEl.querySelector('[data-nav="next"]');
                prev && prev.addEventListener('click', () => {
                    if (currentPage > 1) {
                        currentPage--;
                        renderPage();
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    }
                });
                next && next.addEventListener('click', () => {
                    const totalPages = Math.max(1, Math.ceil(allFarms.length / pageSize));
                    if (currentPage < totalPages) {
                        currentPage++;
                        renderPage();
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    }
                });
            }

            /* ========== جلب + رسم ========== */
            // بدّل دالة renderFarms بالكامل بهذه:
            function renderFarms(items) {
                allFarms = Array.isArray(items) ? items : [];
                // حدّث الملخص بعدد النتائج الكلي
                updateSummary(allFarms.length);
                // ابدأ دائماً من الصفحة الأولى بعد أي جلب/فلترة
                currentPage = 1;
                renderPage(); // سيقوم بقصّ العناصر إلى 3 وعرض شريط الترقيم
            }


            /* ========== تفاصيل ========== */
            function openDetails(item) {
                detailsBody.innerHTML = '';
                const fields = [
                    ['الاسم', item.name],
                    ['الموقع', item.location],
                    ['المساحة (هـ)', item.area],
                    ['نوع المحصول', item.crop_type],
                    ['نوع الري', item.irrigation_type],
                    ['نوع التربة', item.soil_type],
                    ['الحالة', item.status],
                    ['ملاحظات', item.notes],
                    ['أضيفت في', item.created_at],
                    ['آخر تعديل', item.updated_at],
                ];
                fields.forEach(([k, v]) => {
                    const d = document.createElement('div');
                    d.className = 'detail';
                    d.innerHTML = `<b>${k}</b><div>${(v ?? '—')}</div>`;
                    detailsBody.appendChild(d);
                });
                openModal(modalDetails);
            }

            /* ========== إنشاء/تعديل ========== */
            btnOpenCreate.addEventListener('click', () => openCreate());

            function openCreate() {
                form.reset();
                f.id.value = '';
                formTitle.textContent = 'إضافة مزرعة';
                btnSave.textContent = 'حفظ';
                setStatus('', false, formStatus);
                openModal(modalForm);
            }

            function openEdit(item) {
                f.id.value = item.id;
                f.name.value = item.name ?? '';
                f.location.value = item.location ?? '';
                f.area.value = item.area ?? '';
                f.crop_type.value = item.crop_type ?? '';
                f.irrigation_type.value = item.irrigation_type ?? '';
                f.soil_type.value = item.soil_type ?? '';
                f.status.value = item.status ?? '';
                f.notes.value = item.notes ?? '';
                formTitle.textContent = 'تعديل مزرعة';
                btnSave.textContent = 'تحديث';
                setStatus('', false, formStatus);
                openModal(modalForm);
            }

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const id = f.id.value ? Number(f.id.value) : null;

                const payload = {
                    name: f.name.value?.trim() || null,
                    location: f.location.value?.trim() || null,
                    area: f.area.value ? Number(f.area.value) : null,
                    crop_type: f.crop_type.value?.trim() || null,
                    irrigation_type: f.irrigation_type.value || null,
                    soil_type: f.soil_type.value || null,
                    status: (f.status.value || 'نشطة'),
                    notes: f.notes.value?.trim() || null,
                };

                try {
                    btnSave.disabled = true;
                    let url, method = 'POST';
                    if (id) url = `/user/farm/update-farm/${id}`;
                    else url = `/user/farm/add-farm`;

                    await fetchJSON(url, {
                        method,
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    setStatus(id ? 'تم تحديث المزرعة بنجاح' : 'تم إنشاء المزرعة بنجاح', true, formStatus);
                    await doFilter(); // ← بدلاً من loadFarms
                    setTimeout(() => closeModal(modalForm), 400);

                } catch (err) {
                    console.error(err);
                    setStatus(err.message, false, formStatus);
                } finally {
                    btnSave.disabled = false;
                }
            });

            /* ========== حذف ========== */
            async function doDelete(id, name) {
                const ok = confirm(`هل أنت متأكد من حذف المزرعة "${name}"؟`);
                if (!ok) return;
                try {
                    setStatus('جاري الحذف…');
                    await fetchJSON(`/user/farm/delete-farm/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': token
                        }
                    });
                    setStatus('تم حذف المزرعة بنجاح', true);
                    await doFilter(); // ← بدلاً من loadFarms
                } catch (err) {
                    console.error(err);
                    setStatus('خطأ: ' + err.message);
                }
            }

            /* ========== بناء برامترات الفلترة ========== */
            function buildFilterParams() {
                const params = new URLSearchParams();

                // بحث عام q
                if (qGlobal?.value.trim()) params.set('q', qGlobal.value.trim());

                // فلاتر نصية محددة
                if (f_name.value.trim()) params.set('name', f_name.value.trim());
                if (f_location.value.trim()) params.set('location', f_location.value.trim());
                if (f_crop_type.value.trim()) params.set('crop_type', f_crop_type.value.trim());
                if (f_irrigation.value) params.set('irrigation_type', f_irrigation.value);
                if (f_soil.value) params.set('soil_type', f_soil.value);

                // مدى المساحة
                if (f_area_min.value !== '') params.set('area_min', f_area_min.value);
                if (f_area_max.value !== '') params.set('area_max', f_area_max.value);

                // الحالة كمصفوفة
                const statuses = [];
                if (f_status_active.checked) statuses.push('نشطة');
                if (f_status_paused.checked) statuses.push('متوقفة');
                statuses.forEach(s => params.append('status[]', s));

                // مدى التاريخ
                if (f_created_from.value) params.set('created_from', f_created_from.value);
                if (f_created_to.value) params.set('created_to', f_created_to.value);

                // ترتيب
                if (f_sort_by.value) params.set('sort_by', f_sort_by.value);
                if (f_sort_dir.value) params.set('sort_dir', f_sort_dir.value);

                // حد أقصى
                if (f_limit.value) params.set('limit', f_limit.value);

                return params;
            }

            /* ========== تنفيذ الفلترة (فوري) ========== */
            async function doFilter() {
                try {
                    const params = buildFilterParams();
                    const url = `/user/farm/filter-farm` + (params.toString() ? `?${params}` : '');
                    const data = await fetchJSON(url);
                    renderFarms(data.data || []);
                } catch (e) {
                    console.error(e);
                    setStatus('خطأ في الفلترة: ' + e.message);
                    renderFarms([]); // لتحديث الملخص والبادج
                }
            }
            const debouncedFilter = debounce(doFilter, 350);

            /* تشغيل فوري مثل واجهة الأدوات */
            // البحث العام
            qGlobal?.addEventListener('input', debouncedFilter);
            qGlobal?.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') e.preventDefault();
            });

            // حقول نصية/أرقام/تواريخ → input
            [f_name, f_location, f_crop_type, f_area_min, f_area_max, f_created_from, f_created_to, f_limit]
            .forEach(el => el?.addEventListener('input', debouncedFilter));

            // قوائم منسدلة/checkbox → change
            [f_irrigation, f_soil, f_sort_by, f_sort_dir, f_status_active, f_status_paused]
            .forEach(el => el?.addEventListener('change', debouncedFilter));

            // الأزرار تبقى تعمل أيضًا
            btnFiltersApply?.addEventListener('click', doFilter);
            btnSearch?.addEventListener('click', doFilter);

            // إظهار/إخفاء لوحة الفلاتر
            btnToggleFilters?.addEventListener('click', () => {
                const st = (filtersPanel.style.display || 'none');
                filtersPanel.style.display = (st === 'none') ? 'block' : 'none';
            });

            // مسح الفلاتر فقط
            btnFiltersReset?.addEventListener('click', () => {
                [f_name, f_location, f_crop_type, f_area_min, f_area_max, f_created_from, f_created_to, f_limit]
                .forEach(el => el.value = '');
                [f_irrigation, f_soil, f_sort_by, f_sort_dir].forEach(el => el.selectedIndex = 0);
                f_status_active.checked = false;
                f_status_paused.checked = false;
                debouncedFilter();
            });

            // إعادة ضبط كاملة (عرض الكل)
            btnReset?.addEventListener('click', () => {
                if (qGlobal) qGlobal.value = '';
                btnFiltersReset?.click();
                filtersPanel.style.display = 'none';
                doFilter();
            });

            /* ========== بدء التشغيل: فلترة فورية ========== */
            doFilter();
        })();
    </script>


</body>

</html>
