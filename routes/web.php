<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ConsultancyController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Shop
Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('index');
    Route::get('/{slug}', [ShopController::class, 'show'])->name('show');
});

// Cart
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::put('/{key}', [CartController::class, 'update'])->name('update');
    Route::delete('/{key}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/', [CartController::class, 'clear'])->name('clear');
});

// Subscriptions
Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
    Route::get('/', [SubscriptionController::class, 'plans'])->name('plans');
    Route::get('/build', [SubscriptionController::class, 'build'])->name('build')->middleware('auth');
    Route::post('/', [SubscriptionController::class, 'store'])->name('store')->middleware('auth');
});

// Consultancy
Route::prefix('consultancy')->name('consultancy.')->group(function () {
    Route::get('/', [PageController::class, 'consultancy'])->name('index');
    Route::get('/book', [ConsultancyController::class, 'book'])->name('book');
    Route::post('/book', [ConsultancyController::class, 'store'])->name('store');
});

// Blog
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});

// Static pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/refund-policy', [PageController::class, 'refund'])->name('refund-policy');
Route::get('/corporate-gifting', [PageController::class, 'gifting'])->name('gifting.index');
Route::get('/courses', [PageController::class, 'courses'])->name('courses.index');

// Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Payment webhooks (no CSRF)
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook')->withoutMiddleware(['web']);

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Checkout
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::post('/payment/create-order', [PaymentController::class, 'createOrder'])->name('payment.create');
    Route::post('/payment/verify', [PaymentController::class, 'verify'])->name('payment.verify');
    Route::post('/payment/upi-confirm', [PaymentController::class, 'upiConfirm'])->name('payment.upi-confirm');

    // Customer portal
    Route::prefix('account')->name('customer.')->group(function () {
        Route::get('/', [CustomerController::class, 'dashboard'])->name('dashboard');
        Route::get('/orders', [CustomerController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [CustomerController::class, 'showOrder'])->name('order.show');
        Route::get('/subscriptions', [CustomerController::class, 'subscriptions'])->name('subscriptions');
        Route::get('/subscriptions/{subscription}', [CustomerController::class, 'showSubscription'])->name('subscription.show');
        Route::post('/subscriptions/{subscription}/pause', [SubscriptionController::class, 'pause'])->name('subscription.pause');
        Route::post('/subscriptions/{subscription}/resume', [SubscriptionController::class, 'resume'])->name('subscription.resume');
        Route::post('/subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
        Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
        Route::put('/profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
        Route::get('/invoices', [CustomerController::class, 'invoices'])->name('invoices');
    });

    // Wishlist
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('index');
        Route::post('/{product}', [WishlistController::class, 'add'])->name('add');
        Route::delete('/{product}', [WishlistController::class, 'remove'])->name('remove');
    });

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Addresses
    Route::apiResource('addresses', AddressController::class);
    Route::post('/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('addresses.default');
});

// ============ ADMIN PANEL ============
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminSubscriptionController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminCouponController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminWhatsAppController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminBlogController;
use App\Http\Controllers\Admin\AdminConsultancyController;

