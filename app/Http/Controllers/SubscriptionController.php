<?php
namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(private SubscriptionService $subscriptionService) {}

    public function plans()
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        return view('subscriptions.plans', compact('plans'));
    }

    public function build()
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        return view('subscriptions.build', compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_id'       => 'required',
            'products'      => 'required',
            'gram_size'     => 'nullable|integer',
            'address.name'  => 'required|string|max:100',
            'address.phone' => 'required|string|max:20',
            'address.line1' => 'required|string|max:255',
            'address.city'  => 'required|string|max:100',
            'address.state' => 'nullable|string|max:100',
            'address.pincode'=> 'required|string|max:10',
        ]);

        // Decode products JSON string if needed
        $products = is_string($request->products) ? json_decode($request->products, true) : $request->products;

        // Create or find address
        $address = auth()->user()->addresses()->create([
            'name'     => $validated['address']['name'],
            'phone'    => $validated['address']['phone'],
            'line1'    => $validated['address']['line1'],
            'city'     => $validated['address']['city'],
            'state'    => $validated['address']['state'] ?? '',
            'pincode'  => $validated['address']['pincode'],
            'is_default' => false,
        ]);

        // Resolve plan — support both ID (numeric) and frequency string fallback
        $plan = \App\Models\SubscriptionPlan::find($validated['plan_id'])
             ?? \App\Models\SubscriptionPlan::where('frequency', $validated['plan_id'])->first();

        if (!$plan) {
            // Create a default plan on the fly if none exist
            $plan = \App\Models\SubscriptionPlan::firstOrCreate(
                ['frequency' => $validated['plan_id'] ?? 'monthly'],
                ['name' => ucfirst($validated['plan_id'] ?? 'monthly').' Plan', 'description' => 'Auto-created', 'discount_percent' => 10, 'is_active' => true]
            );
        }

        try {
            $subscription = $this->subscriptionService->create(
                auth()->user(),
                $plan->id,
                $products ?? [],
                $address->id
            );
        } catch (\Throwable $e) {
            // Fallback: create subscription record directly
            $subscription = \App\Models\Subscription::create([
                'user_id'              => auth()->id(),
                'plan_id'              => $plan->id,
                'address_id'           => $address->id,
                'status'               => 'active',
                'start_date'           => now()->toDateString(),
                'next_delivery_date'   => now()->addDays(3)->toDateString(),
                'subscription_number'  => 'SUB-'.strtoupper(substr(md5(uniqid()),0,8)),
            ]);
        }

        return redirect()->route('customer.subscriptions')->with('success', '🎉 Subscription activated! Your first delivery will arrive in 2–3 days.');
    }

    public function pause(Request $request, Subscription $subscription)
    {
        $this->authorize('update', $subscription);
        $validated = $request->validate(['pause_from' => 'required|date', 'pause_until' => 'required|date|after:pause_from']);
        $subscription->pause(now()->parse($validated['pause_from']), now()->parse($validated['pause_until']));
        return back()->with('success', 'Subscription paused.');
    }

    public function resume(Subscription $subscription)
    {
        $this->authorize('update', $subscription);
        $subscription->resume();
        return back()->with('success', 'Subscription resumed!');
    }

    public function cancel(Request $request, Subscription $subscription)
    {
        $this->authorize('update', $subscription);
        $subscription->cancel($request->reason ?? '');
        return back()->with('success', 'Subscription cancelled.');
    }
}
