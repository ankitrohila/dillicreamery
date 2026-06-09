<?php
namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderWhatsApp implements ShouldQueue
{
    public string $queue = 'default';
    public int $tries = 2;

    public function __construct(private WhatsAppService $whatsApp) {}

    public function handle(OrderPlaced $event): void
    {
        $order = $event->order->load(['user', 'items']);
        // Send order confirmation
        $this->whatsApp->sendOrderConfirmation($order);
        // Send invoice 2 minutes later (small delay so messages are separate)
        // For now send immediately; queue delay can be added later
        // $this->whatsApp->sendOrderInvoice($order);
    }
}
