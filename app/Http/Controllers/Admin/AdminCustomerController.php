<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(20)->withQueryString();

        return view('admin.customers.index', compact('customers', 'search'));
    }

    public function show(User $user)
    {
        $user->load([
            'orders',
            'subscriptions',
            'addresses',
            'wishlist',
        ]);

        $total_spent = $user->orders
            ->whereIn('status', ['completed', 'delivered'])
            ->sum('total');

        return view('admin.customers.show', compact('user', 'total_spent'));
    }

    public function sendWhatsApp(Request $request, User $user)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        app(WhatsAppService::class)->send($user->phone, $request->input('message'));

        return back()->with('success', 'WhatsApp message sent successfully.');
    }

    public function newsletter(Request $request)
    {
        $subscribers = NewsletterSubscriber::latest()->paginate(30);

        return view('admin.newsletter.index', compact('subscribers'));
    }
}
