<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Dilli Creamery') }} - Delhi's Finest Dairy</title>
    <meta name="description" content="{{ $metaDescription ?? 'Dilli Creamery – Premium fresh dairy products delivered to your door. Farm-fresh milk, paneer, dahi, ghee, butter and more from Delhi\'s most trusted dairy brand.' }}">
    <meta name="keywords" content="{{ $metaKeywords ?? 'fresh milk delhi, paneer, dahi, ghee, dairy delivery, dilli creamery' }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $title ?? 'Dilli Creamery' }} - Delhi's Finest Dairy">
    <meta property="og:description" content="{{ $metaDescription ?? 'Premium fresh dairy products delivered to your door.' }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('images/og-default.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">

    {{-- Preconnect for fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    @stack('styles')
</head>
<body class="bg-white" x-data="{ mobileMenuOpen: false, cartOpen: false }">

{{-- Flash Messages --}}
@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
     class="fixed top-4 right-4 z-50 max-w-sm">
    <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-800 rounded-2xl shadow-lg">
        <svg class="w-5 h-5 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <p class="text-sm font-medium">{{ session('success') }}</p>
        <button @click="show = false" class="ml-auto text-green-400 hover:text-green-600">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
        </button>
    </div>
</div>
@endif
@if(session('error'))
<div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
     class="fixed top-4 right-4 z-50 max-w-sm">
    <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl shadow-lg">
        <svg class="w-5 h-5 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        <p class="text-sm font-medium">{{ session('error') }}</p>
    </div>
</div>
@endif

{{-- ======= HEADER ======= --}}
<header class="sticky top-0 z-40 glass border-b border-white/50 shadow-sm" x-data="{ scrolled: false }" @scroll.window="scrolled = window.scrollY > 20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 md:h-20">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                <div class="w-10 h-10 md:w-12 md:h-12 rounded-full overflow-hidden shadow-brand ring-2 ring-brand-100">
                    <img src="{{ asset('images/logo.png') }}" alt="Dilli Creamery" class="w-full h-full object-cover"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                    <div style="display:none" class="w-full h-full bg-brand-400 rounded-full items-center justify-center">
                        <span class="text-white font-display font-bold text-lg">DC</span>
                    </div>
                </div>
                <div class="hidden sm:block">
                    <span class="font-display font-bold text-xl text-brand-900">Dilli Creamery</span>
                    <p class="text-xs text-brand-500 -mt-0.5 font-medium">Delhi's Finest Dairy</p>
                </div>
            </a>

            {{-- Desktop Navigation --}}
            <nav class="hidden lg:flex items-center gap-8">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                <a href="{{ route('shop.index') }}" class="nav-link {{ request()->routeIs('shop.*') ? 'active' : '' }}">Shop</a>
                <a href="{{ route('subscriptions.plans') }}" class="nav-link {{ request()->routeIs('subscriptions.*') ? 'active' : '' }}">Subscribe</a>
                <a href="{{ route('consultancy.index') }}" class="nav-link {{ request()->routeIs('consultancy.*') ? 'active' : '' }}">Consultancy</a>
                <a href="{{ route('blog.index') }}" class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}">Blog</a>
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a>
            </nav>

            {{-- Right side actions --}}
            <div class="flex items-center gap-3">
                {{-- Search --}}
                <button class="hidden md:flex w-9 h-9 items-center justify-center rounded-full text-gray-500 hover:text-brand-400 hover:bg-brand-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>

                {{-- Wishlist --}}
                @auth
                <a href="{{ route('wishlist.index') }}" class="hidden md:flex w-9 h-9 items-center justify-center rounded-full text-gray-500 hover:text-brand-400 hover:bg-brand-50 transition-colors relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </a>
                @endauth

                {{-- Cart --}}
                <a href="{{ route('cart.index') }}" class="relative flex w-9 h-9 items-center justify-center rounded-full text-gray-500 hover:text-brand-400 hover:bg-brand-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    @livewire('cart.cart-icon')
                </a>

                {{-- User Menu --}}
                @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-full hover:bg-brand-50 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-brand-400 flex items-center justify-center text-white font-semibold text-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <svg class="w-4 h-4 text-gray-400 hidden md:block" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-transition
                         class="absolute right-0 top-full mt-2 w-52 bg-white rounded-2xl shadow-card-hover border border-gray-100 py-2 z-50">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            Dashboard
                        </a>
                        <a href="{{ route('customer.orders') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            My Orders
                        </a>
                        <a href="{{ route('customer.subscriptions') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Subscriptions
                        </a>
                        <div class="border-t border-gray-100 mt-1 pt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="hidden md:flex btn-primary btn-sm">Login</a>
                @endauth

                {{-- Mobile menu button --}}
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden flex w-9 h-9 items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 transition-colors">
                    <svg x-show="!mobileMenuOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileMenuOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="lg:hidden bg-white border-t border-gray-100 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4 space-y-1">
            <a href="{{ route('home') }}" class="block px-4 py-2.5 rounded-xl text-gray-700 font-medium hover:bg-brand-50 hover:text-brand-600 transition-colors">Home</a>
            <a href="{{ route('shop.index') }}" class="block px-4 py-2.5 rounded-xl text-gray-700 font-medium hover:bg-brand-50 hover:text-brand-600 transition-colors">Shop</a>
            <a href="{{ route('subscriptions.plans') }}" class="block px-4 py-2.5 rounded-xl text-gray-700 font-medium hover:bg-brand-50 hover:text-brand-600 transition-colors">Subscribe & Save</a>
            <a href="{{ route('consultancy.index') }}" class="block px-4 py-2.5 rounded-xl text-gray-700 font-medium hover:bg-brand-50 hover:text-brand-600 transition-colors">Consultancy</a>
            <a href="{{ route('blog.index') }}" class="block px-4 py-2.5 rounded-xl text-gray-700 font-medium hover:bg-brand-50 hover:text-brand-600 transition-colors">Blog</a>
            <a href="{{ route('about') }}" class="block px-4 py-2.5 rounded-xl text-gray-700 font-medium hover:bg-brand-50 hover:text-brand-600 transition-colors">About</a>
            <div class="pt-2 border-t border-gray-100">
                @auth
                <a href="{{ route('customer.dashboard') }}" class="block px-4 py-2.5 rounded-xl text-brand-600 font-semibold hover:bg-brand-50 transition-colors">My Account</a>
                @else
                <a href="{{ route('login') }}" class="block btn-primary text-center mt-2">Login / Register</a>
                @endauth
            </div>
        </div>
    </div>
</header>

{{-- ======= MAIN CONTENT ======= --}}
<main>
    {{ $slot }}
</main>

{{-- ======= FOOTER ======= --}}
<footer class="bg-brand-900 text-white">
    {{-- Newsletter bar --}}
    <div class="bg-gradient-to-r from-brand-800 to-brand-700 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h3 class="font-display text-2xl font-bold text-white">Stay Fresh with Us</h3>
                    <p class="text-brand-200 mt-1">Subscribe for recipes, offers & dairy tips delivered weekly.</p>
                </div>
                @livewire('newsletter.subscribe-form')
            </div>
        </div>
    </div>

    {{-- Main footer --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-12">
            {{-- Brand --}}
            <div class="lg:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-brand-400 flex items-center justify-center">
                        <span class="text-white font-display font-bold text-xl">DC</span>
                    </div>
                    <div>
                        <span class="font-display font-bold text-xl text-white">Dilli Creamery</span>
                        <p class="text-brand-300 text-xs">Delhi's Finest Dairy</p>
                    </div>
                </div>
                <p class="text-brand-300 text-sm leading-relaxed mb-6">
                    Bringing the purity of farm-fresh dairy to Delhi homes since 2018. Every drop is tested, every product is pure.
                </p>
                {{-- Social --}}
                <div class="flex items-center gap-3">
                    <a href="#" class="w-9 h-9 rounded-full bg-brand-700 hover:bg-brand-400 transition-colors flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full bg-brand-700 hover:bg-pink-500 transition-colors flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full bg-brand-700 hover:bg-green-500 transition-colors flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Products --}}
            <div>
                <h4 class="font-semibold text-white mb-4">Products</h4>
                <ul class="space-y-2.5">
                    @foreach(['Fresh Milk', 'Paneer', 'Dahi & Yogurt', 'Ghee', 'Butter', 'Sweets', 'Namkeen', 'Frozen'] as $cat)
                    <li><a href="{{ route('shop.index') }}?category={{ Str::slug($cat) }}" class="text-brand-300 hover:text-white text-sm transition-colors">{{ $cat }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Services --}}
            <div>
                <h4 class="font-semibold text-white mb-4">Services</h4>
                <ul class="space-y-2.5">
                    <li><a href="{{ route('subscriptions.plans') }}" class="text-brand-300 hover:text-white text-sm transition-colors">Daily Subscription</a></li>
                    <li><a href="{{ route('consultancy.index') }}" class="text-brand-300 hover:text-white text-sm transition-colors">Dairy Consultancy</a></li>
                    <li><a href="{{ route('gifting.index') }}" class="text-brand-300 hover:text-white text-sm transition-colors">Corporate Gifting</a></li>
                    <li><a href="{{ route('courses.index') }}" class="text-brand-300 hover:text-white text-sm transition-colors">Courses</a></li>
                    <li><a href="{{ route('blog.index') }}" class="text-brand-300 hover:text-white text-sm transition-colors">Blog</a></li>
                </ul>
            </div>

            {{-- Company --}}
            <div>
                <h4 class="font-semibold text-white mb-4">Company</h4>
                <ul class="space-y-2.5">
                    <li><a href="{{ route('about') }}" class="text-brand-300 hover:text-white text-sm transition-colors">About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="text-brand-300 hover:text-white text-sm transition-colors">Contact</a></li>
                    <li><a href="{{ route('privacy') }}" class="text-brand-300 hover:text-white text-sm transition-colors">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}" class="text-brand-300 hover:text-white text-sm transition-colors">Terms of Use</a></li>
                    <li><a href="{{ route('refund-policy') }}" class="text-brand-300 hover:text-white text-sm transition-colors">Refund Policy</a></li>
                </ul>
                <div class="mt-5">
                    <p class="text-brand-300 text-xs">📞 +91 99999 00000</p>
                    <p class="text-brand-300 text-xs mt-1">✉️ hello@dillicreamery.in</p>
                </div>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="border-t border-brand-800 mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-brand-400 text-sm">© {{ date('Y') }} Dilli Creamery. All rights reserved.</p>
            <div class="flex items-center gap-3">
                <span class="text-brand-400 text-xs">Secure Payments:</span>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-1 bg-brand-800 rounded text-xs text-brand-300 font-medium">Razorpay</span>
                    <span class="px-2 py-1 bg-brand-800 rounded text-xs text-brand-300 font-medium">UPI</span>
                    <span class="px-2 py-1 bg-brand-800 rounded text-xs text-brand-300 font-medium">Cards</span>
                </div>
            </div>
        </div>
    </div>
</footer>

@livewireScripts
@stack('scripts')

<script>
// Scroll reveal
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>
</body>
</html>
