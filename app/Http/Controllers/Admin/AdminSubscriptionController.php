<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class AdminSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscription::with(['user', 'plan'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $subscriptions = $query->paginate(15)->withQueryString();

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function show(Subscription $subscription)
    {
        $subscription->load(['user', 'plan', 'items', 'deliveries']);

        return view('admin.subscriptions.show', compact('subscription'));
    }

    public function updateStatus(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:active,paused,cancelled,expired'],
        ]);

        $subscription->update(['status' => $validated['status']]);

        // Always auto-send WhatsApp notification on status change
        $this->dispatchWhatsApp($subscription, $validated['status']);

        return back()->with('success', 'Subscription status updated to ' . $validated['status'] . '.');
    }

    public function sendWhatsApp(Request $request, Subscription $subscription)
    {
        $request->validate([
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = $subscription->user;

        if (empty($user->phone)) {
            return back()->with('error', 'User does not have a phone number on record.');
        }

        $message = $request->input('message') ?? $this->defaultStatusMessage($subscription->status);

        app(WhatsAppService::class)->sendMessage($user->phone, $message);

        return back()->with('success', 'WhatsApp message sent to ' . $user->name . '.');
    }

    public function plans()
    {
        $plans = SubscriptionPlan::all();

        return view('admin.subscription-plans.index', compact('plans'));
    }

    public function storePlan(Request $request)
    {
        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'frequency'        => ['required', 'string', 'max:100'],
            'discount_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'description'      => ['nullable', 'string'],
            'is_active'        => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        SubscriptionPlan::create($validated);

        return back()->with('success', 'Subscription plan created successfully.');
    }

    public function updatePlan(Request $request, SubscriptionPlan $plan)
    {
        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'frequency'        => ['required', 'string', 'max:100'],
            'discount_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'description'      => ['nullable', 'string'],
            'is_active'        => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $plan->update($validated);

        return back()->with('success', 'Subscription plan updated successfully.');
    }

    public function destroyPlan(SubscriptionPlan $plan)
    {
        $plan->delete();

        return back()->with('success', 'Subscription plan deleted.');
    }

    private function dispatchWhatsApp(Subscription $subscription, string $status): void
    {
        $user = $subscription->user;

        if (empty($user->phone)) {
            return;
        }

        $message = $this->defaultStatusMessage($status);

        app(WhatsAppService::class)->sendMessage($user->phone, $message);
    }

    private function defaultStatusMessage(string $status): string
    {
        return match ($status) {
            'active'    => "✅ *Subscription Active — Dilli Creamery*\n\nYour subscription is now *ACTIVE*. 🎉\nDeliveries will begin as scheduled.\n\nManage: http://localhost:8001/account/subscriptions\n\n_Pure Ghee. Pure Care._ 🥛",
            'paused'    => "⏸ *Subscription Paused — Dilli Creamery*\n\nYour subscription has been *PAUSED*.\nWe'll resume deliveries when you're ready.\n\nManage: http://localhost:8001/account/subscriptions\n\n_Pure Ghee. Pure Care._ 🥛",
            'cancelled' => "❌ *Subscription Cancelled — Dilli Creamery*\n\nYour subscription has been *CANCELLED*.\nWe hope to serve you again soon!\n\nResubscribe: http://localhost:8001/subscriptions\n\n_Pure Ghee. Pure Care._ 🥛",
            'expired'   => "⏰ *Subscription Expired — Dilli Creamery*\n\nYour subscription has *EXPIRED*.\nRenew now to continue receiving fresh deliveries.\n\nRenew: http://localhost:8001/subscriptions\n\n_Pure Ghee. Pure Care._ 🥛",
            default     => "📋 *Subscription Update — Dilli Creamery*\n\nYour subscription status has been updated to: *" . strtoupper($status) . "*.\n\nManage: http://localhost:8001/account/subscriptions\n\n_Pure Ghee. Pure Care._ 🥛",
        };
    }
}
