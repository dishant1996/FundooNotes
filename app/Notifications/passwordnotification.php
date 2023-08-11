<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Http\Controllers\PasswordReset;


class PasswordResetRequest extends Notification
{
    use Queueable;

    private $token;
    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
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
        $url = url("/passwordreset/$this->token");
        return (new MailMessage)
                    // ->line('forgot password.')
                    // ->action('click to reset', url('/'))
                    // ->line('Thank you for using our application!');
                    
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', url($url))
            ->line('Link is valid only for 12 hours')
            ->line('If you did not request a password reset, no further action is required.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
