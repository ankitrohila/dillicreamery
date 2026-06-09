<?php
namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Coupon;
use App\Events\OrderPlaced;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        private CartService $cartService,
        private InventoryService $inventoryService,
        private CouponService $couponService,
    ) {}

    public function createFromCart(
        \App\Models\User $user,
        int $addressId,
        ?string $couponCode,
        array $paymentData
    ): Order {
        return DB::transaction(function () use ($user, $addressId, $couponCode, $paymentData) {
            $cartItems = $this->cartService->getItems();
            if (empty($cartItems)) {
                throw new \Exception('Cart is empty');
            }

            $subtotal = $this->cartService->getTotal();
            $discount = 0;
            $couponId = null;
            $deliveryCharge = $subtotal >= 500 ? 0 : 50;

            if ($couponCode) {
                $coupon = Coupon::where('code', $couponCode)->first();
                if ($coupon && $coupon->isValid($subtotal)) {
                    $discount = $coupon->calculateDiscount($subtotal);
                    $couponId = $coupon->id;
                    $coupon->increment('used_count');
                }
            }

            $total = $subtotal - $discount + $deliveryCharge;

            $order = Order::create([
                'order_number' => 'DC' . date('Ymd') . strtoupper(substr(uniqid(), -6)),
                'user_id' => $user->id,
                'address_id' => $addressId,
                'coupon_id' => $couponId,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'payment_method' => $paymentData['method'] ?? 'razorpay',
                'payment_id' => $paymentData['payment_id'] ?? null,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'delivery_charge' => $deliveryCharge,
                'tax' => 0,
                'total' => $total,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'variation_id' => $item['variation_id'],
                    'name' => $item['name'],
                    'sku' => null,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);

                $this->inventoryService->deduct($item['product_id'], $item['variation_id'], $item['quantity'], $order->id);
            }

            $this->cartService->clear();
            event(new OrderPlaced($order));

            return $order;
        });
    }

    public function updateStatus(Order $order, string $status): void
    {
        $order->update(['status' => $status]);
        if ($status === 'delivered') {
            $order->update(['delivered_at' => now()]);
        }
        event(new \App\Events\OrderStatusChanged($order, $status));
    }
}
