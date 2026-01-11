<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Supplier\SupplierAuthController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Supplier\SupplierProductController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\SliderController; 
use App\Http\Controllers\Admin\StatisticController; 
use App\Http\Controllers\Admin\FeatureController; 
use App\Http\Controllers\Admin\AboutSectionController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Models\Supplier;
use App\Http\Controllers\Admin\CtaSectionController;
use App\Http\Controllers\Admin\ContactInfoController;
use App\Http\Controllers\Admin\OurStoryController;
use App\Http\Controllers\Admin\MissionVisionValuesController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\JourneyController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\CertificateAwardController;
use App\Http\Controllers\Admin\ContactInfo2Controller;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\MapLocationController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Supplier\OrderController as SupplierOrderController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Supplier\SupplierForgotPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ========================
// مسار تسجيل الدخول العام
// ========================
Route::get('/login', function() {
    return redirect('/admin/login');
})->name('login');

// ========================
// مسارات الأدمن - Authentication
// ========================
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm']);
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::get('/admin/logout', [AdminAuthController::class, 'logout']);

Route::get('/admin/dashboard', function () {
    if (!session()->has('admin')) {
        return redirect('/admin/login');
    }
    return view('admin.dashboard');
})->name('admin.dashboard');

// مسار جلب التصنيفات (AJAX)
Route::get('/admin/get-categories/{type}', [AdminProductController::class, 'getCategoriesByType']);
Route::get('/supplier/get-categories/{type}', [SupplierProductController::class, 'getCategoriesByType']);

// ========================
// ✅ إدارة الأدمنز (Super Admin)
// ========================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('admins', [AdminController::class, 'index'])->name('admins.index');
    Route::get('admins/create', [AdminController::class, 'create'])->name('admins.create');
    Route::post('admins', [AdminController::class, 'store'])->name('admins.store');
    Route::get('admins/{id}/edit', [AdminController::class, 'edit'])->name('admins.edit');
    Route::post('admins/{id}', [AdminController::class, 'update'])->name('admins.update');
    Route::delete('admins/{id}', [AdminController::class, 'destroy'])->name('admins.destroy');
});

// ========================
// مسارات الأدمن (محمية بالصلاحيات)
// ========================

// 1. التصنيفات
Route::prefix('admin/categories')->middleware('admin.permission:categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/store', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::post('/{category}/update', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::get('/{category}/delete', [CategoryController::class, 'destroy'])->name('admin.categories.delete');
});

