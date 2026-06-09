<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ConsultancyBooking;
use App\Models\Testimonial;
use App\Models\Course;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class AdminConsultancyController extends Controller {
    public function __construct(private WhatsAppService $wa) {}
    public function index() {
        $bookings = ConsultancyBooking::with('user')->latest()->paginate(20);
        return view('admin.consultancy.index', compact('bookings'));
    }
    public function updateStatus(Request $r, ConsultancyBooking $booking) {
        $r->validate(['status'=>'required|in:pending,confirmed,completed,cancelled']);
        $booking->update(['status'=>$r->status]);
        return back()->with('success','Booking status updated.');
    }
    public function sendWhatsApp(Request $r, ConsultancyBooking $booking) {
        $msg = $r->input('message','Your consultancy booking has been confirmed. Thank you for choosing Dilli Creamery!');
        $this->wa->sendMessage($booking->phone ?? $booking->user?->phone, $msg);
        return back()->with('success','WhatsApp sent to customer.');
    }
    public function testimonials() {
        $testimonials = Testimonial::latest()->paginate(20);
        return view('admin.testimonials.index', compact('testimonials'));
    }
    public function courses() {
        $courses = Course::withCount('enrollments')->latest()->paginate(20);
        return view('admin.courses.index', compact('courses'));
    }
}
