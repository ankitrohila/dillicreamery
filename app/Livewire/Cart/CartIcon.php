<?php
namespace App\Livewire\Cart;

use App\Services\CartService;
use Livewire\Component;

class CartIcon extends Component
{
    public int $count = 0;

    protected $listeners = ['cart-updated' => 'refreshCount'];

    public function mount(): void
    {
        $this->count = app(CartService::class)->getItemCount();
    }

    public function refreshCount(): void
    {
        $this->count = app(CartService::class)->getItemCount();
    }

    public function render()
    {
        return view('livewire.cart.cart-icon');
    }
}
