<?php
namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const SESSION_KEY = 'dilli_cart';

    public function add(int $productId, ?int $variationId, int $quantity = 1): array
    {
        $cart = $this->getItems();
        $key = $productId . '_' . ($variationId ?? '0');

        $product = Product::with('variations')->findOrFail($productId);
        $variation = $variationId ? ProductVariation::findOrFail($variationId) : null;

        $price = $variation ? ($variation->sale_price ?? $variation->price) : ($product->sale_price ?? $product->price);
        $name = $product->name . ($variation ? ' - ' . $variation->name : '');

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
            $cart[$key]['subtotal'] = $cart[$key]['quantity'] * $price;
        } else {
            $cart[$key] = [
                'key' => $key,
                'product_id' => $productId,
                'variation_id' => $variationId,
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity,
                'subtotal' => $price * $quantity,
                'image' => $product->primaryImage?->url ?? null,
                'weight' => $variation ? $variation->weight : $product->weight,
                'unit' => $variation ? $variation->unit : $product->unit,
                'slug' => $product->slug,
            ];
        }

        Session::put(self::SESSION_KEY, $cart);
        return $cart[$key];
    }

    public function remove(string $itemKey): void
    {
        $cart = $this->getItems();
        unset($cart[$itemKey]);
        Session::put(self::SESSION_KEY, $cart);
    }

    public function update(string $itemKey, int $quantity): void
    {
        $cart = $this->getItems();
        if (isset($cart[$itemKey])) {
            if ($quantity <= 0) {
                $this->remove($itemKey);
                return;
            }
            $cart[$itemKey]['quantity'] = $quantity;
            $cart[$itemKey]['subtotal'] = $cart[$itemKey]['price'] * $quantity;
            Session::put(self::SESSION_KEY, $cart);
        }
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function getItems(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public function getTotal(): float
    {
        return array_sum(array_column($this->getItems(), 'subtotal'));
    }

    public function getItemCount(): int
    {
        return array_sum(array_column($this->getItems(), 'quantity'));
    }

    public function isEmpty(): bool
    {
        return empty($this->getItems());
    }
}
