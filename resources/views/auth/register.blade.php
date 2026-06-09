<x-layouts.app title="Register - Dilli Creamery">
<div class="min-h-screen flex items-center justify-center py-12 px-4" style="background: linear-gradient(160deg, #f0faf7 0%, #FFF8F0 100%);">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-full bg-brand-400 flex items-center justify-center">
                    <span class="text-white font-display font-bold text-xl">DC</span>
                </div>
                <span class="font-display font-bold text-2xl text-brand-900">Dilli Creamery</span>
            </a>
            <h1 class="font-display text-3xl font-bold text-brand-900">Create Account</h1>
            <p class="text-gray-500 mt-2">Start your dairy journey today</p>
        </div>

        <div class="bg-white rounded-3xl shadow-3d p-8">
            @if($errors->any())
            <div class="alert-error mb-6"><ul class="list-disc list-inside text-sm">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif

            <form method="POST" action="/register" class="space-y-5">
                @csrf
                <div>
                    <label class="input-label">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="input" placeholder="Your full name">
                </div>
                <div>
                    <label class="input-label">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="input" placeholder="you@example.com">
                </div>
                <div>
                    <label class="input-label">Phone Number</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" class="input" placeholder="+91 98765 43210">
                </div>
                <div>
                    <label class="input-label">Password</label>
                    <input type="password" name="password" required class="input" placeholder="Min 8 characters">
                </div>
                <div>
                    <label class="input-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="input" placeholder="Repeat password">
                </div>
                <button type="submit" class="btn-primary w-full btn-lg justify-center">Create Account</button>
            </form>

            <p class="text-center text-gray-500 text-sm mt-6">Already have an account? <a href="/login" class="text-brand-400 font-semibold hover:text-brand-600">Sign in</a></p>
        </div>
    </div>
</div>
</x-layouts.app>
