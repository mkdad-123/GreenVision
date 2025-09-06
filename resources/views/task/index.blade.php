<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>مهامي — إدارة المهام</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="preload" as="image" href="{{ asset('/background/task/ima1.webp') }}" fetchpriority="high">
    <link rel="preload" as="image" href="{{ asset('/background/task/ima2.webp') }}">
    @vite(['resources/css/task.css'])

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
            <h1>مهامي</h1>
            <div class="row">
                <button class="btn" id="btnOpenCreate">➕ إضافة مهمة</button>
            </div>
        </header>

        <div class="toolbar">
            <div class="search">
                <input class="input" id="qGlobal" type="search" placeholder="بحث عام (النوع، الوصف، التكرار…)">
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
                    <label>النوع</label>
                    <input class="input" id="f_type" type="text" placeholder="حراثة، ري، حصاد…">
                </div>
                <div class="field">
                    <label>الحالة</label>
                    <select id="f_status">
                        <option value="">—</option>
                        <option value="قيد التنفيذ">قيد التنفيذ</option>
                        <option value="منجزة">منجزة</option>
                        <option value="مؤجلة">مؤجلة</option>
                    </select>
                </div>
                <div class="field">
                    <label>الأولوية</label>
                    <select id="f_priority">
                        <option value="">—</option>
                        <option value="عالية">عالية</option>
                        <option value="متوسطة">متوسطة</option>
                        <option value="منخفضة">منخفضة</option>
                    </select>
                </div>
                <div class="field">
                    <label>من تاريخ</label>
                    <input class="input" id="f_date_from" type="date">
                </div>
                <div class="field">
                    <label>إلى تاريخ</label>
                    <input class="input" id="f_date_to" type="date">
                </div>
                <div class="field">
                    <label>تكرار</label>
                    <select id="f_repeat" class="input">
                        <option value="">—</option>
                        <option>يومي</option>
                        <option>يومين</option>
                        <option>ثلاث ايام</option>
                        <option>اربع ايام</option>
                        <option>خمس ايام</option>
                        <option>اسبوعي</option>
                        <option>شهري</option>
                        <option>سنوي</option>
                    </select>
                </div>
                <div class="field">
                    <label>ترتيب حسب</label>
                    <select id="f_sort_by">
                        <option value="date">التاريخ</option>
                        <option value="priority">الأولوية</option>
                        <option value="status">الحالة</option>
                        <option value="type">النوع</option>
                        <option value="id">ID</option>
                        <option value="updated_at">آخر تعديل</option>
                        <option value="created_at">تاريخ الإنشاء</option>
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
                    <input class="input" id="f_per_page" type="number" min="1" value="7">
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
            <h3>تفاصيل المهمة</h3>
            <div class="kv-grid" id="detailsBody"></div>
            <div class="footer">
                <button class="btn btn-ghost" data-close="modalDetails">إغلاق</button>
            </div>
        </div>
    </div>

    <div class="modal" id="modalForm" role="dialog" aria-modal="true">
        <div class="overlay"></div>
        <div class="sheet">
            <button class="btn btn-ghost close" data-close="modalForm">✕</button>
            <h3 id="formTitle">إضافة مهمة</h3>

            <form id="taskForm">
                <input type="hidden" id="taskId">
                <div class="form-grid">
                    <div class="field">
                        <label>المزرعة</label>
                        <select id="farm_id" required>
                            <option value="">—</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>النوع</label>
                        <input type="text" id="type" required>
                    </div>
                    <div class="field">
                        <label>التاريخ</label>
                        <input type="date" id="date" required>
                    </div>
                    <div class="field">
                        <div class="field">
                            <label for="repeat_interval">تكرار</label>
                            <select id="repeat_interval" class="input">
                                <option value="">تكرار</option>
                                <option>يومي</option>
                                <option>يومين</option>
                                <option>ثلاث ايام</option>
                                <option>اربع ايام</option>
                                <option>خمس ايام</option>
                                <option>اسبوعي</option>
                                <option>شهري</option>
                                <option>سنوي</option>
                            </select>
                        </div>


                    </div>
                    <div class="field">
                        <label>الحالة</label>
                        <select id="status" required>
                            <option value="قيد التنفيذ" selected>قيد التنفيذ</option>
                            <option value="منجزة">منجزة</option>
                            <option value="مؤجلة">مؤجلة</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>الأولوية</label>
                        <select id="priority">
                            <option value="">— (افتراضي: متوسطة)</option>
                            <option value="عالية">عالية</option>
                            <option value="متوسطة">متوسطة</option>
                            <option value="منخفضة">منخفضة</option>
                        </select>
                    </div>
                    <div class="field" style="grid-column:1/-1">
                        <label>الوصف</label>
                        <textarea id="description" rows="3" placeholder="اختياري"></textarea>
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
            const debounce = (fn, d = 350) => {
                let t;
                return (...a) => {
                    clearTimeout(t);
                    t = setTimeout(() => fn(...a), d)
                }
            }

            (function() {
                const imgs = [
                    "{{ asset('/background/task/ima1.jpg') }}",
                    "{{ asset('/background/task/ima2.jpg') }}",
                    "{{ asset('/background/task/ima3.jpg') }}",
                    "{{ asset('/background/task/ima4.jpg') }}"
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
            const form = document.getElementById('taskForm');
            const formTitle = document.getElementById('formTitle');
            const formStatus = document.getElementById('formStatus');
            const btnOpenCreate = document.getElementById('btnOpenCreate');
            const btnSave = document.getElementById('btnSave');

            const f = {
                id: document.getElementById('taskId'),
                farm_id: document.getElementById('farm_id'),
                type: document.getElementById('type'),
                description: document.getElementById('description'),
                date: document.getElementById('date'),
                repeat_interval: document.getElementById('repeat_interval'),
                status: document.getElementById('status'),
                priority: document.getElementById('priority'),
            };

            const flt = {
                farm_id: document.getElementById('f_farm_id'),
                type: document.getElementById('f_type'),
                status: document.getElementById('f_status'),
                priority: document.getElementById('f_priority'),
                date_from: document.getElementById('f_date_from'),
                date_to: document.getElementById('f_date_to'),
                repeat_interval: document.getElementById('f_repeat'),
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
            document.querySelectorAll('[data-close]').forEach(btn => btn.addEventListener('click', () => closeModal(
                document.getElementById(btn.dataset.close))));
            document.querySelectorAll('.modal .overlay').forEach(ov => ov.addEventListener('click', e => closeModal(
                e.target.closest('.modal'))));

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
                            opt.textContent = (it && it.name) ? it.name : ('#' + it.id);
                            sel.appendChild(opt);
                        });
                        if (cur) sel.value = cur;
                    });
                } catch (e) {
                    console.warn('Failed to load farms', e);
                }
            }

            function fmtDate(d) {
                if (!d) return '—';
                const t = new Date(d);
                return isNaN(t) ? d : t.toISOString().slice(0, 10);
            }

            function fmtDT(s) {
                if (!s) return '—';
                let txt = String(s).trim();

                let d = new Date(txt);
                if (isNaN(d)) d = new Date(txt.replace(' ', 'T'));
                if (isNaN(d)) d = new Date(txt + 'Z');
                if (isNaN(d)) return txt;

                const yyyy = d.getFullYear();
                const mm = String(d.getMonth() + 1).padStart(2, '0');
                const dd = String(d.getDate()).padStart(2, '0');
                const hh = String(d.getHours()).padStart(2, '0');
                const mi = String(d.getMinutes()).padStart(2, '0');
                return `${hh}:${mi} ${yyyy}-${mm}-${dd}`;
            }



            function renderTasks(items) {
                const rank = {
                    'عالية': 1,
                    'متوسطة': 2,
                    'منخفضة': 3
                };
                items.sort((a, b) => (rank[a.priority] ?? 9) - (rank[b.priority] ?? 9));

                cards.innerHTML = '';
                items.forEach(it => {
                    const farmLabel = (it && it.farm && it.farm.name) ? it.farm.name : ((it && it.farm_id) ?
                        it.farm_id : '—');
                    const desc = (it && it.description) ? String(it.description) : '';
                    const card = document.createElement('div');
                    card.className = 'card';
                    card.dataset.id = it.id;
                    card.innerHTML = `
                <div class="left">
                    <!-- العنوان: نوع المهمة فقط -->
                    <div class="title">🧩 ${it.type || '—'}</div>

                    <!-- صف بادجات أول -->
                    <div class="row" style="margin:6px 0">
                    <span class="badge">🏡 المزرعة: ${farmLabel}</span>
                    <span class="badge">⏱️ التكرار: ${(it && it.repeat_interval) ? it.repeat_interval : '—'}</span>
                    <span class="badge">📅 التاريخ: ${fmtDate(it.date)}</span>
                    </div>

                    <!-- صف بادجات ثاني -->
                    <div class="row">
                    <span class="badge">⚑ الحالة: ${it.status || '—'}</span>
                    <span class="badge">⭐ الأولوية: ${it.priority || '—'}</span>
                    </div>

                    <!-- الوصف -->
                    <div class="meta" style="margin-top:6px">
                    ${desc ? (desc.slice(0,160) + (desc.length>160 ? '…' : '')) : '—'}
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
                        .type));
                    cards.appendChild(card);
                });
            }

            function openDetails(it) {
                detailsBody.innerHTML = '';

                const farmLabel = (it && it.farm && it.farm.name) ?
                    it.farm.name :
                    ((it && it.farm_id) ? it.farm_id : '—');

                const rows = [
                    ['الاسم', it.type],
                    ['المزرعة', farmLabel],
                    ['التاريخ', fmtDate(it.date)],
                    ['التكرار', it.repeat_interval],
                    ['الحالة', it.status],
                    ['الأولوية', it.priority],
                    ['الوصف', it.description || '—'],
                    ['أضيفت في', fmtDT(it.created_at)],
                    ['آخر تعديل', fmtDT(it.updated_at)],
                ];

                const frag = document.createDocumentFragment();
                rows.forEach(([label, value]) => {
                    const box = document.createElement('div');
                    box.className = 'kv';
                    box.innerHTML = `
      <div class="kvt">${label}</div>
      <div class="kvv">${(value!=null && value!=='') ? value : '—'}</div>
    `;
                    frag.appendChild(box);
                });

                detailsBody.appendChild(frag);
                openModal(modalDetails);
            }

            btnOpenCreate.addEventListener('click', openCreate);

            function openCreate() {
                form.reset();
                f.id.value = '';
                f.status.value = 'قيد التنفيذ';
                f.priority.value = '';
                formTitle.textContent = 'إضافة مهمة';
                btnSave.textContent = 'حفظ';
                setStatus('', false, formStatus);
                openModal(modalForm);
            }


            function openEdit(it) {
                f.id.value = it.id;
                f.farm_id.value = it.farm_id || '';
                f.type.value = it.type || '';
                f.date.value = it.date || '';
                f.repeat_interval.value = it.repeat_interval || '';
                f.status.value = it.status || 'قيد التنفيذ';
                f.priority.value = it.priority || '';
                f.description.value = it.description || '';

                formTitle.textContent = 'تعديل مهمة';
                btnSave.textContent = 'تحديث';
                setStatus('', false, formStatus);
                openModal(modalForm);
            }


            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const id = f.id.value ? Number(f.id.value) : null;

                const payload = {
                    farm_id: f.farm_id.value ? Number(f.farm_id.value) : null,
                    type: (f.type.value || '').trim(),
                    date: f.date.value || null,
                    status: f.status.value || 'قيد التنفيذ',
                };

                const desc = (f.description.value || '').trim();
                if (desc) payload.description = desc;

                const rep = (f.repeat_interval.value || '').trim();
                if (rep) payload.repeat_interval = rep;

                if (f.priority.value) payload.priority = f.priority.value;

                try {
                    btnSave.disabled = true;
                    let url, method;
                    if (id) {
                        url = `/user/task/update-task/${id}`;
                        method = 'PUT';
                    } else {
                        url = `/user/task/add-task`;
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

                    setStatus(id ? 'تم تحديث المهمة بنجاح' : 'تم إضافة المهمة بنجاح', true, formStatus);
                    await doFilter({
                        resetPage: true
                    });
                    setTimeout(() => closeModal(modalForm), 400);
                } catch (err) {
                    console.error(err);
                    const errs = (err && err.data && err.data.errors) ? Object.values(err.data.errors)
                        .flat().join(' | ') : '';
                    setStatus((errs || err.message), false, formStatus);
                } finally {
                    btnSave.disabled = false;
                }
            });


            async function doDelete(id, name) {
                const ok = confirm(`هل أنت متأكد من حذف المهمة "${name || '#'+id}"؟`);
                if (!ok) return;
                try {
                    setStatus('جاري الحذف…');
                    await fetchJSON(`/user/task/delete-task/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': token
                        }
                    });
                    setStatus('تم حذف المهمة بنجاح', true);
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
                if (flt.type.value.trim()) p.set('type', flt.type.value.trim());
                if (flt.status.value) p.set('status', flt.status.value);
                if (flt.priority.value) p.set('priority', flt.priority.value);
                if (flt.date_from.value) p.set('date_from', flt.date_from.value);
                if (flt.date_to.value) p.set('date_to', flt.date_to.value);
                if (flt.repeat_interval.value.trim()) p.set('repeat_interval', flt.repeat_interval.value.trim());
                if (flt.sort_by.value) p.set('sort_by', flt.sort_by.value);
                if (flt.sort_dir.value) p.set('sort_dir', flt.sort_dir.value);
                const per = Number(flt.per_page.value) || 7;
                p.set('per_page', per);
                p.set('page', state.page);
                return p;
            }

            async function doFilter(opts = {}) {
                if (opts.resetPage) state.page = 1;
                setStatus('جاري التحميل…');
                try {
                    const params = buildParams();
                    const url = `/user/task/filter-tasks?${params.toString()}`;
                    const data = await fetchJSON(url);
                    const items = data.data || [];
                    renderTasks(items);
                    const meta = data.meta || {};
                    state.total = (typeof meta.total === 'number') ? meta.total : items.length;
                    state.last_page = (typeof meta.last_page === 'number') ? meta.last_page : 1;
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
                const lp = Number(state.last_page) || 1,
                    cur = Number(state.page) || 1;
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
                [...new Set(show)].sort((a, b) => a - b).forEach(n => mkBtn(String(n), false, () => {
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
            [flt.farm_id, flt.sort_by, flt.sort_dir].forEach(el => el.addEventListener('change', () => doFilter({
                resetPage: true
            })));
            [flt.type, flt.status, flt.priority, flt.date_from, flt.date_to, flt.repeat_interval, flt.per_page]
            .forEach(el => el.addEventListener('input', debouncedFilter));

            btnToggleFilters.addEventListener('click', () => {
                const st = (filtersPanel.style.display || 'none');
                filtersPanel.style.display = (st === 'none') ? 'block' : 'none';
            });
            btnFiltersApply.addEventListener('click', () => doFilter({
                resetPage: true
            }));
            btnFiltersReset.addEventListener('click', () => {
                Object.values(flt).forEach(el => el.value = (el.id === 'f_per_page' ? '7' : ''));
            });

            try {
                await loadFarmsOptions();
            } catch (e) {
            }
            await doFilter({
                resetPage: true
            });
        })();
    </script>
</body>

</html>
