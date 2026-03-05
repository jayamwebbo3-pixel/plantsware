<?php

use Illuminate\Support\Facades\Route;

// ================= FRONTEND CONTROLLERS =================
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\Frontend\BlogController as FrontendBlogController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\UserDashboardController;

// ================= AUTH CONTROLLERS =================
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\GoogleController;

// ================= ADMIN CONTROLLERS =================
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\HeaderFooterController;
use App\Http\Controllers\Admin\ProductManagementController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ComboPackController;

// ======================================================
// ================= FRONTEND ROUTES =====================
// ======================================================

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('products', [FrontendProductController::class, 'index'])->name('products.index');
Route::get('product/{slug}', [FrontendProductController::class, 'show'])->name('product.show');

Route::get('categories', [FrontendProductController::class, 'categories'])->name('categories');
Route::get('category/{slug}', [FrontendProductController::class, 'category'])->name('category.show');
Route::get('sub-category/{slug}', [FrontendProductController::class, 'subcategory'])->name('subcategory.show');

// Route::get('blog', [FrontendBlogController::class, 'index'])->name('blog.index');
// Route::get('blog/{slug}', [FrontendBlogController::class, 'show'])->name('blog.show');
// Route::get('blog-categories', [FrontendBlogController::class, 'categories'])->name('blog.categories');
// Route::get('blog-category/{slug}', [FrontendBlogController::class, 'category'])->name('blog.category.show');

// ================= Blog section Fix =====================
Route::get('blog', [App\Http\Controllers\Frontend\BlogController::class, 'allBlogs'])->name('blog.index');  // Home page itself is not loading without it
Route::get('blog/{slug}', [FrontendBlogController::class, 'show'])->name('blog.show');                      // Home page itself is not loading without it
Route::get('blog-categories', [FrontendBlogController::class, 'categories'])->name('blog.categories');      // single blog page is not opeing laravel error without it
// Route::get('blog-category/{slug}', [FrontendBlogController::class, 'category'])->name('blog.category.show'); Not used anywhere as of now 
//================== Blog section Fix =====================

// ================= CART ROUTES =================

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
    Route::post('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{cart}', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
});

// ================= WISHLIST ROUTES =================

Route::prefix('wishlist')->name('wishlist.')->controller(CartController::class)->group(function () {
    Route::get('/', 'wishlist')->name('index');
    Route::post('/add/{product}', 'addToWishlist')->name('add');
    Route::delete('/remove/{product}', 'removeFromWishlist')->name('remove');
});

// ================= CHECKOUT (AUTH REQUIRED) =================

Route::middleware('auth')
    ->prefix('checkout')
    ->name('checkout.')
    ->controller(CheckoutController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/address', 'address')->name('address');
        Route::post('/address', 'saveAddress')->name('saveAddress');
        Route::post('/place-order', 'placeOrder')->name('placeOrder');
        Route::get('/order/{order}/confirmation', 'confirmation')->name('confirmation');
    });

// ================= PAYMENT (AUTH REQUIRED) =================

Route::middleware('auth')
    ->prefix('payment')
    ->name('payment.')
    ->controller(PaymentController::class)
    ->group(function () {
        Route::get('/gateway/{transaction_ref}', 'gateway')->name('gateway');
        Route::post('/callback', 'callback')->name('callback');
    });

// ================= USER DASHBOARD =================

Route::middleware('auth')->group(function () {
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::post('/user/order/{id}/cancel', [UserDashboardController::class, 'cancelOrder'])->name('user.order.cancel');
    Route::post('/user/order/{id}/return', [UserDashboardController::class, 'returnOrder'])->name('user.order.return');
});

// ======================================================
// ================= AUTH ROUTES (USER) ==================
// ======================================================

Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login/otp', [LoginController::class, 'sendOtp'])->name('login.otp');
Route::post('/verify-otp', [LoginController::class, 'verifyOtp'])->name('verify.otp');
Route::post('/set-name', [LoginController::class, 'setName'])->name('set.name')->middleware('auth');

