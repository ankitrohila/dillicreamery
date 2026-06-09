<x-layouts.app title="My Profile">
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('customer.dashboard') }}" class="text-gray-400 hover:text-brand-400 text-sm">← Dashboard</a>
            <h1 class="font-display text-2xl font-bold text-brand-900">My Profile</h1>
        </div>

        @if(session('success'))
        <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl mb-5">
            ✅ {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-card p-8">
            <div class="flex items-center gap-5 mb-8">
                <div class="w-20 h-20 rounded-full flex items-center justify-center text-3xl font-bold text-white" style="background: #5B9B8A;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="font-display text-xl font-bold text-gray-800">{{ auth()->user()->name }}</h2>
                    <p class="text-gray-500 text-sm">{{ auth()->user()->email }}</p>
                    <span class="badge-brand text-xs mt-1">{{ ucfirst(auth()->user()->getRoleNames()->first() ?? 'Customer') }}</span>
                </div>
            </div>

            @if($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 rounded-xl mb-5">
                <ul class="text-sm text-red-700 list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-5">
                @csrf @method('PUT')
                <div>
                    <label class="input-label">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required class="input">
                </div>
                <div>
                    <label class="input-label">Email Address</label>
                    <input type="email" value="{{ auth()->user()->email }}" disabled class="input opacity-60 cursor-not-allowed">
                    <p class="text-xs text-gray-400 mt-1">Email cannot be changed. Contact support if needed.</p>
                </div>
                <div>
                    <label class="input-label">Phone Number</label>
                    <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="input" placeholder="+91 98765 43210">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="input-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', auth()->user()->date_of_birth?->format('Y-m-d')) }}" class="input">
                    </div>
                    <div>
                        <label class="input-label">Gender</label>
                        <select name="gender" class="input">
                            <option value="">Prefer not to say</option>
                            <option value="male" {{ old('gender', auth()->user()->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', auth()->user()->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', auth()->user()->gender) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn-primary w-full justify-center">Save Changes</button>
            </form>
        </div>
    </div>
</div>
</x-layouts.app>
