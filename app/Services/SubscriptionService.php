<?php
namespace App\Services;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionItem;
use App\Models\SubscriptionDelivery;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    public function create(\App\Models\User $user, int $planId, array $products, int $addressId): Subscription
    {
        return DB::transaction(function () use ($user, $planId, $products, $addressId) {
            $plan = SubscriptionPlan::findOrFail($planId);

            $subscription = Subscription::create([
                'subscription_number' => 'SUB' . date('Ymd') . strtoupper(substr(uniqid(), -6)),
                'user_id' => $user->id,
                'plan_id' => $planId,
                'address_id' => $addressId,
                'status' => 'active',
                'start_date' => today(),
                'next_delivery_date' => today(),
                'total_deliveries' => 0,
                'completed_deliveries' => 0,
            ]);

            foreach ($products as $item) {
                SubscriptionItem::create([
                    'subscription_id' => $subscription->id,
                    'product_id' => $item['product_id'],
                    'variation_id' => $item['variation_id'] ?? null,
                    'quantity' => $item['quantity'] ?? 1,
                    'price' => $item['price'],
                ]);
            }

            $this->generateDeliverySchedule($subscription);

            // Fire WhatsApp notification
            event(new \App\Events\SubscriptionCreated($subscription));

            return $subscription;
        });
    }

    public function generateDeliverySchedule(Subscription $subscription, int $days = 30): void
    {
        $plan = $subscription->plan;
        $startDate = $subscription->next_delivery_date ?? today();
        $dates = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            $shouldDeliver = match($plan->frequency) {
                'daily' => true,
                'alternate_day' => $i % 2 === 0,
                'weekly' => $date->dayOfWeek === $startDate->dayOfWeek,
                'monthly' => $date->day === $startDate->day,
                default => false,
            };
            if ($shouldDeliver) {
                $dates[] = $date;
            }
        }

        foreach ($dates as $date) {
            $exists = $subscription->deliveries()->where('scheduled_date', $date)->exists();
            if (!$exists) {
                SubscriptionDelivery::create([
                    'subscription_id' => $subscription->id,
                    'scheduled_date' => $date,
                    'status' => 'scheduled',
                ]);
            }
        }
    }

    public function skipDelivery(SubscriptionDelivery $delivery): void
    {
        $delivery->update(['status' => 'skipped']);
    }
}