Route::middleware('web')->group(function () {
    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])
        ->name('auth.google');

    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ================= STATIC PAGES =================

Route::view('about', 'view.about')->name('about');
Route::view('privacy-policy', 'view.privacypolicy')->name('privacy-policy');
Route::view('terms-conditions', 'view.termsandconditions')->name('terms-conditions');
Route::view('refund-policy', 'view.refundpolicy')->name('refund-policy');

// ======================================================
// ================= ADMIN ROUTES ========================
// ======================================================

Route::prefix('admin')->name('admin.')->group(function () {

    // ---------- ADMIN AUTH (GUEST) ----------
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])->name('login.post');
    });

    // ---------- ADMIN PROTECTED ----------
    Route::middleware('auth:admin')->group(function () {

        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('subcategories', SubcategoryController::class);
        Route::resource('blogs', BlogController::class);
        Route::resource('sliders', SliderController::class);
        Route::resource('testimonials', TestimonialController::class);

        Route::resource('orders', OrderController::class)->only(['index', 'show']);
        Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])
            ->name('orders.updateStatus');
        Route::get('orders/{order}/invoice', [OrderController::class, 'generateInvoice'])
            ->name('orders.invoice');

        Route::get('users', [UserController::class, 'index'])->name('users.index');

        // for embedded imgs new added line 
        Route::post('ckeditor/upload-image', [App\Http\Controllers\Admin\BlogController::class, 'ckeditorImageUpload'])->name('ckeditor.image.upload');
        Route::post('ckeditor/upload', [App\Http\Controllers\Admin\BlogController::class, 'ckeditorUpload'])
            ->name('ckeditor.upload');
        // end

        Route::get('settings', [HeaderFooterController::class, 'index'])->name('settings');
        Route::post('settings', [HeaderFooterController::class, 'update'])->name('settings.update');

        // Informative Pages Management
        Route::get('pages/{slug}/edit', [\App\Http\Controllers\Admin\PageController::class, 'edit'])->name('pages.edit');
        Route::put('pages/{slug}', [\App\Http\Controllers\Admin\PageController::class, 'update'])->name('pages.update');

        // Product Management Flow
        Route::get('products-management', [ProductManagementController::class, 'categories'])
            ->name('products.management');

        Route::get('categories/{category}/subcategories', [ProductManagementController::class, 'subcategories'])
            ->name('categories.subcategories');

        Route::get('categories/{category}/subcategories_json', function (App\Models\Category $category) {
            return response()->json($category->subcategories);
        })->name('categories.subcategories.json');

        Route::get('categories/{category}/subcategories/create', [ProductManagementController::class, 'createSubcategory'])
            ->name('categories.subcategories.create');

        Route::post('categories/{category}/subcategories', [ProductManagementController::class, 'storeSubcategory'])
            ->name('categories.subcategories.store');

        Route::get('subcategories/{subcategory}/products', [ProductManagementController::class, 'products'])
            ->name('subcategories.products');

        Route::get('combo-packs', [ComboPackController::class, 'index'])->name('combo-packs.index');
        Route::post('combo-packs', [ComboPackController::class, 'store'])->name('combo-packs.store');
        Route::get('combo-packs/{combo}/edit', [ComboPackController::class, 'edit'])->name('combo-packs.edit');
        Route::put('combo-packs/{combo}', [ComboPackController::class, 'update'])->name('combo-packs.update');
        Route::delete('combo-packs/{id}', [ComboPackController::class, 'destroy'])->name('combo-packs.destroy');
        Route::patch('combo-packs/{combo}/status', [ComboPackController::class, 'updateStatus'])->name('combo-packs.update-status');
        Route::get('combo-packs/get-items', [ComboPackController::class, 'getItems'])->name('combo-packs.get-items');
    });
});
