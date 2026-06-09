<?php
namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index()
    {
        $items = $this->cart->getItems();
        $total = $this->cart->getTotal();
        return view('cart', compact('items', 'total'));
    }

    public function add(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'variation_id' => 'nullable|integer|exists:product_variations,id',
            'quantity' => 'integer|min:1|max:100',
        ]);

        $item = $this->cart->add($validated['product_id'], $validated['variation_id'] ?? null, $validated['quantity'] ?? 1);
        return response()->json(['success' => true, 'item' => $item, 'count' => $this->cart->getItemCount()]);
    }

    public function update(Request $request, string $key): JsonResponse
    {
        $validated = $request->validate(['quantity' => 'required|integer|min:0|max:100']);
        $this->cart->update($key, $validated['quantity']);
        return response()->json(['success' => true, 'total' => $this->cart->getTotal(), 'count' => $this->cart->getItemCount()]);
    }

    public function remove(string $key): JsonResponse
    {
        $this->cart->remove($key);
        return response()->json(['success' => true, 'count' => $this->cart->getItemCount()]);
    }

    public function clear(): JsonResponse
    {
        $this->cart->clear();
        return response()->json(['success' => true]);
    }

    public function checkout()
    {
        $items = $this->cart->getItems();
        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        $total        = $this->cart->getTotal();
        $delivery     = $total >= 500 ? 0 : 50;
        $grand        = $total + $delivery;
        $addresses    = auth()->user()->addresses()->latest()->get();
        $upiEnabled   = \App\Models\Setting::where('key', 'upi_enabled')->value('value') === '1';
        $upiVpa       = \App\Models\Setting::where('key', 'upi_vpa')->value('value') ?? '';
        $upiMerchant  = \App\Models\Setting::where('key', 'upi_merchant_name')->value('value') ?? 'Dilli Creamery';
        $gpayEnabled  = \App\Models\Setting::where('key', 'gpay_enabled')->value('value') !== '0';
        $phonepeEnabled = \App\Models\Setting::where('key', 'phonepe_enabled')->value('value') !== '0';
        $razorpayKey  = config('services.razorpay.key');
        return view('checkout', compact('items', 'total', 'delivery', 'grand', 'addresses',
            'upiEnabled', 'upiVpa', 'upiMerchant', 'gpayEnabled', 'phonepeEnabled', 'razorpayKey'));
    }
}