// 2. المنتجات
Route::prefix('admin/products')->middleware('admin.permission:products')->group(function () {
    Route::get('/', [AdminProductController::class, 'index'])->name('admin.products.index');
    Route::get('/create', [AdminProductController::class, 'create'])->name('admin.products.create');
    Route::post('/store', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::get('/{id}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
    Route::post('/{id}/update', [AdminProductController::class, 'update'])->name('admin.products.update');
    Route::get('/{id}/approve', [AdminProductController::class, 'approve'])->name('admin.products.approve');
    Route::get('/{id}/reject', [AdminProductController::class, 'reject'])->name('admin.products.reject');
    Route::delete('/{id}/delete', [AdminProductController::class, 'destroy'])->name('admin.products.delete');
});

// إضافة سريعة للموردين والتصنيفات من صفحة المنتج
Route::post('/admin/products/quick-supplier', [AdminProductController::class, 'storeQuickSupplier'])->name('admin.products.quick-store-supplier');
Route::post('/admin/products/quick-category', [AdminProductController::class, 'storeQuickCategory'])->name('admin.products.quick-store-category');

// 3. الموردين (Suppliers - Admin Side)
Route::prefix('admin')->name('admin.')->middleware('admin.permission:suppliers')->group(function () {
    Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::post('suppliers/{supplier}/update', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::get('suppliers/{supplier}/toggle', [SupplierController::class, 'toggleStatus'])->name('suppliers.toggle');
    Route::delete('suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
});

// 4. الطلبات
Route::prefix('admin')->name('admin.')->middleware('admin.permission:orders')->group(function () {
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('orders/{id}/payment-status', [AdminOrderController::class, 'updatePaymentStatus'])->name('orders.updatePaymentStatus');
    Route::post('orders/{id}/commission', [AdminOrderController::class, 'updateCommission'])->name('orders.updateCommission');
    
    // ✅ راوت الطباعة الجديد
    Route::get('orders/{order}/print', [AdminOrderController::class, 'print'])->name('orders.print');
});

// 5. البانرات
Route::prefix('admin/sliders')->name('admin.sliders.')->middleware('admin.permission:sliders')->group(function () {
    Route::get('/', [SliderController::class, 'index'])->name('index');
    Route::get('/create', [SliderController::class, 'create'])->name('create');
    Route::post('/', [SliderController::class, 'store'])->name('store');
    Route::get('/{slider}', [SliderController::class, 'show'])->name('show');
    Route::get('/{slider}/edit', [SliderController::class, 'edit'])->name('edit');
    Route::put('/{slider}', [SliderController::class, 'update'])->name('update');
    Route::delete('/{slider}', [SliderController::class, 'destroy'])->name('destroy');
    Route::patch('/{slider}/toggle', [SliderController::class, 'toggleStatus'])->name('toggleStatus');
    Route::post('/reorder', [SliderController::class, 'reorder'])->name('reorder');
});

// 6. الإحصائيات
Route::prefix('admin/statistics')->name('admin.statistics.')->group(function () {
    Route::get('/', [StatisticController::class, 'index'])->name('index');
    Route::get('/create', [StatisticController::class, 'create'])->name('create');
    Route::post('/', [StatisticController::class, 'store'])->name('store');
    Route::get('/{statistic}/edit', [StatisticController::class, 'edit'])->name('edit');
    Route::put('/{statistic}', [StatisticController::class, 'update'])->name('update');
    Route::delete('/{statistic}', [StatisticController::class, 'destroy'])->name('destroy');
    Route::patch('/{statistic}/order', [StatisticController::class, 'updateOrder'])->name('updateOrder');
});

// 7. المميزات
Route::prefix('admin/features')->name('admin.features.')->group(function () {
    Route::get('/', [FeatureController::class, 'index'])->name('index');
    Route::get('/create', [FeatureController::class, 'create'])->name('create');
    Route::post('/', [FeatureController::class, 'store'])->name('store');
    Route::get('/{feature}/edit', [FeatureController::class, 'edit'])->name('edit');
    Route::put('/{feature}', [FeatureController::class, 'update'])->name('update');
    Route::delete('/{feature}', [FeatureController::class, 'destroy'])->name('destroy');
    Route::patch('/{feature}/order', [FeatureController::class, 'updateOrder'])->name('updateOrder');
});

// 8. المحتوى العام
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('about', [AboutSectionController::class, 'index'])->name('about.index');
    Route::post('about', [AboutSectionController::class, 'update'])->name('about.update');

    Route::prefix('testimonials')->name('testimonials.')->group(function () {
        Route::get('/', [TestimonialController::class, 'index'])->name('index');
        Route::get('/create', [TestimonialController::class, 'create'])->name('create');
        Route::post('/store', [TestimonialController::class, 'store'])->name('store');
        Route::get('/{testimonial}/edit', [TestimonialController::class, 'edit'])->name('edit');
        Route::post('/{testimonial}/update', [TestimonialController::class, 'update'])->name('update');
        Route::delete('/{testimonial}', [TestimonialController::class, 'destroy'])->name('destroy');
        Route::post('/reorder', [TestimonialController::class, 'reorder'])->name('reorder');
        Route::patch('/{testimonial}/toggle', [TestimonialController::class, 'toggleStatus'])->name('toggle');
    });

    Route::get('cta', [CtaSectionController::class, 'index'])->name('cta.index');
    Route::post('cta', [CtaSectionController::class, 'update'])->name('cta.update');

    Route::get('contact', [ContactInfoController::class, 'index'])->name('contact.index');
    Route::post('contact', [ContactInfoController::class, 'update'])->name('contact.update');

    Route::get('story', [OurStoryController::class, 'index'])->name('story.index');
    Route::post('story', [OurStoryController::class, 'update'])->name('story.update');

    Route::get('mvv', [MissionVisionValuesController::class, 'index'])->name('mvv.index');
    Route::post('mvv', [MissionVisionValuesController::class, 'update'])->name('mvv.update');

    Route::get('team', [TeamMemberController::class, 'index'])->name('team.index');
    Route::get('team/create', [TeamMemberController::class, 'create'])->name('team.create');
    Route::post('team', [TeamMemberController::class, 'store'])->name('team.store');
    Route::get('team/{member}/edit', [TeamMemberController::class, 'edit'])->name('team.edit');
    Route::put('team/{member}', [TeamMemberController::class, 'update'])->name('team.update');
    Route::delete('team/{member}', [TeamMemberController::class, 'destroy'])->name('team.destroy');

    Route::get('journey', [JourneyController::class, 'index'])->name('journey.index');
    Route::get('journey/create', [JourneyController::class, 'create'])->name('journey.create');
    Route::post('journey', [JourneyController::class, 'store'])->name('journey.store');
    Route::get('journey/{journey}/edit', [JourneyController::class, 'edit'])->name('journey.edit');
    Route::post('journey/{journey}', [JourneyController::class, 'update'])->name('journey.update');
    Route::delete('journey/{journey}', [JourneyController::class, 'destroy'])->name('journey.destroy');

    Route::get('partners', [PartnerController::class, 'index'])->name('partners.index');
    Route::get('partners/create', [PartnerController::class, 'create'])->name('partners.create');
    Route::post('partners', [PartnerController::class, 'store'])->name('partners.store');
    Route::get('partners/{partner}/edit', [PartnerController::class, 'edit'])->name('partners.edit');
    Route::post('partners/{partner}', [PartnerController::class, 'update'])->name('partners.update');
    Route::delete('partners/{partner}', [PartnerController::class, 'destroy'])->name('partners.destroy');

    Route::get('certificates', [CertificateAwardController::class, 'index'])->name('certificates.index');
    Route::get('certificates/create', [CertificateAwardController::class, 'create'])->name('certificates.create');
    Route::post('certificates', [CertificateAwardController::class, 'store'])->name('certificates.store');
    Route::get('certificates/{certificate}/edit', [CertificateAwardController::class, 'edit'])->name('certificates.edit');
    Route::post('certificates/{certificate}', [CertificateAwardController::class, 'update'])->name('certificates.update');
    Route::delete('certificates/{certificate}', [CertificateAwardController::class, 'destroy'])->name('certificates.destroy');

    Route::get('contact2', [ContactInfo2Controller::class, 'index'])->name('contact2.index');
    Route::post('contact2', [ContactInfo2Controller::class, 'update'])->name('contact2.update');

    Route::get('faqs', [FaqController::class, 'index'])->name('faqs.index');
    Route::get('faqs/create', [FaqController::class, 'create'])->name('faqs.create');
    Route::post('faqs', [FaqController::class, 'store'])->name('faqs.store');
    Route::get('faqs/{faq}/edit', [FaqController::class, 'edit'])->name('faqs.edit');
    Route::post('faqs/{faq}', [FaqController::class, 'update'])->name('faqs.update');
    Route::delete('faqs/{faq}', [FaqController::class, 'destroy'])->name('faqs.destroy');

    Route::get('map', [MapLocationController::class, 'index'])->name('map.index');
    Route::post('map', [MapLocationController::class, 'update'])->name('map.update');
});

// ========================
// مسارات الموردين (Suppliers)
// ========================
Route::get('/supplier/auth', [SupplierAuthController::class, 'index'])->name('supplier.auth');
Route::get('/supplier/login', [SupplierAuthController::class, 'index'])->name('supplier.login');
Route::post('/supplier/auth', [SupplierAuthController::class, 'store'])->name('supplier.auth.submit');
Route::post('/supplier/login', [SupplierAuthController::class, 'store']);
Route::post('/supplier/register', [SupplierAuthController::class, 'create'])->name('supplier.register');
Route::get('/supplier/logout', [SupplierAuthController::class, 'destroy'])->name('supplier.logout');
Route::post('/supplier/logout', [SupplierAuthController::class, 'destroy']);

Route::get('/supplier/dashboard', function () {
    if (!session()->has('supplier')) {
        return redirect('/supplier/auth')->with('error', 'يرجى تسجيل الدخول أولاً');
    }
    $supplierId = session('supplier');
    $supplier = Supplier::findOrFail($supplierId);
    return view('supplier.dashboard', compact('supplier'));
})->name('supplier.dashboard');

Route::prefix('supplier')->name('supplier.')->group(function () {
    Route::get('products', [SupplierProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [SupplierProductController::class, 'create'])->name('products.create');
    Route::post('products/store', [SupplierProductController::class, 'store'])->name('products.store');
    Route::get('products/{id}/edit', [SupplierProductController::class, 'edit'])->name('products.edit');
    Route::post('products/{id}/update', [SupplierProductController::class, 'update'])->name('products.update');
    Route::get('products/{id}/delete', [SupplierProductController::class, 'destroy'])->name('products.delete');
    
    Route::get('orders', [SupplierOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}', [SupplierOrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{id}/status', [SupplierOrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // مسارات استعادة كلمة المرور
    Route::get('password/reset', [SupplierForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [SupplierForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [SupplierForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [SupplierForgotPasswordController::class, 'reset'])->name('password.update');
});

Route::get('/supplier/profile', function () {
    if (!session()->has('supplier')) {
        return redirect('/supplier/auth')->with('error', 'يرجى تسجيل الدخول أولاً');
    }
    $supplierId = session('supplier');
    $supplier = Supplier::findOrFail($supplierId);
    return view('supplier.profile', compact('supplier'));
})->name('supplier.profile');

// ========================
// SEO & Utilities
// ========================
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
    $robots = "User-agent: *\n";
    $robots .= "Allow: /\n";
    $robots .= "Sitemap: http://localhost:8000/sitemap.xml\n";
    return response($robots, 200)->header('Content-Type', 'text/plain');
});



// ========================
// Fallback
// ========================
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
