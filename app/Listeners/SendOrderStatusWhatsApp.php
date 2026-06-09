<?php
namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderStatusWhatsApp implements ShouldQueue
{
    public string $queue = 'default';
    public int $tries = 2;

    public function __construct(private WhatsAppService $whatsApp) {}

    public function handle(OrderStatusChanged $event): void
    {
        $order = $event->order->load('user');
        $this->whatsApp->sendOrderStatusUpdate($order);
    }
}
