<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')
            ->where(function ($q) {
                $q->whereNotNull('razorpay_order_id')
                  ->orWhere('payment_status', 'paid');
            });

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        return view('admin.payments.index', compact('orders'));
    }

    public function razorpay()
    {
        $totalCollected = Order::where('payment_status', 'paid')
            ->whereNotNull('razorpay_payment_id')
            ->sum('total');

        $totalPending = Order::where('payment_status', 'pending')
            ->whereNotNull('razorpay_order_id')
            ->sum('total');

        $totalRefunded = Order::where('payment_status', 'refunded')
            ->whereNotNull('razorpay_order_id')
            ->sum('total');

        $countPaid = Order::where('payment_status', 'paid')
            ->whereNotNull('razorpay_payment_id')
            ->count();

        $countPending = Order::where('payment_status', 'pending')
            ->whereNotNull('razorpay_order_id')
            ->count();

        $countRefunded = Order::where('payment_status', 'refunded')
            ->whereNotNull('razorpay_order_id')
            ->count();

        $recentOrders = Order::with('user')
            ->whereNotNull('razorpay_order_id')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.payments.razorpay', compact(
            'totalCollected',
            'totalPending',
            'totalRefunded',
            'countPaid',
            'countPending',
            'countRefunded',
            'recentOrders'
        ));
    }

    public function show($id)
    {
        $order = Order::with('user')->findOrFail($id);

        return view('admin.payments.show', compact('order'));
    }
}
