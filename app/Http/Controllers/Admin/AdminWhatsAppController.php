<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Subscription;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class AdminWhatsAppController extends Controller {
    public function __construct(private WhatsAppService $wa) {}

    public function index() {
        $recent_logs = \App\Models\AuditLog::where('action','like','whatsapp%')->latest()->take(50)->get();
        $stats = [
            'sent_today' => \App\Models\AuditLog::where('action','like','whatsapp%')->whereDate('created_at',today())->count(),
            'sent_week' => \App\Models\AuditLog::where('action','like','whatsapp%')->whereBetween('created_at',[now()->startOfWeek(),now()])->count(),
            'total_customers' => User::whereNotNull('phone')->count(),
        ];
        $customers = User::whereNotNull('phone')->get(['id','name','phone','email']);
        return view('admin.whatsapp.index', compact('recent_logs','stats','customers'));
    }

    public function send(Request $r) {
        $r->validate(['phone'=>'required','message'=>'required|min:5']);
        $result = $this->wa->sendMessage($r->phone, $r->message);
        return back()->with($result ? 'success' : 'error', $result ? 'WhatsApp sent!' : 'Failed to send. Check WhatsApp config.');
    }

    public function blast(Request $r) {
        $r->validate(['message'=>'required|min:10','target'=>'required|in:all,subscribers,customers']);
        $users = match($r->target) {
            'subscribers' => User::role('customer')->whereNotNull('phone')->get(),
            'all' => User::whereNotNull('phone')->get(),
            default => User::whereNotNull('phone')->get(),
        };
        $sent = 0;
        foreach($users->take(100) as $user) {
            if($this->wa->sendMessage($user->phone, $r->message)) $sent++;
        }
        return back()->with('success', "WhatsApp blast sent to {$sent} customers.");
    }

    public function emails() {
        $logs = \App\Models\AuditLog::whereIn('action',['email_sent','order_confirmation','subscription_confirmation'])->latest()->paginate(30);
        return view('admin.whatsapp.emails', compact('logs'));
    }
}
