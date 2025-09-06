<!DOCTYPE html>
<html lang="ar">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>إعادة تعيين كلمة المرور</title></head>
<body>
    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
    @endif

    <h3>إعادة تعيين كلمة المرور لحساب: {{ $email ?? '' }}</h3>

    <form method="POST" action="{{ route('password.update.clean') }}">
        @csrf
        <label>كلمة المرور الجديدة</label>
        <input type="password" name="password" required>
        <label>تأكيد كلمة المرور</label>
        <input type="password" name="password_confirmation" required>
        <button type="submit">تحديث</button>
    </form>
</body>
</html>
