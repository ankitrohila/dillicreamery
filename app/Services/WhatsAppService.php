<?php
namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $apiUrl;
    private string $token;
    private string $phoneId;
    private string $businessNumber;
    private bool   $enabled;

    public function __construct()
    {
        $this->apiUrl         = Setting::where('key', 'whatsapp_api_url')->value('value')         ?? 'https://graph.facebook.com/v25.0';
        $this->token          = Setting::where('key', 'whatsapp_token')->value('value')           ?? '';
        $this->phoneId        = Setting::where('key', 'whatsapp_phone_id')->value('value')        ?? '';
        $this->businessNumber = Setting::where('key', 'whatsapp_business_number')->value('value') ?? '';
        $this->enabled        = Setting::where('key', 'whatsapp_enabled')->value('value') === '1';
    }

    /* ──────────────────────── core sender ──────────────────────── */

    /**
     * Normalize any Indian phone number to 12-digit format: 91XXXXXXXXXX
     */
    private function normalizePhone(string $phone): string
    {
        // Strip everything except digits
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Already correct: 91 + 10 digits = 12 digits
        if (strlen($phone) === 12 && str_starts_with($phone, '91')) {
            return $phone;
        }
        // Indian local with leading 0: 0XXXXXXXXXX = 11 digits → strip 0, add 91
        if (strlen($phone) === 11 && str_starts_with($phone, '0')) {
            return '91' . substr($phone, 1);
        }
        // Plain 10-digit Indian number → add 91
        if (strlen($phone) === 10) {
            return '91' . $phone;
        }
        // 91 prefix but somehow 13+ digits (data entry error like +91 0XXXXXXXXXX)
        if (strlen($phone) === 13 && str_starts_with($phone, '910')) {
            return '91' . substr($phone, 3);
        }
        // Return as-is (already has country code or non-Indian)
        return $phone;
    }

    public function sendMessage(string $phone, string $message): bool
    {
        if (! $this->enabled || empty($this->token) || empty($this->phoneId) || empty($phone)) {
            Log::info("WhatsApp disabled/misconfigured. Would send to: {$phone}");
            return false;
        }
        try {
            $phone = $this->normalizePhone($phone);

            $response = Http::withToken($this->token)
                ->timeout(15)
                ->post("{$this->apiUrl}/{$this->phoneId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to'   => $phone,
                    'type' => 'text',
                    'text' => ['body' => $message, 'preview_url' => false],
                ]);

            Log::info("WhatsApp [{$phone}] → HTTP {$response->status()}: " . $response->body());
            return $response->successful();
        } catch (\Throwable $e) {
            Log::error("WhatsApp sendMessage error: " . $e->getMessage());
            return false;
        }
    }

    /* ──────────────────────── order notifications ──────────────── */

    public function sendOrderConfirmation(\App\Models\Order $order): bool
    {
        $phone = $order->user?->phone;
        if (! $phone) return false;

        $items  = $order->items->map(fn($i) => "• {$i->name} × {$i->quantity} — ₹" . number_format($i->subtotal, 0))->implode("\n");
        $delivery = $order->delivery_charge > 0 ? "\nDelivery: ₹" . number_format($order->delivery_charge, 0) : "\nDelivery: FREE 🎉";
        $discount = $order->discount > 0 ? "\nDiscount: -₹" . number_format($order->discount, 0) : '';
        $trackUrl = url('/account/orders/' . $order->id);

        $msg = "✅ *Order Confirmed — Dilli Creamery*\n\n"
             . "Hi {$order->user->name}! Your order is confirmed.\n\n"
             . "*Order #{$order->order_number}*\n"
             . "─────────────────\n"
             . $items
             . "\n─────────────────"
             . $delivery
             . $discount
             . "\n*Total: ₹" . number_format($order->total, 0) . "*\n\n"
             . "📦 Track your order:\n{$trackUrl}\n\n"
             . "_Pure Ghee. Pure Care._ 🥛\n"
             . "Need help? Reply to this message.";

        return $this->sendMessage($phone, $msg);
    }

    public function sendOrderStatusUpdate(\App\Models\Order $order): bool
    {
        $phone = $order->user?->phone;
        if (! $phone) return false;

        $emojis = [
            'confirmed'  => '✅',
            'processing' => '🔄',
            'packed'     => '📦',
            'shipped'    => '🚚',
            'out_for_delivery' => '🛵',
            'delivered'  => '🎉',
            'cancelled'  => '❌',
        ];
        $emoji    = $emojis[$order->status] ?? '📋';
        $status   = strtoupper(str_replace('_', ' ', $order->status));
        $trackUrl = url('/account/orders/' . $order->id);

        $extra = '';
        if ($order->status === 'delivered') {
            $extra = "\n\nLoved our products? Leave a review:\n" . $trackUrl . "#review";
        } elseif ($order->status === 'out_for_delivery') {
            $extra = "\n\nPlease keep your door open. Our delivery partner will arrive shortly! 🙏";
        }

        $msg = "{$emoji} *Order Update — #{$order->order_number}*\n\n"
             . "Hi {$order->user->name}! Your order status is now *{$status}*."
             . $extra . "\n\n"
             . "Track: {$trackUrl}\n\n"
             . "_Dilli Creamery_ 🥛";

        return $this->sendMessage($phone, $msg);
    }

    public function sendOrderInvoice(\App\Models\Order $order): bool
    {
        $phone = $order->user?->phone;
        if (! $phone) return false;

        $invoiceUrl = url('/account/orders/' . $order->id);
        $date       = $order->created_at->format('d M Y');

        $msg = "🧾 *Invoice — Dilli Creamery*\n\n"
             . "Hi {$order->user->name}!\n\n"
             . "Invoice for your order *#{$order->order_number}* dated {$date}.\n"
             . "Amount: ₹" . number_format($order->total, 0) . "\n"
             . "Payment: " . strtoupper($order->payment_method ?? 'online') . "\n\n"
             . "View & Download Invoice:\n{$invoiceUrl}\n\n"
             . "Thank you for shopping with Dilli Creamery! 🙏\n"
             . "_Pure Ghee. Pure Care._";

        return $this->sendMessage($phone, $msg);
    }

    /* ──────────────────────── subscription notifications ───────── */

    public function sendSubscriptionUpdate(\App\Models\Subscription $subscription, string $event = 'activated'): bool
    {
        $phone = $subscription->user?->phone;
        if (! $phone) return false;

        $name     = $subscription->user->name;
        $trackUrl = url('/account/subscriptions/' . $subscription->id);

        $messages = [
            'activated' => "✅ *Subscription Activated — Dilli Creamery*\n\n"
                         . "Hi {$name}! Your subscription is now *ACTIVE*. 🎉\n\n"
                         . "Plan: *{$subscription->plan_name}*\n"
                         . "Your first delivery will arrive within 1-2 days.\n\n"
                         . "Manage your subscription:\n{$trackUrl}\n\n"
                         . "_Pure Ghee. Pure Care._ 🥛",

            'paused'    => "⏸️ *Subscription Paused*\n\n"
                         . "Hi {$name}! Your Dilli Creamery subscription has been *PAUSED*.\n\n"
                         . "Resume anytime from:\n{$trackUrl}\n\n"
                         . "Or reply *RESUME* to restart.",

            'resumed'   => "▶️ *Subscription Resumed*\n\n"
                         . "Hi {$name}! Great news — your subscription is *ACTIVE* again! 🎉\n\n"
                         . "Your next delivery is on its way soon.\n"
                         . "Manage: {$trackUrl}\n\n"
                         . "_Dilli Creamery_ 🥛",

            'cancelled' => "❌ *Subscription Cancelled*\n\n"
                         . "Hi {$name}, your Dilli Creamery subscription has been cancelled.\n\n"
                         . "We're sorry to see you go! If you change your mind, you can resubscribe anytime at:\n"
                         . url('/subscriptions') . "\n\n"
                         . "Thank you for being our customer. 🙏",

            'delivery'  => "🛵 *Delivery Today — Dilli Creamery*\n\n"
                         . "Hi {$name}! Your daily ghee delivery is *OUT FOR DELIVERY* right now.\n\n"
                         . "Please keep your door open. Our delivery partner will arrive shortly!\n\n"
                         . "Questions? Reply here. 😊\n\n"
                         . "_Dilli Creamery_ 🥛",

            'reminder'  => "⏰ *Delivery Reminder — Dilli Creamery*\n\n"
                         . "Hi {$name}! Your Dilli Creamery delivery is scheduled for *today*. 🥛\n\n"
                         . "Expected time: Morning (before 10 AM)\n\n"
                         . "Track & manage: {$trackUrl}",
        ];

        return $this->sendMessage($phone, $messages[$event] ?? $messages['activated']);
    }

    /* ──────────────────────── daily delivery blast ─────────────── */

    /**
     * Send daily delivery notification to all active subscription holders.
     * Called from artisan command: whatsapp:daily-delivery
     */
    public function sendDailyDeliveryNotifications(): array
    {
        $subscriptions = \App\Models\Subscription::with('user')
            ->where('status', 'active')
            ->whereHas('user', fn($q) => $q->whereNotNull('phone'))
            ->get();

        $sent = 0; $failed = 0;
        foreach ($subscriptions as $sub) {
            if ($this->sendSubscriptionUpdate($sub, 'reminder')) {
                $sent++;
            } else {
                $failed++;
            }
            // Small delay to avoid rate limiting
            usleep(200000); // 200ms
        }
        return ['sent' => $sent, 'failed' => $failed, 'total' => $subscriptions->count()];
    }

    /* ──────────────────────── WhatsApp order link ───────────────── */

    public function generateOrderLink(\App\Models\Product $product): string
    {
        $waPhone = preg_replace('/\D/', '', $this->businessNumber) ?: '919220872212';
        $msg = urlencode(
            "Hi Dilli Creamery! 👋\n\n"
            . "I'd like to order: *{$product->name}*\n"
            . "Price: ₹" . number_format($product->display_price, 0) . "\n\n"
            . "Please confirm availability and delivery details. Thank you!"
        );
        return "https://wa.me/{$waPhone}?text={$msg}";
    }

    public function getBusinessNumber(): string
    {
        return preg_replace('/\D/', '', $this->businessNumber) ?: '919220872212';
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
