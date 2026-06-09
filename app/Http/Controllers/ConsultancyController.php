<?php
namespace App\Http\Controllers;

use App\Models\ConsultancyLead;
use App\Models\ConsultancyService;
use App\Models\ConsultancyPackage;
use Illuminate\Http\Request;

class ConsultancyController extends Controller
{
    public function book()
    {
        $services = ConsultancyService::where('is_active', true)->get();
        $packages = ConsultancyPackage::where('is_active', true)->get();
        return view('consultancy.book', compact('services', 'packages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'business_name' => 'nullable|string|max:255',
            'service_id' => 'nullable|exists:consultancy_services,id',
            'message' => 'nullable|string|max:2000',
        ]);

        ConsultancyLead::create(array_merge($validated, ['source' => 'website', 'status' => 'new']));
        return back()->with('success', 'Thank you! We will contact you within 24 hours.');
    }
}
