<?php
namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index() { return response()->json(auth()->user()->addresses); }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'line1' => 'required|string|max:255',
            'line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
        ]);

        if ($request->is_default) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }

        $address = auth()->user()->addresses()->create(array_merge($validated, ['country' => 'India']));
        return response()->json($address, 201);
    }

    public function update(Request $request, Address $address)
    {
        $this->authorize('update', $address);
        $address->update($request->only(['label','name','phone','line1','line2','city','state','pincode']));
        return response()->json($address);
    }

    public function destroy(Address $address)
    {
        $this->authorize('delete', $address);
        $address->delete();
        return response()->json(['success' => true]);
    }

    public function setDefault(Address $address)
    {
        $this->authorize('update', $address);
        auth()->user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);
        return response()->json(['success' => true]);
    }
}
