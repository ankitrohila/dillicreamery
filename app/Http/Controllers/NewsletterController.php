<?php
namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        NewsletterSubscriber::firstOrCreate(
            ['email' => $request->email],
            ['is_active' => true, 'confirmed_at' => now(), 'source' => 'website']
        );

        return back()->with('success', 'Thank you for subscribing!');
    }
}
