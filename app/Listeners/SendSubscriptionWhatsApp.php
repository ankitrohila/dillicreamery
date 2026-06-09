<?php
namespace App\Listeners;

use App\Events\SubscriptionCreated;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSubscriptionWhatsApp implements ShouldQueue
{
    public string $queue = 'default';
    public int $tries = 2;

    public function __construct(private WhatsAppService $whatsApp) {}

    public function handle(SubscriptionCreated $event): void
    {
        $subscription = $event->subscription->load('user');
        $this->whatsApp->sendSubscriptionUpdate($subscription, 'activated');
    }
}
