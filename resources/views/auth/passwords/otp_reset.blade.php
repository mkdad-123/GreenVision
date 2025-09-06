<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>إعادة تعيين كلمة المرور</title>
<style>
    :root{
        --green-700:#2e7d32;
        --green-600:#388e3c;
        --green-500:#43a047;
        --green-400:#66bb6a;
        --green-300:#81c784;
        --beige-50:#faf7f0;
        --beige-100:#f2ecdf;
        --beige-200:#e8e0cf;
        --brown-300:#a08669;
        --text:#20301f;
        --danger:#b00020;
        --success:#1b5e20;
        --shadow:0 10px 25px rgba(0,0,0,.08), 0 2px 8px rgba(0,0,0,.06);
        --radius:16px;
    }

    *{box-sizing:border-box}
    body{
        margin:0;
        font-family: "Tajawal", Arial, sans-serif;
        color:var(--text);
        background:
          radial-gradient(1200px 600px at 80% -10%, rgba(102,187,106,.15), transparent 60%),
          radial-gradient(900px 500px at 10% 110%, rgba(67,160,71,.12), transparent 60%),
          linear-gradient(180deg, var(--beige-50), var(--beige-100));
        min-height:100vh;
        display:flex; align-items:center; justify-content:center;
        padding:24px;
    }

    .wrap{
        width:100%; max-width: 440px;
        position:relative;
        animation: fadeIn .6s ease-out both;
    }

    .card{
        background: #fff;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow:hidden;
        transform: translateY(8px);
        animation: rise .7s .15s cubic-bezier(.2,.8,.2,1) both;
    }

    .card-header{
        position:relative;
        padding: 28px 24px 20px;
        color:#fff;
        background:
            radial-gradient(1000px 300px at 80% -100%, rgba(255,255,255,.15), transparent 60%),
            linear-gradient(135deg, var(--green-600), var(--green-500));
    }

    .brand{
        display:flex; align-items:center; gap:12px;
    }

    .leaf{
        width:36px; height:36px; flex:0 0 36px;
        filter: drop-shadow(0 2px 6px rgba(0,0,0,.2));
        transform-origin: bottom center;
        animation: leafSway 3.2s ease-in-out infinite;
    }

    .title{
        margin:0; font-size:20px; font-weight:700; letter-spacing:.2px;
    }
    .subtitle{
        margin:6px 0 0; font-size:13px; opacity:.9;
    }

    .card-body{
        padding: 22px 22px 20px;
        background: #fff;
    }

    .alert{
        margin:0 0 14px; padding:12px 14px;
        border-radius:12px; font-size:14px; line-height:1.5;
        border:1px solid transparent;
        display:flex; gap:10px; align-items:flex-start;
        animation: fadeIn .4s ease-out both;
    }
    .alert-success{
        background: #eaf6ec; border-color:#cfead3; color: var(--success);
    }
    .alert-error{
        background: #fdeceb; border-color:#f6c9c7; color: var(--danger);
    }
    .alert ul{margin:6px 0 0 0; padding-inline-start:18px}

    .form-group{ margin-bottom:14px; }
    label{
        display:block; margin-bottom:7px; font-size:14px; color:#3c513a; font-weight:600;
    }
    .hint{ font-size:12px; color:#6d7b6a; margin-top:6px }

    input{
        width:100%; padding:13px 12px;
        border:1px solid #e2e8e0; border-radius:12px;
        font-size:14px; background:#fff;
        transition: border-color .2s, box-shadow .2s, transform .08s;
        outline:none;
    }
    input:focus{
        border-color: var(--green-400);
        box-shadow: 0 0 0 4px rgba(102,187,106,.15);
    }
    input[type="text"][name="code"]{
        letter-spacing: .25em;
        text-align:center; font-weight:700;
    }

    .actions{
        margin-top:14px; display:flex; flex-direction:column; gap:10px;
    }
    .btn{
        appearance:none; border:none; cursor:pointer;
        border-radius: 12px;
        padding: 13px 14px;
        font-weight:700; font-size:15px;
        transition: transform .06s ease, box-shadow .2s ease, background .2s ease;
    }
    .btn-primary{
        color:#fff;
        background: linear-gradient(135deg, var(--green-600), var(--green-500));
        box-shadow: 0 8px 20px rgba(67,160,71,.28);
    }
    .btn-primary:hover{ filter:brightness(1.02) }
    .btn-primary:active{ transform: translateY(1px) }

    .btn-ghost{
        background: transparent; color: var(--green-600);
    }
    .btn-ghost:hover{ background: #f2f6f2 }

    .footer{
        padding: 12px 22px 20px;
        display:flex; justify-content:space-between; align-items:center;
        font-size:12px; color:#6d7b6a;
    }

    .divider{
        height:1px; background:linear-gradient(90deg, transparent, #e8efe6, transparent);
        margin: 12px 0;
    }

    /* Animations */
    @keyframes fadeIn{ from{opacity:0} to{opacity:1} }
    @keyframes rise{ from{ transform: translateY(12px); opacity:.0 } to{ transform: translateY(0); opacity:1 } }
    @keyframes leafSway{
        0%,100%{ transform: rotate(0deg) }
        50%{ transform: rotate(-6deg) }
    }
</style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <div class="card-header">
            <div class="brand">
                <!-- أيقونة ورقة (SVG) -->
                <svg class="leaf" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                    <defs>
                        <linearGradient id="lg" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#a5d6a7"/>
                            <stop offset="100%" stop-color="#4caf50"/>
                        </linearGradient>
                    </defs>
                    <path d="M54 10C32 10 10 22 10 42c0 6 2 10 6 12 2-8 9-15 20-20-6 6-10 12-12 18 10 2 18-1 24-7 8-8 8-21 6-35Z" fill="url(#lg)"/>
                    <path d="M28 40c6-6 14-11 24-14" stroke="rgba(255,255,255,.8)" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <div>
                    <h1 class="title">إعادة تعيين كلمة المرور</h1>
                    <p class="subtitle">أدخل الرمز (OTP) الذي وصلك عبر البريد ثم عيّن كلمة مرور جديدة.</p>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">
                    <div>✔</div>
                    <div>{{ session('status') }}</div>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <div>⚠</div>
                    <div>
                        <strong>حدثت بعض الأخطاء:</strong>
                        <ul>
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('password.otp.reset.web') }}" novalidate>
                @csrf

                <div class="form-group">
                    <label for="email">البريد الإلكتروني</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required>
                    <div class="hint">استخدم نفس البريد الذي استلمت عليه الرمز.</div>
                </div>

                <div class="form-group">
                    <label for="code">رمز التحقق (6 أرقام)</label>
                    <input id="code" type="text" name="code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" value="{{ old('code') }}" placeholder="— — — — — —" required>
                </div>

                <div class="form-group">
                    <label for="password">كلمة المرور الجديدة</label>
                    <input id="password" type="password" name="password" minlength="8" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">تأكيد كلمة المرور</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" minlength="8" required>
                </div>

                <div class="actions">
                    <button class="btn btn-primary" type="submit">تعيين كلمة المرور</button>
                    <a class="btn btn-ghost" href="{{ route('password.otp.form', ['email' => old('email', $email)]) }}">تحديث الصفحة</a>
                </div>
            </form>

            <div class="divider"></div>
            <div class="hint">إذا لم يصلك الرمز، تحقّق من صندوق الرسائل غير المرغوب فيها (Spam) أو أعد الإرسال من صفحة “نسيت كلمة المرور”.</div>
        </div>

        <div class="footer">
            <span>© {{ date('Y') }} مزرعتي</span>
            <span>هوية لونية: أخضر زراعي &nbsp;•&nbsp; بيج ترابي</span>
        </div>
    </div>
</div>
</body>
</html>
