<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show($order)
    {
        $order = Order::with(['items', 'user', 'address'])->findOrFail($order);

        return view('admin.orders.show', compact('order'));
    }

    public function edit($order)
    {
        $order = Order::findOrFail($order);

        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, $order)
    {
        $order = Order::findOrFail($order);

        $order->update($request->except(['_token', '_method']));

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Order updated successfully.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'in:pending,processing,confirmed,shipped,out_for_delivery,delivered,cancelled,refunded'],
        ]);

        $previousStatus = $order->status;
        $order->update(['status' => $request->status]);

        Log::info('Order status updated', [
            'order_id'        => $order->id,
            'order_number'    => $order->order_number,
            'previous_status' => $previousStatus,
            'new_status'      => $request->status,
            'updated_by'      => auth()->id(),
        ]);

        if ($order->user && $order->user->phone) {
            try {
                WhatsAppService::sendOrderUpdate($order->user->phone, $order);
            } catch (\Exception $e) {
                Log::warning('WhatsApp notification failed for order status update', [
                    'order_id' => $order->id,
                    'error'    => $e->getMessage(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Order status updated to ' . ucfirst(str_replace('_', ' ', $request->status)) . '.');
    }

    public function invoice(Order $order)
    {
        $order->load(['items', 'user', 'address']);

        return view('admin.orders.invoice', compact('order'));
    }

    public function sendWhatsApp(Request $request, Order $order)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $phone = optional($order->user)->phone;

        if (!$phone) {
            return redirect()->back()->with('error', 'No phone number found for this customer.');
        }

        try {
            WhatsAppService::sendOrderUpdate($phone, $order, $request->message);

            Log::info('Custom WhatsApp message sent', [
                'order_id' => $order->id,
                'phone'    => $phone,
                'sent_by'  => auth()->id(),
            ]);

            return redirect()->back()->with('success', 'WhatsApp message sent successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to send custom WhatsApp message', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Failed to send WhatsApp message: ' . $e->getMessage());
        }
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}
