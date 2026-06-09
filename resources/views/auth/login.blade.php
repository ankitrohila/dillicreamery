<x-layouts.app title="Login - Dilli Creamery">
<div class="min-h-screen flex items-center justify-center py-12 px-4" style="background: linear-gradient(160deg, #f0faf7 0%, #FFF8F0 100%);">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-full bg-brand-400 flex items-center justify-center">
                    <span class="text-white font-display font-bold text-xl">DC</span>
                </div>
                <span class="font-display font-bold text-2xl text-brand-900">Dilli Creamery</span>
            </a>
            <h1 class="font-display text-3xl font-bold text-brand-900">Welcome Back</h1>
            <p class="text-gray-500 mt-2">Sign in to your account</p>
        </div>

        <div class="bg-white rounded-3xl shadow-3d p-8">
            @if($errors->any())
            <div class="alert-error mb-6">
                <ul class="list-disc list-inside text-sm">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <form method="POST" action="/login" class="space-y-5">
                @csrf
                <div>
                    <label class="input-label">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="input" placeholder="you@example.com">
                </div>
                <div>
                    <label class="input-label">Password</label>
                    <input type="password" name="password" required class="input" placeholder="••••••••">
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-brand-400">
                        Remember me
                    </label>
                </div>
                <button type="submit" class="btn-primary w-full btn-lg justify-center">Sign In</button>
            </form>

            <p class="text-center text-gray-500 text-sm mt-6">Don't have an account? <a href="/register" class="text-brand-400 font-semibold hover:text-brand-600">Register here</a></p>
        </div>
    </div>
</div>
</x-layouts.app>
