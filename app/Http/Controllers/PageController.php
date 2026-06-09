<?php
namespace App\Http\Controllers;

use App\Models\ConsultancyService;
use App\Models\FounderAchievement;
use App\Models\Video;

class PageController extends Controller
{
    public function about() { return view('about', ['achievements' => FounderAchievement::orderBy('sort_order')->get()]); }
    public function contact() { return view('contact'); }
    public function privacy() { return view('pages.privacy'); }
    public function terms() { return view('pages.terms'); }
    public function refund() { return view('pages.refund'); }
    public function consultancy() { return view('consultancy.index', ['services' => ConsultancyService::where('is_active', true)->orderBy('sort_order')->get()]); }
    public function gifting() { return view('pages.gifting'); }
    public function courses() { return view('courses.index'); }
}
