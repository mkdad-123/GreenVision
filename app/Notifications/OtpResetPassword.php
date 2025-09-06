<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OtpResetPassword extends Notification
{
    use Queueable;

    public function __construct(public string $code) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = route('password.otp.form', ['email' => $notifiable->email]);

        return (new MailMessage)
            ->subject('رمز إعادة تعيين كلمة المرور')
            ->greeting('مرحبًا!')
            ->line('رمزك لإعادة تعيين كلمة المرور:')
            ->line('**' . $this->code . '**')
            ->line('صلاحية الرمز: 10 دقائق.')
            ->line('إذا لم تطلب إعادة التعيين، تجاهل الرسالة.');
    }
}
