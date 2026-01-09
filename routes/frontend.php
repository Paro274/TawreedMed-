<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\MedicineController;
use App\Http\Controllers\Frontend\CosmeticsController;
use App\Http\Controllers\Frontend\MedicalSuppliesController;
use App\Http\Controllers\Frontend\CompaniesController;
use App\Http\Controllers\Frontend\CustomerAuthController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Supplier\SupplierAuthController;
use App\Http\Controllers\Supplier\SupplierProductController;
use App\Http\Controllers\Frontend\ProductController;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Frontend\CustomerForgotPasswordController;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

// ============================================
// الصفحة الرئيسية والصفحات الثابتة
// ============================================
Route::get('/', [HomeController::class, 'index'])->name('frontend.home');
Route::get('/about', [AboutController::class, 'index'])->name('frontend.about');
Route::get('/services', [AboutController::class, 'services'])->name('frontend.services');

// ============================================
// مسارات التواصل
// ============================================
Route::get('/contact', [ContactController::class, 'index'])->name('frontend.contact.index');
Route::get('/contact-page', function () {
    return redirect()->route('frontend.contact.index');
})->name('frontend.contact');
Route::post('/contact/send', [ContactController::class, 'send'])->name('frontend.contact.send');
Route::get('/contact-us', function () {
    return redirect()->route('frontend.contact.index');
})->name('contact-us');

// ============================================
// صفحات إضافية (FAQ, Privacy, Terms)
// ============================================
Route::get('/faq', function () {
    return redirect()->route('frontend.contact.index') . '#faq-section';
})->name('frontend.faq');
Route::get('/privacy-policy', function () {
    return view('frontend.privacy');
})->name('frontend.privacy');
Route::get('/terms-and-conditions', function () {
    return view('frontend.terms');
})->name('frontend.terms');

// ============================================
// Newsletter Subscription
// ============================================
Route::post('/newsletter/subscribe', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    return redirect()->back()->with('success', 'تم الاشتراك في النشرة البريدية بنجاح!');
})->name('frontend.newsletter.subscribe');

// ============================================
// مسارات المنتجات حسب الفئة
// ============================================
Route::prefix('medicines')->name('frontend.medicines.')->group(function () {
    Route::get('/', [MedicineController::class, 'index'])->name('index');
    Route::get('/quick-order', [MedicineController::class, 'quickOrder'])->name('quick-order');
    Route::get('/{product}', [MedicineController::class, 'show'])->name('show');
});

Route::prefix('medical-supplies')->name('frontend.medical-supplies.')->group(function () {
    Route::get('/', [MedicalSuppliesController::class, 'index'])->name('index');
    Route::get('/quick-order', [MedicalSuppliesController::class, 'quickOrder'])->name('quick-order');
    Route::get('/{product}', [MedicalSuppliesController::class, 'show'])->name('show');
});

Route::prefix('cosmetics')->name('frontend.cosmetics.')->group(function () {
    Route::get('/', [CosmeticsController::class, 'index'])->name('index');
    Route::get('/quick-order', [CosmeticsController::class, 'quickOrder'])->name('quick-order');
    Route::get('/{product}', [CosmeticsController::class, 'show'])->name('show');
});

// ============================================
// جميع المنتجات
// ============================================
Route::get('/products', [HomeController::class, 'products'])->name('frontend.products.index');
Route::get('/all-products', function () {
    return redirect()->route('frontend.products.index');
})->name('frontend.products');

Route::get('/product/{slug}', [ProductController::class, 'show'])->name('frontend.products.show');
Route::get('/products/compare', [ProductController::class, 'compare'])->name('frontend.products.compare');
Route::get('/products/quick-view/{id}', [ProductController::class, 'quickView'])->name('frontend.products.quick-view');
Route::get('/products/check-availability/{id}', [ProductController::class, 'checkAvailability'])->name('frontend.products.check-availability');
Route::get('/products/reviews/{id}', [ProductController::class, 'getReviews'])->name('frontend.products.reviews');

