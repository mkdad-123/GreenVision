<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        /* نفس تنسيقاتك */
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, .1);
            width: 320px;
            border-radius: 8px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 16px;
        }

        .form-group {
            margin-bottom: 12px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
        }

        input[type=email],
        input[type=password] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button[type=submit] {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }

        button[type=submit]:hover {
            background-color: #4cae4c;
        }

        .alert {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .alert-success {
            background: #eaf9ea;
            border: 1px solid #bfe6bf;
            color: #2e7d32;
        }

        .alert-error {
            background: #fdecea;
            border: 1px solid #f5c6cb;
            color: #a94442;
        }

        .errors {
            margin: 0 0 12px;
            padding: 0;
            list-style: none;
        }

        .errors li {
            font-size: 13px;
            color: #a94442;
            margin-bottom: 6px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Password Reset</h1>

        {{-- رسالة نجاح قادمة من السيشن (مثلاً بعد إعادة توجيه) --}}
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        {{-- عرض الأخطاء --}}
        @if ($errors->any())
            <ul class="errors">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('password.update', ['broker' => $broker]) }}">
            @csrf

            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" name="email" id="email" value="{{ old('email', $email ?? '') }}" required
                    autofocus>
            </div>

            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm New Password:</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required>
            </div>

            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="broker" value="{{ $broker }}">

            <div class="form-group">
                <button type="submit">Reset Password</button>
            </div>
        </form>
    </div>
</body>

</html>
