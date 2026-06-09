<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingsController extends Controller {
    public function index() {
        $settings = Setting::pluck('value','key');
        return view('admin.settings.index', compact('settings'));
    }
    public function update(Request $r) {
        foreach($r->except(['_token','_method']) as $key => $value) {
            Setting::updateOrCreate(['key'=>$key],['value'=>$value]);
        }
        return back()->with('success','Settings saved successfully.');
    }
    public function whatsapp() {
        $settings = Setting::whereIn('key',['whatsapp_api_url','whatsapp_token','whatsapp_phone_id','whatsapp_enabled','whatsapp_business_number','wati_api_key','wati_base_url'])->pluck('value','key');
        return view('admin.settings.whatsapp', compact('settings'));
    }
    public function updateWhatsApp(Request $r) {
        $fields = ['whatsapp_api_url','whatsapp_token','whatsapp_phone_id','whatsapp_enabled','whatsapp_business_number','wati_api_key','wati_base_url'];
        foreach($fields as $key) {
            if($r->has($key)) Setting::updateOrCreate(['key'=>$key],['value'=>$r->input($key)]);
        }
        return back()->with('success','WhatsApp settings saved.');
    }
}
