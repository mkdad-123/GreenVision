<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>لوحة التحكم</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    :root{
      --bg:#0a1a12; --bg2:#0e251a; --panel:#0f1f17; --card:#10231a;
      --text:#ecfdf5; --muted:#a7f3d0; --accent:#22c55e; --accent-2:#16a34a; --stroke:#1f3b2d;
    }
    *{box-sizing:border-box;font-family:system-ui,Segoe UI,Roboto,Arial}
    body{
      margin:0;color:var(--text);
      background: radial-gradient(1200px 800px at 20% -10%, #12402c 0%, var(--bg) 60%),
                  radial-gradient(900px 600px at 120% 20%, #0e2a1d 0%, var(--bg2) 70%),
                  var(--bg);
      display:grid;grid-template-columns:280px 1fr;min-height:100vh;overflow-x:hidden;
    }
    .leaves{position:fixed; inset:0; pointer-events:none; opacity:.12; z-index:0;
      background:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="240" height="240"><g fill="%23a7f3d0" fill-opacity=".35"><path d="M120 10c35 40 35 85 0 130-35-45-35-90 0-130z"/><circle cx="45" cy="180" r="18"/><circle cx="180" cy="60" r="14"/></g></svg>') repeat;
      filter: blur(1px);
    }
    aside{position:sticky; top:0; height:100vh; z-index:3;
      background:linear-gradient(180deg, rgba(16,35,26,.95), rgba(10,26,18,.9));
      border-inline-end:1px solid var(--stroke); padding:20px 18px; backdrop-filter: blur(6px);
    }
    .brand{font-weight:900;font-size:22px;margin-bottom:18px;display:flex;align-items:center;gap:8px}
    .brand .logo{width:28px;height:28px;border-radius:8px;
      background:linear-gradient(135deg,var(--accent),var(--accent-2));
      box-shadow:0 6px 18px rgba(34,197,94,.35);
    }
    nav a{display:flex;align-items:center;gap:10px; padding:10px 12px;margin-bottom:8px;
      text-decoration:none;color:var(--text); border:1px solid transparent;border-radius:12px;transition:.2s}
    nav a:hover{background:#0c1d15;border-color:var(--stroke)}
    header{display:flex;justify-content:space-between;align-items:center;gap:12px;
      padding:16px 18px;border-bottom:1px solid var(--stroke);
      background:linear-gradient(135deg,rgba(16,35,26,.7),rgba(12,29,21,.7)); position:sticky;top:0;z-index:4; backdrop-filter: blur(6px);}
    .logout{background:linear-gradient(135deg,#0f172a,#0b1220); border:1px solid #1f2937;color:#e5e7eb;padding:8px 12px;border-radius:10px;cursor:pointer}
    .page{padding:18px;position:relative;z-index:2}
    .grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px}
    .card{background:linear-gradient(180deg,rgba(16,35,26,.85),rgba(12,29,21,.85));
      border:1px solid var(--stroke);border-radius:18px;padding:16px;position:relative;overflow:hidden}
    .card::after{content:""; position:absolute; inset:auto -30% -30% auto; width:120px; height:120px; border-radius:50%;
      background: radial-gradient(closest-side, rgba(34,197,94,.25), transparent); transform: rotate(25deg);}
    .card h3{margin:0 0 8px;font-size:15px;color:#d1fae5}
    .num{font-size:28px;font-weight:800}
    .btn{appearance:none; border:0; cursor:pointer; border-radius:12px; padding:10px 14px; font-weight:700}
    .btn-accent{background:linear-gradient(135deg,var(--accent),var(--accent-2)); color:#052e1a; box-shadow:0 8px 22px rgba(34,197,94,.25)}
    .table{width:100%; border-collapse:separate; border-spacing:0; overflow:hidden; border-radius:14px; background:rgba(16,35,26,.75); border:1px solid var(--stroke)}
    .table th, .table td{padding:12px 14px; text-align:right; border-bottom:1px solid var(--stroke); color:#d1fae5}
    .table th{background:rgba(20,45,34,.85); font-weight:800; letter-spacing:.2px}
    .table tr:hover td{background:rgba(13,31,23,.7)}
    .tools{display:flex;gap:10px;align-items:center;flex-wrap:wrap; margin-bottom:12px}
    .search{flex:1 1 240px; background:rgba(16,35,26,.85); border:1px solid var(--stroke); border-radius:12px; display:flex; align-items:center; gap:8px; padding:8px 12px}
    .search input{flex:1; background:transparent; border:0; color:var(--text); outline:none}
    @media(max-width:1200px){.grid{grid-template-columns:repeat(2,1fr)}}
    @media(max-width:800px){body{grid-template-columns:1fr} aside{position:static;height:auto}}
    .fade-in{animation:fade .5s ease-out both}
    @keyframes fade{from{opacity:0; transform:translateY(6px)} to{opacity:1; transform:none}}
    .modal-backdrop{position:fixed; inset:0; background:rgba(0,0,0,.45); display:none; align-items:center; justify-content:center; z-index:50}
    .modal{width:min(100%, 920px); max-height:85vh; overflow:auto; border-radius:16px; border:1px solid var(--stroke); background:linear-gradient(180deg,rgba(16,35,26,.98),rgba(10,26,18,.98)); padding:16px}
    .modal header{position:sticky; top:0; border:0; padding:0 0 12px; background:transparent}
    .close{background:transparent; border:1px solid var(--stroke); color:var(--text); padding:6px 10px; border-radius:10px; cursor:pointer}
  </style>
</head>
<body>
  <div class="leaves" aria-hidden="true"></div>

  <aside>
    <div class="brand"><span class="logo"></span> لوحة الأدمن</div>
    <nav>
      <a href="{{ route('admin.dashboard') }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M3 11l9-8 9 8v9a2 2 0 0 1-2 2h-3v-6H8v6H5a2 2 0 0 1-2-2v-9z" stroke="#86efac" stroke-width="1.5"/></svg>
        الصفحة الرئيسية
      </a>
      <a href="#users">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="#86efac" stroke-width="1.5"/></svg>
        المستخدمون
      </a>
      <a id="openComplaintsBtn" href="javascript:void(0)">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M21 15a4 4 0 0 1-4 4H7l-4 4V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v8z" stroke="#86efac" stroke-width="1.5"/></svg>
        الشكاوى
      </a>
      <a href="#">الإعدادات</a>
    </nav>
  </aside>

  <main>
    <header>
      <div>مرحباً، {{ auth('admin')->user()->name ?? 'أدمن' }}</div>
      <form id="logoutForm" method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button class="logout" type="submit">تسجيل الخروج</button>
      </form>
    </header>

    <div class="page">
      <!-- بطاقات ملخص: المستخدمون + الشكاوى + التاريخ + الوقت -->
      <div class="grid fade-in" style="margin-bottom:16px">
        <div class="card"><h3>عدد المستخدمين</h3><div class="num" id="usersCount">--</div></div>
        <div class="card"><h3>عدد الشكاوى</h3><div class="num" id="tickets">--</div></div>
        <div class="card"><h3>التاريخ</h3><div class="num" id="todayDate">--</div></div>
        <div class="card"><h3>الوقت الآن</h3><div class="num" id="nowTime">--</div></div>
      </div>

      <!-- أدوات -->
      <div class="tools">
        <div class="search">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M21 21l-4.35-4.35M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0z" stroke="#86efac" stroke-width="1.5"/></svg>
          <input id="searchInput" placeholder="ابحث في المستخدمين بالاسم أو البريد…">
        </div>
        <button class="btn btn-accent" id="refreshUsers">تحديث القائمة</button>
        <button class="btn btn-accent" id="openComplaintsBtn2">عرض الشكاوى</button>
      </div>

      <!-- جدول المستخدمين -->
      <div class="card fade-in">
        <h3 style="margin-bottom:10px">قائمة المستخدمين</h3>
        <div style="overflow:auto">
          <table class="table" id="usersTable">
            <thead>
              <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>البريد</th>
                <th>تاريخ الإنشاء</th>
              </tr>
            </thead>
            <tbody>
              <tr><td colspan="4" style="text-align:center;color:var(--muted)">جاري التحميل…</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- لمحة -->
   
    </div>
  </main>

  <!-- مودال الشكاوى -->
  <div class="modal-backdrop" id="complaintsModal">
    <div class="modal">
      <header style="display:flex;justify-content:space-between;align-items:center">
        <h3 style="margin:0">قائمة الشكاوى</h3>
        <button class="close" id="closeComplaints">إغلاق</button>
      </header>
      <div style="margin-top:10px; overflow:auto">
        <table class="table" id="complaintsTable">
          <thead>
            <tr>
              <th>#</th>
              <th>ID الشكوى</th>
              <th>معرّف المشتكي (user_id)</th>
              <th>العنوان</th>
              <th>الوصف</th>
              <th>تاريخ الإنشاء</th>
            </tr>
          </thead>
          <tbody>
            <tr><td colspan="6" style="text-align:center;color:var(--muted)">جاري التحميل…</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // DOM
    const usersCountEl = document.getElementById('usersCount');
    const ticketsEl    = document.getElementById('tickets');
    const todayDateEl  = document.getElementById('todayDate');
    const nowTimeEl    = document.getElementById('nowTime');

    const usersTable   = document.getElementById('usersTable').querySelector('tbody');
    const complaintsTable = document.getElementById('complaintsTable').querySelector('tbody');
    const searchInput  = document.getElementById('searchInput');
    const refreshBtn   = document.getElementById('refreshUsers');
    const modal        = document.getElementById('complaintsModal');
    const openC1       = document.getElementById('openComplaintsBtn');
    const openC2       = document.getElementById('openComplaintsBtn2');
    const closeC       = document.getElementById('closeComplaints');

    let usersRaw = [];

    // تحديث التاريخ والوقت (من جهاز المستخدم)
    function updateClock(){
      const now = new Date();
      // تاريخ بشكل بسيط: YYYY-MM-DD
      const yyyy = now.getFullYear();
      const mm = String(now.getMonth()+1).padStart(2,'0');
      const dd = String(now.getDate()).padStart(2,'0');
      todayDateEl.textContent = `${yyyy}-${mm}-${dd}`;

      // وقت: HH:MM:SS
      const hh = String(now.getHours()).padStart(2,'0');
      const mi = String(now.getMinutes()).padStart(2,'0');
      const ss = String(now.getSeconds()).padStart(2,'0');
      nowTimeEl.textContent = `${hh}:${mi}:${ss}`;
    }
    updateClock();
    setInterval(updateClock, 1000);

    // جلب المستخدمين
    async function loadUsers() {
      try {
        usersTable.innerHTML = `<tr><td colspan="4" style="text-align:center;color:var(--muted)">جاري التحميل…</td></tr>`;
        const res = await fetch(`{{ route('admin.users.index') }}`, { headers: { 'Accept': 'application/json' }});
        if (!res.ok) throw new Error('فشل في جلب البيانات');
        const data = await res.json();
        const list = Array.isArray(data.data) ? data.data : [];
        usersRaw = list;
        usersCountEl.textContent = list.length;
        renderUsers(list);
      } catch (e) {
        usersTable.innerHTML = `<tr><td colspan="4" style="text-align:center;color:#fecaca;background:#2f0b0b;border:1px solid #7f1d1d">حدث خطأ أثناء التحميل</td></tr>`;
      }
    }

    // رسم جدول المستخدمين مع البحث
    function renderUsers(list){
      const q = (searchInput.value || '').toLowerCase();
      const filtered = list.filter(u => {
        const name  = (u.name || '').toLowerCase();
        const email = (u.email || '').toLowerCase();
        return name.includes(q) || email.includes(q);
      });
      if (!filtered.length) {
        usersTable.innerHTML = `<tr><td colspan="4" style="text-align:center;color:var(--muted)">لا توجد نتائج</td></tr>`;
        return;
      }
      usersTable.innerHTML = filtered.map((u, i) => {
        const created = (u.created_at || '').toString().replace('T',' ').slice(0,19);
        return `<tr>
          <td>${i+1}</td>
          <td>${escapeHtml(u.name ?? '')}</td>
          <td>${escapeHtml(u.email ?? '')}</td>
          <td>${escapeHtml(created)}</td>
        </tr>`;
      }).join('');
    }

    // جلب الشكاوى — يعرض id و user_id
    async function loadComplaints(){
      complaintsTable.innerHTML = `<tr><td colspan="6" style="text-align:center;color:var(--muted)">جاري التحميل…</td></tr>`;
      try {
        const res = await fetch(`{{ route('admin.complaints.index') }}`, { headers: { 'Accept': 'application/json' }});
        if (!res.ok) throw new Error('فشل في جلب الشكاوى');
        const data = await res.json();

        // الكود يدعم حالتين:
        // 1) { data: [ {...}, {...} ] }  (قائمة)
        // 2) { data: { ... } }           (عنصر واحد)
        const list = Array.isArray(data.data) ? data.data : (data.data ? [data.data] : []);
        ticketsEl.textContent = list.length;

        if (!list.length) {
          complaintsTable.innerHTML = `<tr><td colspan="6" style="text-align:center;color:var(--muted)">لا توجد شكاوى</td></tr>`;
          return;
        }

        complaintsTable.innerHTML = list.map((c, i) => {
          const created = (c.created_at || '').toString().replace('T',' ').slice(0,19);
          return `<tr>
            <td>${i+1}</td>
            <td>${escapeHtml(c.id ?? '')}</td>
            <td>${escapeHtml(c.user_id ?? '')}</td>
            <td>${escapeHtml(c.title ?? '')}</td>
            <td>${escapeHtml(c.description ?? '')}</td>
            <td>${escapeHtml(created)}</td>
          </tr>`;
        }).join('');
      } catch (e) {
        complaintsTable.innerHTML = `<tr><td colspan="6" style="text-align:center;color:#fecaca;background:#2f0b0b;border:1px solid #7f1d1d">حدث خطأ أثناء تحميل الشكاوى</td></tr>`;
      }
    }

    // أدوات
    function escapeHtml(s){ return (s+'').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])) }

    // أحداث
    searchInput.addEventListener('input', () => renderUsers(usersRaw));
    refreshBtn.addEventListener('click', loadUsers);

    function openComplaints(){ modal.style.display='flex'; loadComplaints(); }
    function closeComplaints(){ modal.style.display='none'; }
    document.getElementById('openComplaintsBtn').addEventListener('click', openComplaints);
    document.getElementById('openComplaintsBtn2').addEventListener('click', openComplaints);
    document.getElementById('closeComplaints').addEventListener('click', closeComplaints);
    modal.addEventListener('click', (e)=>{ if(e.target===modal) closeComplaints(); });

    // عند الدخول
    loadUsers();
  </script>
</body>
</html>
