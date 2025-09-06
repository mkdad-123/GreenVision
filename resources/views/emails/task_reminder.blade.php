<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تذكير بالمهمة</title>
</head>
<body style="direction: rtl; font-family: Arial, sans-serif;">
    <h2>مرحباً {{ $task->user->name }}</h2>
    <p>هذا تذكير بمهمتك:</p>

    <p><strong>نوع المهمة:</strong> {{ $task->type }}</p>
    <p><strong>الوصف:</strong> {{ $task->description }}</p>
    <p><strong>تاريخ التنفيذ:</strong> {{ $task->date }}</p>
    <p><strong>الأولوية:</strong> {{ $task->priority }}</p>

    <p>الرجاء إنجاز المهمة في وقتها.</p>
    <hr>
    <p>نظام إدارة المهام</p>
</body>
</html>
