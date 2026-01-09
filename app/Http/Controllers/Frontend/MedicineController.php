<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        // جلب التصنيفات
        $categories = Category::ofType('أدوية')
                            ->withCount('products')
                            ->orderBy('name', 'asc')
                            ->get();

        // جلب الشركات
        $companies = Product::medicines()
                           ->active()
                           ->whereNotNull('company_name')
                           ->pluck('company_name')
                           ->unique()
                           ->filter()
                           ->sort()
                           ->values()
                           ->toArray();

        // بناء استعلام المنتجات
        $productsQuery = Product::medicines()
                               ->active()
                               ->with(['category', 'supplier']);

        // فلتر البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $productsQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('active_ingredient', 'like', "%{$search}%")
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

        // فلتر التوفر
        if ($request->filled('available')) {
            $productsQuery->where('total_units', '>', 0);
        }

        // الترتيب
        $sortBy = $request->get('sort', 'default');
        switch ($sortBy) {
            case 'price-low':
                $productsQuery->orderByRaw('CASE WHEN discount > 0 THEN price - (price * discount / 100) ELSE price END ASC');
                break;
            case 'price-high':
                $productsQuery->orderByRaw('CASE WHEN discount > 0 THEN price - (price * discount / 100) ELSE price END DESC');
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
        $totalProducts = Product::medicines()->active()->count();
        
        $featuredProducts = Product::medicines()
                                 ->active()
                                 ->where('featured', true)
                                 ->take(8)
                                 ->get();

        return view('frontend.medicines.index', compact(
            'categories', 
            'companies', 
            'products', 
            'totalProducts', 
            'featuredProducts'
        ));
    }

    public function show($slug)
    {
        $product = Product::medicines()
                         ->active()
                         ->where('slug', $slug)
                         ->with(['category', 'supplier'])
                         ->firstOrFail();

        // زيادة المشاهدات
        $product->increment('views');

        // منتجات مشابهة
        $relatedProducts = Product::medicines()
                                ->active()
                                ->where('category_id', $product->category_id)
                                ->where('id', '!=', $product->id)
                                ->take(6)
                                ->get();

        // Pass variables for unified template
        $productType = 'medicines';
        $categoryName = 'الأدوية';
        $categoryRoute = route('frontend.medicines.index');

        return view('frontend.products.unified-show', compact('product', 'productType', 'categoryName', 'categoryRoute'));
    }

    public function quickOrder()
    {
        $products = Product::medicines()
                         ->active()
                         ->with(['supplier'])
                         ->orderBy('name', 'asc')
                         ->get();

        return view('frontend.medicines.quick-order', compact('products'));
    }
}
