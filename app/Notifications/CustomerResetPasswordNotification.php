<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // ✅ الرابط هنا هو مفتاح الحل، لازم يروح لراوت العميل
        $url = route('frontend.customer.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('استعادة كلمة المرور - عميل')
            ->line('لقد تلقيت هذا البريد لأننا استلمنا طلب استعادة كلمة مرور لحسابك.')
            ->action('تغيير كلمة المرور', $url)
            ->line('إذا لم تطلب هذا التغيير، يمكنك تجاهل الرسالة.');
    }
}
