<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerifyEmailVietnamese extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
        );

        $name = $notifiable->profile->name ?? $notifiable->name ?? 'bạn';

        return (new MailMessage)
            ->subject('Xác minh địa chỉ Email của bạn - ' . config('app.name'))
            ->greeting('Xin chào ' . $name . '!')
            ->line('Cảm ơn bạn đã đăng ký tài khoản tại ' . config('app.name') . '.')
            ->line('Vui lòng nhấn vào nút bên dưới để xác minh email:')
            ->action('Xác minh Email', $verifyUrl)
            ->line('Link xác minh có hiệu lực trong vòng 60 phút.')
            ->line('Nếu bạn không thực hiện đăng ký, vui lòng bỏ qua email này.')
            ->salutation('Trân trọng, ' . config('app.name'));
    }
}