<?php
namespace App\Services;

use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    private Api $api;

    public function __construct()
    {
        $this->api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );
    }

    public function createOrder(float $amount, string $currency = 'INR', string $receipt = ''): array
    {
        $orderData = [
            'receipt' => $receipt ?: 'receipt_' . uniqid(),
            'amount' => (int) ($amount * 100), // paise
            'currency' => $currency,
            'payment_capture' => 1,
        ];

        $razorpayOrder = $this->api->order->create($orderData);
        return $razorpayOrder->toArray();
    }

    public function verifyPayment(string $orderId, string $paymentId, string $signature): bool
    {
        $expectedSignature = hash_hmac(
            'sha256',
            $orderId . '|' . $paymentId,
            config('services.razorpay.secret')
        );
        return hash_equals($expectedSignature, $signature);
    }

    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $expectedSignature = hash_hmac('sha256', $payload, config('services.razorpay.webhook_secret', ''));
        return hash_equals($expectedSignature, $signature);
    }

    public function fetchPayment(string $paymentId): array
    {
        try {
            $payment = $this->api->payment->fetch($paymentId);
            return $payment->toArray();
        } catch (\Exception $e) {
            Log::error('Razorpay fetch payment error: ' . $e->getMessage());
            return [];
        }
    }

    public function refund(string $paymentId, float $amount): array
    {
        try {
            $refund = $this->api->payment->fetch($paymentId)->refund(['amount' => (int)($amount * 100)]);
            return $refund->toArray();
        } catch (\Exception $e) {
            Log::error('Razorpay refund error: ' . $e->getMessage());
            return [];
        }
    }
}
