<x-layouts.app title="Contact Us - Dilli Creamery">
<div class="min-h-screen py-16" style="background: linear-gradient(160deg, #f0faf7 0%, white 100%);">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="font-display text-4xl font-bold text-brand-900 mb-4">Get In <span class="gradient-text">Touch</span></h1>
            <p class="text-gray-500 text-lg">We'd love to hear from you. Send us a message!</p>
        </div>
        <div class="grid md:grid-cols-2 gap-12">
            <div class="space-y-6">
                <div class="flex items-center gap-4 p-4 bg-white rounded-2xl shadow-card">
                    <div class="w-12 h-12 rounded-xl bg-brand-100 flex items-center justify-center text-2xl">📞</div>
                    <div><p class="font-semibold text-gray-800">Phone</p><p class="text-gray-500">+91 99999 00000</p></div>
                </div>
                <div class="flex items-center gap-4 p-4 bg-white rounded-2xl shadow-card">
                    <div class="w-12 h-12 rounded-xl bg-brand-100 flex items-center justify-center text-2xl">✉️</div>
                    <div><p class="font-semibold text-gray-800">Email</p><p class="text-gray-500">hello@dillicreamery.in</p></div>
                </div>
                <div class="flex items-center gap-4 p-4 bg-white rounded-2xl shadow-card">
                    <div class="w-12 h-12 rounded-xl bg-brand-100 flex items-center justify-center text-2xl">📍</div>
                    <div><p class="font-semibold text-gray-800">Location</p><p class="text-gray-500">Delhi, India</p></div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-card p-8">
                @if(session('success'))<div class="alert-success mb-6">{{ session('success') }}</div>@endif
                <form method="POST" action="/contact" class="space-y-4">
                    @csrf
                    <div><label class="input-label">Name</label><input type="text" name="name" class="input" required></div>
                    <div><label class="input-label">Email</label><input type="email" name="email" class="input" required></div>
                    <div><label class="input-label">Message</label><textarea name="message" rows="4" class="input resize-none"></textarea></div>
                    <button type="submit" class="btn-primary w-full">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</div>
</x-layouts.app>
