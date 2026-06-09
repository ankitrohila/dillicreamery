<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: true, mobileOpen: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@isset($title){{ $title }} â€” @endisset Dilli Creamery Admin</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sidebar: '#1a1f2e',
                        'sidebar-hover': '#242a3d',
                        'sidebar-active': '#2a3150',
                        gold: '#C9A84C',
                        'gold-light': '#dfc070',
                    }
                }
            }
        }
    </script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f0f2f7; }

        /* Scrollbar */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #2a3150; border-radius: 4px; }

        /* Active sidebar item */
        .nav-item-active {
            background: #2a3150;
            border-left: 3px solid #C9A84C;
            color: #C9A84C !important;
        }
        .nav-item-active .nav-icon { color: #C9A84C !important; }

        .nav-item {
            border-left: 3px solid transparent;
            transition: all 0.15s ease;
        }
        .nav-item:hover {
            background: #242a3d;
            border-left-color: rgba(201,168,76,0.4);
            color: #e2e8f0;
        }

        /* Section headings */
        .nav-section-heading {
            font-size: 0.6rem;
            letter-spacing: 0.12em;
            font-variant: small-caps;
            text-transform: uppercase;
            color: #4a5568;
            font-weight: 600;
        }

        /* Collapsed sidebar */
        .sidebar-collapsed { width: 64px; }
        .sidebar-expanded { width: 260px; }

        .sidebar-text { transition: opacity 0.2s ease, width 0.2s ease; }

        /* Top bar shadow */
        .topbar { box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.04); }

        /* Card style for content area */
        .admin-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }

        /* Notification badge */
        .badge {
            font-size: 0.6rem;
            min-width: 16px;
            height: 16px;
        }

        @media (max-width: 768px) {
            .sidebar-overlay { display: block; }
        }
    </style>
</head>

