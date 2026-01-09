<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class MedicalSuppliesController extends Controller
{
    public function index(Request $request)
    {
        // جلب التصنيفات
        $categories = Category::where('product_type', 'مستلزمات طبية')
                            ->withCount('products')
                            ->orderBy('name', 'asc')
                            ->get();

        // جلب الشركات
        $companies = Product::medicalSupplies()
                          ->active()
                          ->whereNotNull('company_name')
                          ->pluck('company_name')
                          ->unique()
                          ->filter()
                          ->sort()
                          ->values()
                          ->toArray();

        // بناء استعلام المنتجات
        $productsQuery = Product::medicalSupplies()
                              ->active()
                              ->with(['category', 'supplier']);

        // فلتر البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $productsQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // فلتر التصنيف
        if ($request->filled('category')) {
            $productsQuery->whereIn('category_id', (array)$request->category);
        }

        // فلتر الشركة
        if ($request->filled('company')) {
            $productsQuery->whereIn('company_name', (array)$request->company);
        }

        // فلتر السعر
        if ($request->filled('price_min')) {
            $productsQuery->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $productsQuery->where('price', '<=', $request->price_max);
        }

        // ✅ فلتر التوفر المُحسّن للمستلزمات
        if ($request->filled('available')) {
            $productsQuery->available();
        }

        // الترتيب
        $sortBy = $request->get('sort', 'default');
        switch ($sortBy) {
            case 'price-low':
                $productsQuery->orderBy('price', 'asc');
                break;
            case 'price-high':
                $productsQuery->orderBy('price', 'desc');
                break;
            case 'name-asc':
                $productsQuery->orderBy('name', 'asc');
                break;
            case 'name-desc':
                $productsQuery->orderBy('name', 'desc');
                break;
            case 'newest':
                $productsQuery->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $productsQuery->orderBy('views', 'desc');
                break;
            default:
                $productsQuery->orderBy('featured', 'desc')
                             ->orderBy('created_at', 'desc');
                break;
        }

        // الباجينيشن
        $products = $productsQuery->paginate(12);

        // إحصائيات
        $totalProducts = Product::medicalSupplies()
                              ->active()
                              ->count();

        // منتجات مميزة
        $featuredProducts = Product::medicalSupplies()
                                 ->active()
                                 ->where('featured', true)
                                 ->take(8)
                                 ->get();

        return view('frontend.medical_supplies.index', compact(
            'categories', 
            'companies', 
            'products', 
            'totalProducts',
            'featuredProducts'
        ));
    }

    public function show($slug)
    {
        $product = Product::medicalSupplies()
                        ->active()
                        ->where('slug', $slug)
                        ->with(['category', 'supplier'])
                        ->firstOrFail();

        // ✅ التأكد من السعر (لو صفر نحدد قيمة افتراضية)
        if (!$product->price || $product->price == 0) {
            $product->price = 100; // سعر افتراضي للمستلزمات
            $product->saveQuietly(); // حفظ بدون events
        }

        // زيادة المشاهدات
        $product->increment('views');

        // منتجات مشابهة
        $relatedProducts = Product::medicalSupplies()
                                ->active()
                                ->where('category_id', $product->category_id)
                                ->where('id', '!=', $product->id)
                                ->take(6)
                                ->get();

        // ✅ السعر النهائي (بدون خصم للمستلزمات)
        $product->final_price = $product->price;

        // Pass variables for unified template
        $productType = 'medical-supplies';
        $categoryName = 'المستلزمات الطبية';
        $categoryRoute = route('frontend.medical-supplies.index');

        return view('frontend.products.unified-show', compact('product', 'productType', 'categoryName', 'categoryRoute'));
    }

    public function quickOrder()
    {
        $products = Product::medicalSupplies()
                         ->active()
                         ->with(['supplier'])
                         ->orderBy('name', 'asc')
                         ->get();

        return view('frontend.medical_supplies.quick-order', compact('products'));
    }
}
