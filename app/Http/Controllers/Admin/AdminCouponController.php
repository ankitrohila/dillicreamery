<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminCouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(20);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $r)
    {
        $r->validate([
            'code'       => 'required|string|unique:coupons,code',
            'type'       => 'required|in:percent,fixed',
            'value'      => 'required|numeric|min:0',
            'min_order'  => 'nullable|numeric|min:0',
            'max_uses'   => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'is_active'  => 'boolean',
        ]);

        Coupon::create([
            'code'       => strtoupper($r->code),
            'type'       => $r->type,
            'value'      => $r->value,
            'min_order'  => $r->min_order,
            'max_uses'   => $r->max_uses,
            'expires_at' => $r->expires_at,
            'is_active'  => $r->boolean('is_active', true),
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $r, Coupon $coupon)
    {
        $r->validate([
            'code'       => 'required|string|unique:coupons,code,' . $coupon->id,
            'type'       => 'required|in:percent,fixed',
            'value'      => 'required|numeric|min:0',
            'min_order'  => 'nullable|numeric|min:0',
            'max_uses'   => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'is_active'  => 'boolean',
        ]);

        $coupon->update([
            'code'       => strtoupper($r->code),
            'type'       => $r->type,
            'value'      => $r->value,
            'min_order'  => $r->min_order,
            'max_uses'   => $r->max_uses,
            'expires_at' => $r->expires_at,
            'is_active'  => $r->boolean('is_active', true),
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return back()->with('success', 'Coupon deleted successfully.');
    }

    public function toggle(Coupon $coupon)
    {
        $coupon->update(['is_active' => ! $coupon->is_active]);

        return response()->json([
            'success'   => true,
            'is_active' => $coupon->is_active,
        ]);
    }

    public function offers()
    {
        return view('admin.offers.index');
    }
}
