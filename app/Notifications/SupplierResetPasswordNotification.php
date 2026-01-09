<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupplierResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // ✅ رابط إعادة التعيين الخاص بالموردين
        $url = route('supplier.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('إعادة تعيين كلمة المرور - توريد ميد') // عنوان الإيميل
            ->greeting('مرحباً ' . $notifiable->name . '،') // التحية
            ->line('لقد تلقيت هذه الرسالة لأننا استلمنا طلب إعادة تعيين كلمة المرور لحسابك الخاص بالمورد.')
            ->action('إعادة تعيين كلمة المرور', $url) // الزر والرابط
            ->line('رابط إعادة تعيين كلمة المرور هذا صالح لمدة 60 دقيقة.')
            ->line('إذا لم تطلب إعادة تعيين كلمة المرور، فلا داعي لاتخاذ أي إجراء آخر.')
            ->salutation('تحياتنا، فريق توريد ميد'); // الختام
    }
}
