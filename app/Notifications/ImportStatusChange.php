<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ImportStatusChange extends Notification
{
    use Queueable;

    private string $status;
    private string $message;

    public function __construct(string $status, string $message)
    {
        $this->status = $status;
        $this->message = $message;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
                    ->subject('Import Status: ' . $this->status)
                    ->greeting('Hi your import of products ' . $this->message)
                    ->action('Can se the details click Here', route('products.index'))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
