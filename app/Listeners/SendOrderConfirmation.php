<?php
namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderConfirmation implements ShouldQueue
{
    public function handle(OrderPlaced $event): void
    {
        // Mail::to($event->order->user->email)->queue(new \App\Mail\OrderConfirmationMail($event->order));
    }
}
