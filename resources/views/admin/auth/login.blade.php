<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>تسجيل دخول الأدمن</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    :root{--bg:#0f172a;--card:#111827;--muted:#94a3b8;--accent:#22c55e;--danger:#ef4444;--text:#e5e7eb;}
    *{box-sizing:border-box;font-family:system-ui,Segoe UI,Roboto,Arial}
    body{margin:0;min-height:100vh;display:grid;place-items:center;background:linear-gradient(135deg,#0f172a,#111827);}
    .card{width:100%;max-width:420px;background:rgba(17,24,39,.9);border:1px solid #1f2937;border-radius:18px;padding:28px;box-shadow:0 10px 30px rgba(0,0,0,.35);backdrop-filter: blur(6px);}
    h1{margin:0 0 8px;color:var(--text);font-weight:700;font-size:24px;text-align:center}
    p{margin:0 0 20px;color:var(--muted);text-align:center}
    label{display:block;color:var(--text);font-size:14px;margin-bottom:6px}
    input[type="email"],input[type="password"]{
      width:100%;padding:12px 14px;border-radius:12px;border:1px solid #374151;
      background:#0b1220;color:var(--text);outline:none;transition:.2s;
    }
    input:focus{border-color:#3b82f6;box-shadow:0 0 0 4px rgba(59,130,246,.15)}
    .row{margin-bottom:14px}
    .flex{display:flex;align-items:center;justify-content:space-between}
    .btn{width:100%;padding:12px 14px;border:0;border-radius:12px;cursor:pointer;font-weight:700;font-size:15px;margin-top:10px}
    .btn-primary{background:linear-gradient(135deg,#22c55e,#16a34a);color:#08120a}
    .btn-primary:hover{filter:brightness(1.05)}
    .status,.error{margin-top:10px;font-size:14px;padding:10px;border-radius:10px}
    .status{background:#052e1a;color:#86efac;border:1px solid #14532d}
    .error{background:#2f0b0b;color:#fecaca;border:1px solid #7f1d1d}
    .remember{gap:8px;color:var(--muted);font-size:13px}
  </style>
</head>
<body>
  <div class="card">
    <h1>لوحة تحكم الأدمن</h1>
    <p>سجّل دخولك للوصول إلى الداشبورد</p>

    @if ($errors->any())
      <div class="error">
        @foreach ($errors->all() as $err)
          <div>• {{ $err }}</div>
        @endforeach
      </div>
    @endif

    @if (session('status'))
      <div class="status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login.post') }}" id="loginForm" novalidate>
      @csrf
      <div class="row">
        <label for="email">البريد الإلكتروني</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
      </div>
      <div class="row">
        <label for="password">كلمة المرور</label>
        <input type="password" id="password" name="password" required autocomplete="current-password" />
      </div>
      <div class="flex">
        <label class="remember">
          <input type="checkbox" name="remember" value="1"> تذكرني
        </label>
      </div>
      <button class="btn btn-primary" type="submit">تسجيل الدخول</button>
    </form>
  </div>

  <script>
    // تحسين بسيط: منع إرسال فورم فارغ
    document.getElementById('loginForm').addEventListener('submit', function(e){
      const email = document.getElementById('email').value.trim();
      const pass  = document.getElementById('password').value.trim();
      if(!email || !pass){
        e.preventDefault();
        alert('الرجاء إدخال البريد وكلمة المرور');
      }
    });
  </script>
</body>
</html>
