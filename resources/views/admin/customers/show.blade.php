<x-admin.layout title="Customer Profile">

{{-- Header --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-6">
    <div class="flex flex-wrap items-center gap-5">
        <div class="w-16 h-16 rounded-full bg-[#1a1f2e] flex items-center justify-center text-white font-black text-2xl flex-shrink-0">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div class="flex-1">
            <div class="flex items-center gap-3 flex-wrap">
                <h2 class="text-xl font-bold text-slate-800">{{ $user->name }}</h2>
                @foreach($user->roles ?? [] as $role)
                <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded text-xs font-medium">{{ ucfirst($role->name) }}</span>
                @endforeach
            </div>
            <div class="flex flex-wrap gap-4 mt-2 text-sm text-slate-500">
                <span><i class="fa fa-envelope mr-1"></i> {{ $user->email }}</span>
                @if($user->phone)<span><i class="fa fa-phone mr-1"></i> {{ $user->phone }}</span>@endif
                <span><i class="fa fa-calendar mr-1"></i> Joined {{ $user->created_at->format('d F Y') }}</span>
            </div>
        </div>
        <div class="text-right">
            <div class="text-2xl font-bold text-[#C9A84C]">₹{{ number_format($user->total_spent ?? 0, 0) }}</div>
            <div class="text-xs text-slate-500 mt-1">Total Spent</div>
        </div>
        <div class="ml-2">
            <a href="{{ route('admin.customers.index') }}"
               class="border border-slate-200 text-slate-600 px-4 py-2 rounded-lg text-sm hover:bg-slate-50 transition-colors">
                <i class="fa fa-arrow-left mr-1"></i> Back
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Tabs Area --}}
    <div class="lg:col-span-2" x-data="{ tab: 'orders' }">

        {{-- Tab Nav --}}
        <div class="flex gap-1 mb-4 bg-white rounded-xl shadow-sm border border-slate-100 p-1.5">
            @foreach(['orders'=>'Orders','subscriptions'=>'Subscriptions','addresses'=>'Addresses','wishlist'=>'Wishlist'] as $key => $label)
            <button @click="tab='{{ $key }}'"
                    :class="tab==='{{ $key }}' ? 'bg-[#1a1f2e] text-white' : 'text-slate-600 hover:bg-slate-100'"
                    class="flex-1 py-2 rounded-lg text-sm font-medium transition-colors">
                {{ $label }}
            </button>
            @endforeach
        </div>

        {{-- Orders Tab --}}
        <div x-show="tab==='orders'">
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b font-bold text-slate-800">Orders</div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase">
                            <th class="px-4 py-3 text-left">Order#</th>
                            <th class="px-4 py-3 text-right">Amount</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Date</th>
                            <th class="px-4 py-3 text-center">View</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($user->orders ?? [] as $order)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-mono font-bold text-[#C9A84C]">#{{ $order->id }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-slate-800">₹{{ number_format($order->total_amount, 0) }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">{{ ucfirst(str_replace('_',' ',$order->status)) }}</span>
                            </td>
                            <td class="px-4 py-3 text-center text-xs text-slate-500">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:underline text-xs">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-slate-400">No orders yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Subscriptions Tab --}}
        <div x-show="tab==='subscriptions'" x-cloak>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b font-bold text-slate-800">Subscriptions</div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase">
                            <th class="px-4 py-3 text-left">Sub#</th>
                            <th class="px-4 py-3 text-left">Plan</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Next Delivery</th>
                            <th class="px-4 py-3 text-center">View</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($user->subscriptions ?? [] as $sub)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-mono font-bold text-[#C9A84C]">#{{ $sub->id }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $sub->plan?->name ?? 'Custom' }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $sub->status==='active' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-700' }}">{{ ucfirst($sub->status) }}</span>
                            </td>
                            <td class="px-4 py-3 text-center text-xs text-slate-500">
                                {{ $sub->next_delivery_date ? \Carbon\Carbon::parse($sub->next_delivery_date)->format('d M Y') : 'N/A' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('admin.subscriptions.show', $sub) }}" class="text-blue-600 hover:underline text-xs">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-slate-400">No subscriptions</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Addresses Tab --}}
        <div x-show="tab==='addresses'" x-cloak>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <h4 class="font-bold text-slate-800 mb-4">Saved Addresses</h4>
                @forelse($user->addresses ?? [] as $address)
                <div class="p-4 border border-slate-200 rounded-lg mb-3 text-sm text-slate-700">
                    <div class="font-semibold text-slate-800">{{ $address->name }}</div>
                    <div>{{ $address->line1 }}{{ $address->line2 ? ', '.$address->line2 : '' }}</div>
                    <div>{{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}</div>
                    @if($address->is_default)<span class="text-xs text-[#C9A84C] font-medium">Default</span>@endif
                </div>
                @empty
                <p class="text-slate-400 text-sm">No addresses saved</p>
                @endforelse
            </div>
        </div>

        {{-- Wishlist Tab --}}
        <div x-show="tab==='wishlist'" x-cloak>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <h4 class="font-bold text-slate-800 mb-4">Wishlist Items</h4>
                @forelse($user->wishlist ?? [] as $item)
                <div class="flex items-center gap-3 py-3 border-b last:border-0">
                    <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center text-xl">🧈</div>
                    <div class="flex-1">
                        <div class="font-medium text-slate-800">{{ $item->product?->name }}</div>
                        <div class="text-xs text-slate-500">₹{{ number_format($item->product?->price ?? 0, 0) }}</div>
                    </div>
                </div>
                @empty
                <p class="text-slate-400 text-sm">No wishlist items</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Right: WhatsApp Send --}}
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5">
            <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fa-brands fa-whatsapp text-green-500"></i> Send WhatsApp
            </h4>
            @if($user->phone)
            <form action="{{ route('admin.whatsapp.send') }}" method="POST" class="space-y-3">
                @csrf
                <input type="hidden" name="phone" value="{{ $user->phone }}">
                <div class="bg-slate-50 rounded-lg px-3 py-2 text-sm text-slate-600">
                    <i class="fa fa-phone mr-1"></i> {{ $user->phone }}
                </div>
                <textarea name="message" rows="4"
                          class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#C9A84C] resize-none">Hi {{ $user->name }}, greetings from Dilli Creamery!</textarea>
                <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                    <i class="fa-brands fa-whatsapp mr-1"></i> Send Message
                </button>
            </form>
            @else
            <p class="text-sm text-slate-400">No phone number on file for this customer.</p>
            @endif
        </div>

        {{-- Quick Stats --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5">
            <h4 class="font-bold text-slate-800 mb-4">Quick Stats</h4>
            <div class="space-y-3">
                @php
                    $stats = [
                        ['label' => 'Total Orders', 'value' => $user->orders?->count() ?? 0, 'icon' => 'fa-box-open', 'color' => 'text-blue-600'],
                        ['label' => 'Active Subscriptions', 'value' => $user->subscriptions?->where('status','active')->count() ?? 0, 'icon' => 'fa-rotate', 'color' => 'text-green-600'],
                        ['label' => 'Total Spent', 'value' => '₹'.number_format($user->total_spent ?? 0, 0), 'icon' => 'fa-indian-rupee-sign', 'color' => 'text-[#C9A84C]'],
                    ];
                @endphp
                @foreach($stats as $stat)
                <div class="flex items-center justify-between py-2 border-b last:border-0">
                    <div class="flex items-center gap-2 text-slate-600 text-sm">
                        <i class="fa {{ $stat['icon'] }} {{ $stat['color'] }} w-4 text-center"></i>
                        {{ $stat['label'] }}
                    </div>
                    <span class="font-bold text-slate-800">{{ $stat['value'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

</x-admin.layout>
