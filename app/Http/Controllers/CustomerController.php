<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $recentOrders = $user->orders()->latest()->take(5)->get();
        $activeSubscription = $user->subscriptions()->active()->with('plan')->first();
        return view('customer.dashboard', compact('user', 'recentOrders', 'activeSubscription'));
    }

    public function orders()
    {
        $orders = auth()->user()->orders()->with('items')->latest()->paginate(10);
        return view('customer.orders.index', compact('orders'));
    }

    public function showOrder(\App\Models\Order $order)
    {
        $this->authorize('view', $order);
        $order->load(['items.product', 'items.variation', 'address']);
        return view('customer.orders.show', compact('order'));
    }

    public function subscriptions()
    {
        $subscriptions = auth()->user()->subscriptions()->with(['plan', 'items.product'])->latest()->paginate(5);
        return view('customer.subscriptions.index', compact('subscriptions'));
    }

    public function showSubscription(\App\Models\Subscription $subscription)
    {
        $this->authorize('view', $subscription);
        $subscription->load(['plan', 'items.product', 'deliveries']);
        return view('customer.subscriptions.show', compact('subscription'));
    }

    public function profile()
    {
        return view('customer.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ]);
        $user->update($validated);
        return back()->with('success', 'Profile updated successfully!');
    }

    public function invoices()
    {
        $orders = auth()->user()->orders()->where('payment_status', 'paid')->latest()->paginate(10);
        return view('customer.invoices', compact('orders'));
    }
}