<body class="min-h-screen flex overflow-hidden">

    {{-- Mobile overlay --}}
    <div
        x-show="mobileOpen"
        x-transition:enter="transition-opacity ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="mobileOpen = false"
        class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden"
        style="display: none;"
    ></div>

    {{-- ================================================================
         SIDEBAR
    ================================================================ --}}
    <aside
        :class="[
            'fixed inset-y-0 left-0 z-30 flex flex-col bg-sidebar transition-all duration-300 ease-in-out',
            'md:relative md:translate-x-0',
            mobileOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0',
            sidebarOpen ? 'sidebar-expanded' : 'sidebar-collapsed md:flex'
        ]"
        style="width: 260px;"
        :style="sidebarOpen ? 'width:260px' : 'width:64px'"
    >

        {{-- Logo --}}
        <div class="flex items-center h-16 px-4 border-b border-gray-800 flex-shrink-0">
            <div class="flex items-center gap-3 min-w-0">
                <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-gold flex items-center justify-center">
                    <i class="fas fa-cow text-sidebar text-sm"></i>
                </div>
                <div x-show="sidebarOpen" class="sidebar-text min-w-0">
                    <div class="text-white font-bold text-sm leading-tight truncate">Dilli Creamery</div>
                    <div class="text-gray-500 text-xs">Admin Panel</div>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto sidebar-scroll py-4 px-2 space-y-0.5">

            @php
                $currentRoute = request()->path();
                $currentUrl   = request()->fullUrl();

                function isActive(string $path): bool {
                    return request()->is(ltrim($path, '/')) || request()->is(ltrim($path, '/') . '/*');
                }
                function isExact(string $path): bool {
                    return '/' . request()->path() === $path || request()->path() === ltrim($path, '/');
                }
                function isUrlActive(string $url): bool {
                    return request()->fullUrl() === url($url) || request()->url() . '?' . request()->getQueryString() === url($url);
                }
            @endphp

            {{-- ---- MAIN ---- --}}
            <div x-show="sidebarOpen" class="nav-section-heading px-2 pt-2 pb-1">Main</div>

            <a href="{{ url('/admin') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ isExact('/admin') ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">Dashboard</span>
            </a>

            {{-- ---- CATALOG ---- --}}
            <div x-show="sidebarOpen" class="nav-section-heading px-2 pt-4 pb-1">Catalog</div>

            <a href="{{ url('/admin/products') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ isActive('/admin/products') && !isActive('/admin/products/categories') ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-box w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">Products</span>
            </a>

            <a href="{{ url('/admin/products/categories') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ isActive('/admin/products/categories') ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-tags w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">Categories</span>
            </a>

            <a href="{{ url('/admin/reviews') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ isActive('/admin/reviews') ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-star w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">Reviews</span>
            </a>

            {{-- ---- ORDERS ---- --}}
            <div x-show="sidebarOpen" class="nav-section-heading px-2 pt-4 pb-1">Orders</div>

            <a href="{{ url('/admin/orders') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ isActive('/admin/orders') && !request()->has('status') ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-shopping-cart w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">All Orders</span>
            </a>

            <a href="{{ url('/admin/orders') }}?status=pending"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ request()->is('admin/orders') && request()->get('status') === 'pending' ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-clock w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">Pending</span>
            </a>

            <a href="{{ url('/admin/orders') }}?status=processing"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ request()->is('admin/orders') && request()->get('status') === 'processing' ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-spinner w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">Processing</span>
            </a>

            <a href="{{ url('/admin/orders') }}?status=delivered"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ request()->is('admin/orders') && request()->get('status') === 'delivered' ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-check-circle w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">Delivered</span>
            </a>

            {{-- ---- SUBSCRIPTIONS ---- --}}
            <div x-show="sidebarOpen" class="nav-section-heading px-2 pt-4 pb-1">Subscriptions</div>

            <a href="{{ url('/admin/subscriptions') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ isActive('/admin/subscriptions') && !isActive('/admin/subscription-plans') && !request()->has('status') ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-sync-alt w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">All Subscriptions</span>
            </a>

            <a href="{{ url('/admin/subscriptions') }}?status=active"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ request()->is('admin/subscriptions') && request()->get('status') === 'active' ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-play-circle w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">Active</span>
            </a>

            <a href="{{ url('/admin/subscription-plans') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ isActive('/admin/subscription-plans') ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-layer-group w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">Plans</span>
            </a>

            {{-- ---- CUSTOMERS ---- --}}
            <div x-show="sidebarOpen" class="nav-section-heading px-2 pt-4 pb-1">Customers</div>

            <a href="{{ url('/admin/customers') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ isActive('/admin/customers') ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-users w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">All Customers</span>
            </a>

            <a href="{{ url('/admin/newsletter') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ isActive('/admin/newsletter') ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-envelope-open-text w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">Newsletter</span>
            </a>

            {{-- ---- MARKETING ---- --}}
            <div x-show="sidebarOpen" class="nav-section-heading px-2 pt-4 pb-1">Marketing</div>

            <a href="{{ url('/admin/coupons') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ isActive('/admin/coupons') ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-ticket-alt w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">Coupons</span>
            </a>

            <a href="{{ url('/admin/offers') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ isActive('/admin/offers') ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-percentage w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">Offers &amp; Banners</span>
            </a>

            <a href="{{ url('/admin/featured') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ isActive('/admin/featured') ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-fire w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">Featured Products</span>
            </a>

            {{-- ---- PAYMENTS ---- --}}
            <div x-show="sidebarOpen" class="nav-section-heading px-2 pt-4 pb-1">Payments</div>

            <a href="{{ url('/admin/payments') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ isActive('/admin/payments') && !isActive('/admin/payments/razorpay') ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-credit-card w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">Transactions</span>
            </a>

            <a href="{{ url('/admin/payments/razorpay') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ isActive('/admin/payments/razorpay') ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-rupee-sign w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">Razorpay</span>
            </a>

            {{-- ---- COMMUNICATIONS ---- --}}
            <div x-show="sidebarOpen" class="nav-section-heading px-2 pt-4 pb-1">Communications</div>

            <a href="{{ url('/admin/whatsapp') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ isActive('/admin/whatsapp') ? 'nav-item-active' : '' }}">
                <i class="nav-icon fab fa-whatsapp w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">WhatsApp</span>
            </a>

            <a href="{{ url('/admin/emails') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ isActive('/admin/emails') ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-envelope w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">Email Logs</span>
            </a>

            <a href="{{ url('/admin/consultancy') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ isActive('/admin/consultancy') ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-calendar-check w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">Consultancy Bookings</span>
            </a>

            {{-- ---- CONTENT ---- --}}
            <div x-show="sidebarOpen" class="nav-section-heading px-2 pt-4 pb-1">Content</div>

            <a href="{{ url('/admin/blog') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ isActive('/admin/blog') ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-blog w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">Blog</span>
            </a>

            <a href="{{ url('/admin/testimonials') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ isActive('/admin/testimonials') ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-quote-left w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">Testimonials</span>
            </a>

            <a href="{{ url('/admin/courses') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ isActive('/admin/courses') ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-graduation-cap w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">Courses</span>
            </a>

            {{-- ---- REPORTS ---- --}}
            <div x-show="sidebarOpen" class="nav-section-heading px-2 pt-4 pb-1">Reports</div>

            <a href="{{ url('/admin/reports/sales') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ isActive('/admin/reports/sales') ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-chart-line w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">Sales Report</span>
            </a>

            <a href="{{ url('/admin/reports/subscriptions') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ isActive('/admin/reports/subscriptions') ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-chart-bar w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">Subscription Report</span>
            </a>

            {{-- ---- SETTINGS ---- --}}
            <div x-show="sidebarOpen" class="nav-section-heading px-2 pt-4 pb-1">Settings</div>

            <a href="{{ url('/admin/settings') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ isActive('/admin/settings') && !isActive('/admin/settings/whatsapp') ? 'nav-item-active' : '' }}">
                <i class="nav-icon fas fa-cog w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">Site Settings</span>
            </a>

            <a href="{{ url('/admin/settings/whatsapp') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded text-gray-400 text-sm font-medium {{ isActive('/admin/settings/whatsapp') ? 'nav-item-active' : '' }}">
                <i class="nav-icon fab fa-whatsapp w-4 text-center flex-shrink-0 text-gray-500"></i>
                <span x-show="sidebarOpen" class="sidebar-text truncate">WhatsApp Config</span>
            </a>

            <div class="pb-4"></div>

        </nav>

        {{-- Sidebar toggle button (desktop) --}}
        <div class="hidden md:flex border-t border-gray-800 p-3 flex-shrink-0">
            <button
                @click="sidebarOpen = !sidebarOpen"
                class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded text-gray-500 hover:text-gray-300 hover:bg-sidebar-hover text-xs transition-colors"
            >
                <i class="fas" :class="sidebarOpen ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
                <span x-show="sidebarOpen" class="sidebar-text">Collapse</span>
            </button>
        </div>

    </aside>

    {{-- ================================================================
         MAIN CONTENT AREA
    ================================================================ --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- ---- TOP BAR ---- --}}
        <header class="topbar bg-white h-16 flex items-center px-4 md:px-6 gap-4 flex-shrink-0 z-10">

            {{-- Mobile hamburger --}}
            <button
                @click="mobileOpen = !mobileOpen"
                class="md:hidden text-gray-500 hover:text-gray-700 p-1.5 rounded"
            >
                <i class="fas fa-bars text-lg"></i>
            </button>

            {{-- Breadcrumb --}}
            <div class="flex-1 min-w-0">
                @isset($breadcrumbs)
                    <nav class="flex items-center gap-1.5 text-sm" aria-label="Breadcrumb">
                        <a href="{{ url('/admin') }}" class="text-gray-400 hover:text-gold transition-colors flex items-center gap-1">
                            <i class="fas fa-home text-xs"></i>
                        </a>
                        @foreach($breadcrumbs as $breadcrumb)
                            <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                            @if($loop->last)
                                <span class="text-gray-700 font-medium truncate">{{ $breadcrumb['label'] }}</span>
                            @else
                                <a href="{{ $breadcrumb['url'] }}" class="text-gray-400 hover:text-gold transition-colors truncate">{{ $breadcrumb['label'] }}</a>
                            @endif
                        @endforeach
                    </nav>
                @else
                    <div class="flex items-center gap-1.5 text-sm">
                        <a href="{{ url('/admin') }}" class="text-gray-400 hover:text-gold transition-colors">
                            <i class="fas fa-home text-xs"></i>
                        </a>
                        @isset($title)
                            <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                            <span class="text-gray-700 font-medium">{{ $title }}</span>
                        @endisset
                    </div>
                @endisset
            </div>

            {{-- Right side actions --}}
            <div class="flex items-center gap-1 md:gap-2">

                {{-- WhatsApp quick link --}}
                <a href="{{ url('/admin/whatsapp') }}"
                   class="relative p-2 rounded-lg text-gray-400 hover:text-green-500 hover:bg-green-50 transition-colors"
                   title="WhatsApp">
                    <i class="fab fa-whatsapp text-lg"></i>
                </a>

                {{-- Notifications --}}
                <div class="relative" x-data="{ notifOpen: false }">
                    <button
                        @click="notifOpen = !notifOpen"
                        class="relative p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors"
                    >
                        <i class="fas fa-bell text-lg"></i>
                        {{-- Badge --}}
                        <span class="badge absolute top-1.5 right-1.5 bg-red-500 text-white rounded-full flex items-center justify-center font-bold">3</span>
                    </button>

                    {{-- Notifications dropdown --}}
                    <div
                        x-show="notifOpen"
                        @click.outside="notifOpen = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 top-full mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden z-50"
                        style="display:none;"
                    >
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                            <span class="font-semibold text-gray-800 text-sm">Notifications</span>
                            <span class="badge bg-red-500 text-white rounded-full px-1.5 py-0.5 text-xs font-bold">3</span>
                        </div>
                        <div class="divide-y divide-gray-50 max-h-72 overflow-y-auto">
                            <a href="{{ url('/admin/orders') }}" class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-shopping-cart text-blue-600 text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm text-gray-700 font-medium">New order received</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Order #1042 â€” â‚¹1,290</p>
                                    <p class="text-xs text-gray-300 mt-0.5">2 minutes ago</p>
                                </div>
                            </a>
                            <a href="{{ url('/admin/subscriptions') }}" class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-sync-alt text-green-600 text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm text-gray-700 font-medium">New subscription</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Monthly Ghee Plan â€” Priya S.</p>
                                    <p class="text-xs text-gray-300 mt-0.5">15 minutes ago</p>
                                </div>
                            </a>
                            <a href="{{ url('/admin/reviews') }}" class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-star text-yellow-500 text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm text-gray-700 font-medium">New review pending</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Buffalo Ghee 900g â€” 5 stars</p>
                                    <p class="text-xs text-gray-300 mt-0.5">1 hour ago</p>
                                </div>
                            </a>
                        </div>
                        <div class="px-4 py-2.5 border-t border-gray-100 text-center">
                            <a href="{{ url('/admin/notifications') }}" class="text-xs text-gold hover:text-gold-light font-medium">View all notifications</a>
                        </div>
                    </div>
                </div>

                {{-- Admin avatar dropdown --}}
                <div class="relative" x-data="{ profileOpen: false }">
                    <button
                        @click="profileOpen = !profileOpen"
                        class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-lg hover:bg-gray-100 transition-colors"
                    >
                        <div class="w-8 h-8 rounded-full bg-sidebar flex items-center justify-center flex-shrink-0">
                            <span class="text-gold font-bold text-sm">
                                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                            </span>
                        </div>
                        <div class="hidden md:block text-left min-w-0">
                            <div class="text-sm font-medium text-gray-700 leading-tight truncate max-w-28">
                                {{ auth()->user()->name ?? 'Admin' }}
                            </div>
                            <div class="text-xs text-gray-400 leading-tight">Administrator</div>
                        </div>
                        <i class="fas fa-chevron-down text-gray-400 text-xs hidden md:block"></i>
                    </button>

                    <div
                        x-show="profileOpen"
                        @click.outside="profileOpen = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 top-full mt-2 w-52 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden z-50"
                        style="display:none;"
                    >
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name ?? 'Admin' }}</p>
                            <p class="text-xs text-gray-400 mt-0.5 truncate">{{ auth()->user()->email ?? 'admin@dillicreamery.com' }}</p>
                        </div>
                        <div class="py-1">
                            <a href="{{ url('/admin/profile') }}"
                               class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-800 transition-colors">
                                <i class="fas fa-user-circle w-4 text-gray-400 text-center"></i>
                                My Profile
                            </a>
                            <a href="{{ url('/admin/settings') }}"
                               class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-800 transition-colors">
                                <i class="fas fa-cog w-4 text-gray-400 text-center"></i>
                                Settings
                            </a>
                            <a href="{{ url('/') }}" target="_blank"
                               class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-800 transition-colors">
                                <i class="fas fa-external-link-alt w-4 text-gray-400 text-center"></i>
                                View Store
                            </a>
                        </div>
                        <div class="py-1 border-t border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors">
                                    <i class="fas fa-sign-out-alt w-4 text-center"></i>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        {{-- ---- PAGE CONTENT ---- --}}
        <main class="flex-1 overflow-y-auto p-4 md:p-6">

            {{-- Flash messages --}}
            @if(session('success'))
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 4000)"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm"
                >
                    <i class="fas fa-check-circle text-green-500"></i>
                    {{ session('success') }}
                    <button @click="show = false" class="ml-auto text-green-400 hover:text-green-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 5000)"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm"
                >
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                    {{ session('error') }}
                    <button @click="show = false" class="ml-auto text-red-400 hover:text-red-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @if(session('warning'))
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 5000)"
                    class="mb-4 flex items-center gap-3 bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg text-sm"
                >
                    <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                    {{ session('warning') }}
                    <button @click="show = false" class="ml-auto text-yellow-400 hover:text-yellow-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    <div class="flex items-center gap-2 font-medium mb-1">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        Please fix the following errors:
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-xs">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Page slot --}}
            {{ $slot }}

        </main>

        {{-- Footer --}}
        <footer class="bg-white border-t border-gray-100 px-6 py-3 flex-shrink-0">
            <div class="flex items-center justify-between text-xs text-gray-400">
                <span>Dilli Creamery Admin &copy; {{ date('Y') }}</span>
                <span>Pure &amp; Authentic Since 2020</span>
            </div>
        </footer>

    </div>

</body>
</html>
