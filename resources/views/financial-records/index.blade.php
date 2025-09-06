<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>سجلاتي المالية — إدارة السجلات</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preload" as="image" href="{{ asset('/background/fin_rec/ima1.webp') }}" fetchpriority="high">
    <link rel="preload" as="image" href="{{ asset('/background/fin_rec/ima2.webp') }}">
    <link rel="stylesheet" href="{{ asset('css/fin_rec.css') }}">

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
            <h1>سجلاتي المالية</h1>
            <div class="row">
                <button class="btn" id="btnOpenCreate">➕ إضافة سجل</button>
            </div>
        </header>

        <div class="toolbar">
            <div class="search">
                <input class="input" id="qGlobal" type="search"
                    placeholder="بحث عام (الوصف، الفئة، طريقة الدفع، …)">
                <button class="btn btn-ghost" id="btnSearch" type="button">بحث</button>
                <button class="btn btn-ghost" id="btnToggleFilters" type="button">فلترة متقدمة</button>
                <button class="btn btn-ghost" id="btnReset" type="button">عرض الكل</button>
            </div>
            <span class="badge" id="countBadge">—</span>
        </div>

        <section class="card" id="filtersPanel" style="display:none; margin-top:-8px">
            <div class="form-grid">
                <div class="field">
                    <label>النوع</label>
                    <input class="input" id="f_type" type="text" placeholder="مثال: دخل / مصروف">
                </div>
                <div class="field">
                    <label>الفئة</label>
                    <input class="input" id="f_category" type="text" placeholder="سماد، أجور، بيع...">
                </div>
                <div class="field">
                    <label>طريقة الدفع</label>
                    <input class="input" id="f_payment_method" type="text" placeholder="نقدي، تحويل...">
                </div>
                <div class="field">
                    <label>الوصف</label>
                    <input class="input" id="f_description" type="text">
                </div>
                <div class="field">
                    <label>ملاحظات</label>
                    <input class="input" id="f_notes" type="text">
                </div>
                <div class="field">
                    <label>مرجع/رقم</label>
                    <input class="input" id="f_reference" type="text">
                </div>

                <div class="field">
                    <label>المبلغ من</label>
                    <input class="input" id="f_amount_min" type="number" step="0.01" min="0">
                </div>
                <div class="field">
                    <label>المبلغ إلى</label>
                    <input class="input" id="f_amount_max" type="number" step="0.01" min="0">
                </div>

                <div class="field">
                    <label>التاريخ من</label>
                    <input class="input" id="f_date_from" type="date">
                </div>
                <div class="field">
                    <label>التاريخ إلى</label>
                    <input class="input" id="f_date_to" type="date">
                </div>

                <div class="field">
                    <label>ترتيب حسب</label>
                    <select id="f_sort_by">
                        <option value="date">التاريخ</option>
                        <option value="amount">المبلغ</option>
                        <option value="type">النوع</option>
                        <option value="category">الفئة</option>
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
            <h3>تفاصيل السجل المالي</h3>
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
            <h3 id="formTitle">إضافة سجل</h3>

            <form id="recForm">
                <input type="hidden" id="recId">
                <div class="form-grid">
                    <div class="field">
                        <label>التاريخ</label>
                        <input type="date" id="date" required>
                    </div>

                    <div class="field">
                        <label>المبلغ</label>
                        <input type="number" id="amount" step="0.01" min="0" required>
                    </div>

                    <div class="field">
                        <label>الاتجاه</label>
                        <select id="direction" required>
                            <option value="دخل">دخل</option>
                            <option value="نفقات">نفقات</option>
                        </select>
                    </div>

                    <div class="field">
                        <label>الفئة</label>
                        <select id="category" required>
                            <option value="دعم زراعي">دعم زراعي</option>
                            <option value="أسمدة">أسمدة</option>
                            <option value="مبيدات">مبيدات</option>
                            <option value="بذور">بذور</option>
                            <option value="وقود">وقود</option>
                            <option value="مياه">مياه</option>
                            <option value="صيانة">صيانة</option>
                            <option value="معدات">معدات</option>
                            <option value="عمالة">عمالة</option>
                            <option value="أخرى">أخرى</option>
                        </select>
                    </div>

                    <div class="field" style="grid-column:1/-1">
                        <label>الوصف</label>
                        <textarea id="description" rows="2"></textarea>
                    </div>

                    <div class="field" style="grid-column:1/-1">
                        <label>رقم/مرجع العملية</label>
                        <input type="text" id="reference_number">
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
        function fmtDT(s) {
            if (!s) return '—';
            const d = new Date(s);
            if (isNaN(d)) return String(s);
            return d.toLocaleString();
        }

        const CREATED_KEY = 'financial_records_created_at';
        let createdAtMap = {};
        try {
            createdAtMap = JSON.parse(localStorage.getItem(CREATED_KEY) || '{}');
        } catch {}

        function saveCreatedMap() {
            localStorage.setItem(CREATED_KEY, JSON.stringify(createdAtMap));
        }

        (async function() {
            const token = document.querySelector('meta[name="csrf-token"]').content;

            async function fetchJSON(url, init = {}) {
                const baseHeaders = {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                };
                init.headers = {
                    ...(init.headers || {}),
                    ...baseHeaders,
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
                    "{{ asset('/background/fin_rec/ima1.jpg') }}",
                    "{{ asset('/background/fin_rec/ima2.jpg') }}",
                    "{{ asset('/background/fin_rec/ima3.jpg') }}",
                    "{{ asset('/background/fin_rec/ima4.jpg') }}",
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
            const form = document.getElementById('recForm');
            const formTitle = document.getElementById('formTitle');
            const formStatus = document.getElementById('formStatus');
            const btnOpenCreate = document.getElementById('btnOpenCreate');
            const btnSave = document.getElementById('btnSave');

            const f = {
                id: document.getElementById('recId'),
                date: document.getElementById('date'),
                amount: document.getElementById('amount'),
                direction: document.getElementById('direction'),
                category: document.getElementById('category'),
                description: document.getElementById('description'),
                reference_number: document.getElementById('reference_number'),
            };

            const flt = {
                type: document.getElementById('f_type'),
                category: document.getElementById('f_category'),
                payment_method: document.getElementById('f_payment_method'),
                description: document.getElementById('f_description'),
                notes: document.getElementById('f_notes'),
                reference: document.getElementById('f_reference'),
                amount_min: document.getElementById('f_amount_min'),
                amount_max: document.getElementById('f_amount_max'),
                date_from: document.getElementById('f_date_from'),
                date_to: document.getElementById('f_date_to'),
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

            document.querySelectorAll('[data-close]').forEach((btn) => {
                btn.addEventListener('click', () => closeModal(document.getElementById(btn.dataset.close)));
            });
            document.querySelectorAll('.modal .overlay').forEach((ov) => {
                ov.addEventListener('click', (e) => closeModal(e.target.closest('.modal')));
            });

            function renderRecords(items) {
                cards.innerHTML = '';
                items.forEach((it) => {
                    const card = document.createElement('div');
                    card.className = 'card';
                    card.dataset.id = it.id;
                    const amountTxt = it.amount != null ? Number(it.amount).toFixed(2) : '—';
                    card.innerHTML = `
          <div class="left">
            <div class="title">📅 ${it.date ?? '—'} — 💵 ${amountTxt}</div>
            <div class="row" style="margin:6px 0">
              <span class="badge">↔️ الاتجاه: ${it.direction ?? '—'}</span>
              <span class="badge">🗂️ الفئة: ${it.category ?? '—'}</span>
              <span class="badge">🔢 المرجع: ${it.reference_number ?? '—'}</span>
            </div>
            <div class="meta">${(it.description ?? '').toString().slice(0, 160) || '—'}${(it.description && it.description.length > 160) ? '…' : ''}</div>
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
                        .reference_number));
                    cards.appendChild(card);
                });
            }

            function openDetails(it) {
                detailsBody.innerHTML = '';

                const created = it.created_at || createdAtMap[it.id] || it.updated_at || null;

                const rows = [
                    ['التاريخ', it.date],
                    ['المبلغ', it.amount],
                    ['الاتجاه', it.direction],
                    ['الفئة', it.category],
                    ['الوصف', it.description],
                    ['مرجع/رقم', it.reference_number],
                    ['أضيف في', created ? fmtDT(created) : '—'],
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

            document.getElementById('btnOpenCreate').addEventListener('click', openCreate);

            function openCreate() {
                form.reset();
                f.id.value = '';
                formTitle.textContent = 'إضافة سجل';
                btnSave.textContent = 'حفظ';
                setStatus('', false, formStatus);
                openModal(modalForm);
            }

            function openEdit(it) {
                f.id.value = it.id;
                f.date.value = it.date ?? '';
                f.amount.value = it.amount ?? '';
                f.direction.value = it.direction ?? 'دخل';
                f.category.value = it.category ?? 'أخرى';
                f.description.value = it.description ?? '';
                f.reference_number.value = it.reference_number ?? '';
                formTitle.textContent = 'تعديل سجل';
                btnSave.textContent = 'تحديث';
                setStatus('', false, formStatus);
                openModal(modalForm);
            }

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const id = f.id.value ? Number(f.id.value) : null;
                const payload = {
                    date: f.date.value || null,
                    amount: f.amount.value !== '' ? Number(f.amount.value) : null,
                    direction: f.direction.value,
                    category: f.category.value,
                    description: f.description.value?.trim() || null,
                    reference_number: f.reference_number.value?.trim() || null,
                };

                try {
                    btnSave.disabled = true;
                    let url, method;
                    if (id) {
                        url = `/user/financialrecord/update-financialrecord/${id}`;
                        method = 'PUT';
                    } else {
                        url = `/user/financialrecord/add-financialrecord`;
                        method = 'POST';
                    }

                    const resp = await fetchJSON(url, {
                        method,
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(payload),
                    });

                    if (!id) {
                        const newId = resp?.data?.id ?? resp?.id;
                        if (newId) {
                            createdAtMap[newId] = new Date().toISOString();
                            saveCreatedMap();
                        }
                    }

                    setStatus(id ? 'تم تحديث السجل بنجاح' : 'تمت إضافة السجل بنجاح', true, formStatus);
                    await doFilter({
                        resetPage: true
                    });
                    setTimeout(() => closeModal(modalForm), 400);
                } catch (err) {
                    console.error(err);
                    const errs = err?.data?.errors ? Object.values(err.data.errors).flat().join(' | ') :
                        '';
                    setStatus(errs || err.message, false, formStatus);
                } finally {
                    btnSave.disabled = false;
                }
            });

            async function doDelete(id, ref) {
                const ok = confirm(`هل أنت متأكد من حذف السجل "${ref || '#' + id}"؟`);
                if (!ok) return;
                try {
                    setStatus('جاري الحذف…');
                    await fetchJSON(`/user/financialrecord/delete-financialrecord/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': token
                        },
                    });
                    setStatus('تم حذف السجل بنجاح', true);
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

                if (flt.type.value.trim()) p.set('type', flt.type.value.trim());
                if (flt.category.value.trim()) p.set('category', flt.category.value.trim());
                if (flt.payment_method.value.trim()) p.set('payment_method', flt.payment_method.value.trim());
                if (flt.description.value.trim()) p.set('description', flt.description.value.trim());
                if (flt.notes.value.trim()) p.set('notes', flt.notes.value.trim());
                if (flt.reference.value.trim()) p.set('reference_number', flt.reference.value.trim());

                if (flt.amount_min.value !== '') p.set('amount_min', flt.amount_min.value);
                if (flt.amount_max.value !== '') p.set('amount_max', flt.amount_max.value);
                if (flt.date_from.value) p.set('date_from', flt.date_from.value);
                if (flt.date_to.value) p.set('date_to', flt.date_to.value);

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
                    const url = `/user/financialrecord/filter-financialrecord?${params.toString()}`;
                    const data = await fetchJSON(url);
                    const items = data.data || [];
                    renderRecords(items);

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
                [...new Set(show)]
                .sort((a, b) => a - b)
                    .forEach((n) => {
                        mkBtn(String(n), false, () => {
                            state.page = n;
                            doFilter();
                        }, n === cur);
                    });

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

            [flt.sort_by, flt.sort_dir].forEach((el) =>
                el.addEventListener('change', () => doFilter({
                    resetPage: true
                })),
            );
            [
                flt.type,
                flt.category,
                flt.payment_method,
                flt.description,
                flt.notes,
                flt.reference,
                flt.amount_min,
                flt.amount_max,
                flt.date_from,
                flt.date_to,
                flt.per_page,
            ].forEach((el) => el.addEventListener('input', debouncedFilter));

            btnToggleFilters.addEventListener('click', () => {
                const st = filtersPanel.style.display || 'none';
                filtersPanel.style.display = st === 'none' ? 'block' : 'none';
            });
            btnFiltersApply.addEventListener('click', () => doFilter({
                resetPage: true
            }));
            btnFiltersReset.addEventListener('click', () => {
                Object.values(flt).forEach((el) => (el.value = el.id === 'f_per_page' ? '5' : ''));
                debouncedFilter();
            });

            await doFilter({
                resetPage: true
            });
        })();
    </script>

</body>

</html>
