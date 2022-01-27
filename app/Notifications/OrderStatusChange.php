<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChange extends Notification
{
    use Queueable;

    private Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
                    ->subject('Order Status: ' . $this->order->status)
                    ->greeting('Hi your order was ' . $this->order->status)
                    ->action('Can se the details click Here', route('orders.show', $this->order->id))
                    ->line('Thank you for using our application!');
    }
}
