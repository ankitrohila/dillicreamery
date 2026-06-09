<?php
namespace App\Http\Controllers;

use App\Services\PaymentService;
use App\Services\OrderService;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private OrderService $orderService,
        private CartService $cartService,
    ) {}

    public function createOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'address_id' => 'required|integer',
            'coupon_code' => 'nullable|string',
        ]);

        $total = $this->cartService->getTotal();
        if ($total <= 0) {
            return response()->json(['error' => 'Cart is empty'], 422);
        }

        $razorpayOrder = $this->paymentService->createOrder($total, 'INR', 'order_' . auth()->id());

        return response()->json([
            'order_id' => $razorpayOrder['id'],
            'amount' => $razorpayOrder['amount'],
            'currency' => $razorpayOrder['currency'],
            'key' => config('services.razorpay.key'),
        ]);
    }

    public function verify(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string',
            'address_id' => 'required|integer',
            'coupon_code' => 'nullable|string',
        ]);

        if (!$this->paymentService->verifyPayment($validated['razorpay_order_id'], $validated['razorpay_payment_id'], $validated['razorpay_signature'])) {
            return response()->json(['error' => 'Payment verification failed'], 422);
        }

        $order = $this->orderService->createFromCart(
            auth()->user(),
            $validated['address_id'],
            $validated['coupon_code'] ?? null,
            ['payment_id' => $validated['razorpay_payment_id'], 'method' => 'razorpay']
        );

        return response()->json(['success' => true, 'order_id' => $order->id, 'order_number' => $order->order_number]);
    }

    public function upiConfirm(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'address_id'  => 'required|integer',
            'upi_txn_id'  => 'nullable|string|max:100',
        ]);

        $total = $this->cartService->getTotal();
        if ($total <= 0) {
            return response()->json(['error' => 'Cart is empty'], 422);
        }

        $order = $this->orderService->createFromCart(
            auth()->user(),
            $validated['address_id'],
            null,
            [
                'payment_id'     => $validated['upi_txn_id'] ?? 'upi_pending',
                'method'         => 'upi',
                'payment_status' => 'pending_verification',
            ]
        );

        // Notify admin via WhatsApp about UPI order needing verification
        try {
            $wa = app(\App\Services\WhatsAppService::class);
            $adminPhone = \App\Models\Setting::where('key','site_phone')->value('value');
            if ($adminPhone) {
                $wa->sendMessage($adminPhone,
                    "💰 *New UPI Order — #{$order->order_number}*\n\n"
                    . "Customer: {$order->user->name}\n"
                    . "Amount: ₹" . number_format($order->total, 0) . "\n"
                    . "UTR/TxnID: " . ($validated['upi_txn_id'] ?: 'Not provided') . "\n\n"
                    . "Please verify the UPI payment and update order status."
                );
            }
        } catch (\Throwable $e) {}

        return response()->json(['success' => true, 'order_id' => $order->id, 'order_number' => $order->order_number]);
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Razorpay-Signature', '');

        if (!$this->paymentService->verifyWebhookSignature($payload, $signature)) {
            return response('Unauthorized', 401);
        }

        $event = $request->input('event');
        // Handle events: payment.captured, payment.failed, subscription.charged etc.

        return response('OK', 200);
    }
}