// ============================================
// مسارات الشركات
// ============================================
Route::prefix('companies')->name('frontend.companies.')->group(function () {
    Route::get('/', [CompaniesController::class, 'index'])->name('index');
    Route::get('/{company}', [CompaniesController::class, 'show'])->name('show');
});

Route::get('/suppliers', function () {
    return redirect()->route('frontend.companies.index');
})->name('frontend.suppliers.index');

Route::get('/suppliers/{id}', function ($id) {
    $supplier = Supplier::findOrFail($id);
    return redirect()->route('frontend.companies.show', $supplier->slug);
})->name('frontend.suppliers.show');

// ============================================
// مسارات السلة (Cart)
// ============================================
Route::prefix('cart')->name('frontend.cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
    Route::get('/info', [CartController::class, 'getCartInfo'])->name('info');
    Route::get('/count', [CartController::class, 'getCartCount'])->name('count');
});

Route::get('/cart', [CartController::class, 'index'])->name('frontend.cart');

// ============================================
// Checkout routes
// ============================================
Route::get('/cart/checkout', [CheckoutController::class, 'index'])->name('frontend.cart.checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('frontend.checkout.store');


// ============================================
// ✅✅✅ مسارات المستخدمين العملاء (تم الإصلاح)
// ============================================
Route::prefix('customer')->name('frontend.customer.')->group(function () {
    
    // 1. تسجيل الدخول والخروج
    Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [CustomerAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [CustomerAuthController::class, 'register'])->name('register.submit');
    
    Route::post('/logout', [CustomerAuthController::class, 'logout'])
        ->middleware('auth:customer')
        ->name('logout');

    // 2. استعادة كلمة المرور (Reset Password)
    // غيرنا password لـ recover-password عشان نمنع التداخل مع أي راوت افتراضي
// داخل customer group

    // 1. طلب الرابط
    Route::get('/recover-password', [CustomerForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/recover-password/email', [CustomerForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    // 2. إعادة التعيين (من الإيميل)
    Route::get('/password/reset/{token}', [CustomerForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [CustomerForgotPasswordController::class, 'reset'])->name('password.update');



    // 3. الصفحات المحمية (تتطلب تسجيل دخول)
    Route::middleware('auth:customer')->group(function () {
        
        Route::get('/profile', function () {
            $customer = Auth::guard('customer')->user();
            return view('frontend.customer.profile', compact('customer'));
        })->name('profile');
        
        Route::post('/profile/update', function (Request $request) {
            $customer = Auth::guard('customer')->user();
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => ['required','string','max:15', Rule::unique('users', 'phone')->ignore($customer->id)],
                'email' => ['required','email','max:255', Rule::unique('users', 'email')->ignore($customer->id)],
            ]);
            
            $customer->update($validated);
            return redirect()->route('frontend.customer.profile')->with('success', 'تم تحديث بياناتك بنجاح!');
        })->name('profile.update');
        
        Route::get('/orders', function () {
            $customer = Auth::guard('customer')->user();
            $orders = \App\Models\Order::where('customer_id', $customer->id)
                ->with('items')
                ->latest()
                ->paginate(10);
            return view('frontend.customer.orders', compact('customer', 'orders'));
        })->name('orders');
        
        Route::get('/orders/{id}/invoice', function ($id) {
            $customer = Auth::guard('customer')->user();
            $order = \App\Models\Order::with(['items.product', 'supplier'])
                ->where('customer_id', $customer->id)
                ->findOrFail($id);
            return view('frontend.customer.invoice', compact('order'));
        })->name('orders.invoice');
    });
});

// مسار عام لتسجيل الدخول (Fallback)
Route::get('/login', function () {
    return redirect()->route('frontend.customer.login');
})->name('login');

// ============================================
// مسارات الموردين (Suppliers)
// ============================================
Route::prefix('supplier')->name('supplier.')->group(function () {
    Route::get('/auth', [SupplierAuthController::class, 'index'])->name('auth');
    Route::get('/login', [SupplierAuthController::class, 'index'])->name('login');
    Route::post('/auth', [SupplierAuthController::class, 'store'])->name('auth.submit');
    Route::post('/login', [SupplierAuthController::class, 'store']);
    Route::post('/register', [SupplierAuthController::class, 'create'])->name('register');
    Route::get('/logout', [SupplierAuthController::class, 'destroy'])->name('logout');
    Route::post('/logout', [SupplierAuthController::class, 'destroy']);
    
    Route::get('/dashboard', function () {
        if (!session()->has('supplier')) {
            return redirect('/supplier/auth')->with('error', 'يرجى تسجيل الدخول أولاً');
        }
        return view('supplier.dashboard', ['supplier' => Supplier::findOrFail(session('supplier'))]);
    })->name('dashboard');
    
    Route::get('/products', function () {
        if (!session()->has('supplier')) {
            return redirect('/supplier/auth')->with('error', 'يرجى تسجيل الدخول أولاً');
        }
        return app(SupplierProductController::class)->index();
    })->name('products.index');
    
    Route::get('/products/create', function () {
        if (!session()->has('supplier')) {
            return redirect('/supplier/auth')->with('error', 'يرجى تسجيل الدخول أولاً');
        }
        return app(SupplierProductController::class)->create();
    })->name('products.create');
    
    Route::post('/products/store', function (Request $request) {
        if (!session()->has('supplier')) {
            return redirect('/supplier/auth')->with('error', 'يرجى تسجيل الدخول أولاً');
        }
        return app(SupplierProductController::class)->store($request);
    })->name('products.store');
    
    Route::get('/products/{id}/edit', function ($id) {
        if (!session()->has('supplier')) {
            return redirect('/supplier/auth')->with('error', 'يرجى تسجيل الدخول أولاً');
        }
        return app(SupplierProductController::class)->edit($id);
    })->name('products.edit');
    
    Route::post('/products/{id}/update', function (Request $request, $id) {
        if (!session()->has('supplier')) {
            return redirect('/supplier/auth')->with('error', 'يرجى تسجيل الدخول أولاً');
        }
        return app(SupplierProductController::class)->update($request, $id);
    })->name('products.update');
    
    Route::get('/products/{id}/delete', function ($id) {
        if (!session()->has('supplier')) {
            return redirect('/supplier/auth')->with('error', 'يرجى تسجيل الدخول أولاً');
        }
        return app(SupplierProductController::class)->destroy($id);
    })->name('products.delete');
    
    Route::get('/get-categories/{type}', [SupplierProductController::class, 'getCategoriesByType']);
});

// ============================================
// البحث
// ============================================
Route::get('/search', function (Request $request) {
    $query = $request->get('q', '');
    $results = [];
    
    if ($query) {
        $products = Product::where('status', 'approved')
            ->where('is_available', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->with('category', 'supplier')
            ->limit(10)
            ->get();
        
        $companies = Supplier::where('status', 'active')
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->limit(5)
            ->get();
        
        $results = compact('products', 'companies');
    }
    
    return view('frontend.search.results', compact('query', 'results'));
})->name('frontend.search');

// ============================================
// SEO Routes
// ============================================
Route::get('/sitemap.xml', function () {
    $content = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    $content .= '  <url><loc>http://localhost:8000/</loc><lastmod>' . date('Y-m-d') . '</lastmod><priority>1.0</priority></url>' . "\n";
    $content .= '  <url><loc>http://localhost:8000/about</loc><lastmod>' . date('Y-m-d') . '</lastmod><priority>0.8</priority></url>' . "\n";
    $content .= '  <url><loc>http://localhost:8000/contact</loc><lastmod>' . date('Y-m-d') . '</lastmod><priority>0.8</priority></url>' . "\n";
    $content .= '</urlset>';
    return response($content, 200)->header('Content-Type', 'text/xml');
})->name('sitemap');

Route::get('/robots.txt', function () {
    $robots = "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /supplier/\nSitemap: http://localhost:8000/sitemap.xml\n";
    return response($robots, 200)->header('Content-Type', 'text/plain');
})->name('robots');


