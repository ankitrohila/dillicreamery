<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller {
    public function sales(Request $r) {
        $period = $r->get('period','month');
        $start = match($period) { 'week'=>now()->startOfWeek(), 'year'=>now()->startOfYear(), default=>now()->startOfMonth() };
        $revenue = Order::where('payment_status','paid')->where('created_at','>=',$start)->sum('total');
        $orders = Order::where('created_at','>=',$start)->count();
        $avg_order = $orders > 0 ? $revenue/$orders : 0;
        $by_day = Order::where('payment_status','paid')->where('created_at','>=',$start)
            ->selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as orders')
            ->groupBy('date')->orderBy('date')->get();
        $top_products = DB::table('order_items')->select('name',DB::raw('SUM(quantity) as qty'),DB::raw('SUM(subtotal) as rev'))->groupBy('name')->orderByDesc('rev')->take(10)->get();
        return view('admin.reports.sales', compact('revenue','orders','avg_order','by_day','top_products','period'));
    }
    public function subscriptions(Request $r) {
        $active = Subscription::where('status','active')->count();
        $mrr = Subscription::where('status','active')->with('plan')->get()->sum(fn($s) => $s->plan?->monthly_price ?? 0);
        $by_plan = Subscription::with('plan')->selectRaw('plan_id, status, COUNT(*) as count')->groupBy('plan_id','status')->get();
        $churn = Subscription::where('status','cancelled')->whereMonth('updated_at',now()->month)->count();
        return view('admin.reports.subscriptions', compact('active','mrr','by_plan','churn'));
    }
    public function export(Request $r) {
        $orders = Order::with(['user','items'])->latest()->take(1000)->get();
        $csv = "Order#,Customer,Email,Items,Total,Status,Payment,Date\n";
        foreach($orders as $o) {
            $items = $o->items->pluck('name')->implode(' | ');
            $csv .= "{$o->order_number},{$o->user?->name},{$o->user?->email},\"$items\",{$o->total},{$o->status},{$o->payment_status},{$o->created_at->format('d/m/Y')}\n";
        }
        return response($csv,200,['Content-Type'=>'text/csv','Content-Disposition'=>'attachment;filename=orders-export.csv']);
    }
}