Route::prefix('admin')->name('admin.')->middleware(['web','auth','admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Orders
    Route::resource('orders', AdminOrderController::class)->except(['create','store']);
    Route::post('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('orders/{order}/invoice', [AdminOrderController::class, 'invoice'])->name('orders.invoice');
    Route::post('orders/{order}/whatsapp', [AdminOrderController::class, 'sendWhatsApp'])->name('orders.whatsapp');

    // Products
    Route::resource('products', AdminProductController::class);
    Route::post('products/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('products.featured');
    Route::post('products/{product}/toggle-active', [AdminProductController::class, 'toggleActive'])->name('products.toggle');
    Route::get('products/categories', [AdminProductController::class, 'categories'])->name('products.categories');
    Route::get('featured', [AdminProductController::class, 'featured'])->name('featured');
    Route::post('products/{product}/images/{image}/set-main', [AdminProductController::class, 'setMainImage'])->name('products.images.set-main');
    Route::delete('products/{product}/images/{image}', [AdminProductController::class, 'deleteImage'])->name('products.images.delete');
    Route::delete('products/{product}/variations/{variation}', [AdminProductController::class, 'deleteVariation'])->name('products.variations.delete');

    // Subscriptions
    Route::get('subscriptions', [AdminSubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('subscriptions/{subscription}', [AdminSubscriptionController::class, 'show'])->name('subscriptions.show');
    Route::post('subscriptions/{subscription}/status', [AdminSubscriptionController::class, 'updateStatus'])->name('subscriptions.status');
    Route::post('subscriptions/{subscription}/whatsapp', [AdminSubscriptionController::class, 'sendWhatsApp'])->name('subscriptions.whatsapp');
    Route::get('subscription-plans', [AdminSubscriptionController::class, 'plans'])->name('subscription-plans');
    Route::post('subscription-plans', [AdminSubscriptionController::class, 'storePlan'])->name('subscription-plans.store');
    Route::put('subscription-plans/{plan}', [AdminSubscriptionController::class, 'updatePlan'])->name('subscription-plans.update');
    Route::delete('subscription-plans/{plan}', [AdminSubscriptionController::class, 'destroyPlan'])->name('subscription-plans.destroy');

    // Customers
    Route::get('customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/{user}', [AdminCustomerController::class, 'show'])->name('customers.show');
    Route::post('customers/{user}/whatsapp', [AdminCustomerController::class, 'sendWhatsApp'])->name('customers.whatsapp');
    Route::get('newsletter', [AdminCustomerController::class, 'newsletter'])->name('newsletter');

    // Coupons
    Route::resource('coupons', AdminCouponController::class);
    Route::post('coupons/{coupon}/toggle', [AdminCouponController::class, 'toggle'])->name('coupons.toggle');
    Route::get('offers', [AdminCouponController::class, 'offers'])->name('offers');

    // Payments
    Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/razorpay', [AdminPaymentController::class, 'razorpay'])->name('payments.razorpay');
    Route::get('payments/{id}', [AdminPaymentController::class, 'show'])->name('payments.show');

    // WhatsApp
    Route::get('whatsapp', [AdminWhatsAppController::class, 'index'])->name('whatsapp.index');
    Route::post('whatsapp/send', [AdminWhatsAppController::class, 'send'])->name('whatsapp.send');
    Route::post('whatsapp/blast', [AdminWhatsAppController::class, 'blast'])->name('whatsapp.blast');
    Route::get('emails', [AdminWhatsAppController::class, 'emails'])->name('emails');

    // Reports
    Route::get('reports/sales', [AdminReportController::class, 'sales'])->name('reports.sales');
    Route::get('reports/subscriptions', [AdminReportController::class, 'subscriptions'])->name('reports.subscriptions');
    Route::get('reports/export', [AdminReportController::class, 'export'])->name('reports.export');

    // Blog
    Route::resource('blog', AdminBlogController::class);

    // Consultancy
    Route::get('consultancy', [AdminConsultancyController::class, 'index'])->name('consultancy.index');
    Route::post('consultancy/{booking}/status', [AdminConsultancyController::class, 'updateStatus'])->name('consultancy.status');
    Route::post('consultancy/{booking}/whatsapp', [AdminConsultancyController::class, 'sendWhatsApp'])->name('consultancy.whatsapp');

    // Testimonials
    Route::get('testimonials', [AdminConsultancyController::class, 'testimonials'])->name('testimonials');

    // Settings
    Route::get('settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [AdminSettingsController::class, 'update'])->name('settings.update');
    Route::get('settings/whatsapp', [AdminSettingsController::class, 'whatsapp'])->name('settings.whatsapp');
    Route::post('settings/whatsapp', [AdminSettingsController::class, 'updateWhatsApp'])->name('settings.whatsapp.update');

    // Reviews
    Route::get('reviews', [AdminProductController::class, 'reviews'])->name('reviews');
    Route::post('reviews/{review}/approve', [AdminProductController::class, 'approveReview'])->name('reviews.approve');
    Route::delete('reviews/{review}', [AdminProductController::class, 'destroyReview'])->name('reviews.destroy');

    // Courses
    Route::get('courses', [AdminConsultancyController::class, 'courses'])->name('courses');
});

// Auth routes (Laravel Breeze/Jetstream default or manual)
Route::get('/login', fn() => view('auth.login'))->name('login')->middleware('guest');
Route::get('/register', fn() => view('auth.register'))->name('register')->middleware('guest');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'store'])->name('login.store')->middleware('guest');
Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'store'])->name('register.store')->middleware('guest');
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'destroy'])->name('logout')->middleware('auth');
