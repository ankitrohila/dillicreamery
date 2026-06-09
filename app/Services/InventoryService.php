<?php
namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariation;

class InventoryService
{
    public function deduct(int $productId, ?int $variationId, int $quantity, ?int $referenceId = null): void
    {
        if ($variationId) {
            ProductVariation::where('id', $variationId)->decrement('stock_quantity', $quantity);
        } else {
            Product::where('id', $productId)->decrement('stock_quantity', $quantity);
        }
    }

    public function restock(int $productId, ?int $variationId, int $quantity, ?int $referenceId = null): void
    {
        if ($variationId) {
            ProductVariation::where('id', $variationId)->increment('stock_quantity', $quantity);
        } else {
            Product::where('id', $productId)->increment('stock_quantity', $quantity);
        }
    }

    public function getBalance(int $productId, ?int $variationId = null): int
    {
        if ($variationId) {
            return ProductVariation::find($variationId)?->stock_quantity ?? 0;
        }
        return Product::find($productId)?->stock_quantity ?? 0;
    }

    public function isAvailable(int $productId, ?int $variationId, int $quantity): bool
    {
        return $this->getBalance($productId, $variationId) >= $quantity;
    }
}
