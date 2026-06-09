<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\ConsultancyBooking;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller {
    public function index() {
        $stats = [
            'total_orders'         => Order::count(),
            'pending_orders'       => Order::where('status','pending')->count(),
            'total_revenue'        => Order::where('payment_status','paid')->sum('total'),
            'today_revenue'        => Order::where('payment_status','paid')->whereDate('created_at',today())->sum('total'),
            'total_customers'      => User::role('customer')->count(),
            'new_customers_today'  => User::whereDate('created_at',today())->count(),
            'active_subscriptions' => Subscription::where('status','active')->count(),
            'total_products'       => Product::where('is_active',true)->count(),
            'low_stock'            => Product::where('stock_quantity','<=',5)->where('is_active',true)->count(),
            'consultancy_bookings' => ConsultancyBooking::where('status','pending')->count(),
            'newsletter_subscribers'=> NewsletterSubscriber::count(),
            'total_subscriptions'  => Subscription::count(),
        ];
        $recent_orders   = Order::with('user')->latest()->take(10)->get();
        $recent_customers= User::latest()->take(5)->get();
        $revenue_chart   = collect(range(6,0))->map(fn($d)=>[
            'date'    => now()->subDays($d)->format('d M'),
            'revenue' => Order::where('payment_status','paid')->whereDate('created_at',now()->subDays($d))->sum('total'),
            'orders'  => Order::whereDate('created_at',now()->subDays($d))->count(),
        ]);
        $top_products = DB::table('order_items')
            ->select('name',DB::raw('SUM(quantity) as total_sold'),DB::raw('SUM(subtotal) as revenue'))
            ->groupBy('name')->orderByDesc('total_sold')->take(5)->get();
        $order_status_counts = [
            'pending'    => Order::where('status','pending')->count(),
            'processing' => Order::where('status','processing')->count(),
            'shipped'    => Order::where('status','shipped')->count(),
            'delivered'  => Order::where('status','delivered')->count(),
            'cancelled'  => Order::where('status','cancelled')->count(),
        ];
        $low_stock_products = Product::where('stock_quantity','<=',5)->where('is_active',true)->take(5)->get();
        return view('admin.dashboard',compact('stats','recent_orders','recent_customers','revenue_chart','top_products','order_status_counts','low_stock_products'));
    }
}
