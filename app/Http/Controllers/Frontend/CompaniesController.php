<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    // عرض قائمة الشركات (صفحة /companies)
    public function index(Request $request)
    {
        $query = Supplier::where('status', 1) // ✅ تغيير من active إلى status
                        ->withCount(['products' => function($q) {
                            $q->where('status', 'مقبول');
                        }])
                        ->orderBy('name', 'asc');

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $suppliers = $query->paginate(12);

        return view('frontend.companies.index', compact('suppliers'));
    }

    // عرض منتجات شركة معينة (صفحة /companies/{id})
    public function show($id, Request $request)
    {
        // جلب بيانات المورد - ✅ تغيير من active إلى status
        $supplier = Supplier::where('status', 1) // ✅ تغيير من active إلى status
                          ->with(['products' => function($q) {
                              $q->where('status', 'مقبول');
                          }])
                          ->findOrFail($id);

        // جلب منتجات الشركة مع الفلاتر
        $productsQuery = Product::where('supplier_id', $id)
                               ->where('products.status', 'مقبول')
                               ->with(['category', 'supplier']);

        // فلتر البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $productsQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // فلتر نوع المنتج - إصلاح المشكلة
        if ($request->filled('type') && is_string($request->type)) {
            $productsQuery->where('product_type', $request->type);
        }

        // فلتر التصنيف (دعم multiple)
        if ($request->filled('category')) {
            $categories = is_array($request->category) ? $request->category : [$request->category];
            $productsQuery->whereIn('category_id', $categories);
        }

        // فلتر السعر
        if ($request->filled('price_min') && is_numeric($request->price_min)) {
            $productsQuery->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max') && is_numeric($request->price_max)) {
            $productsQuery->where('price', '<=', $request->price_max);
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
            default:
                $productsQuery->orderBy('created_at', 'desc');
                break;
        }

        $products = $productsQuery->paginate(12);

        // إحصائيات الشركة
        $totalProducts = Product::where('supplier_id', $id)
                               ->where('status', 'مقبول')
                               ->count();

        $productTypes = Product::where('supplier_id', $id)
                              ->where('status', 'مقبول')
                              ->select('product_type')
                              ->selectRaw('count(*) as count')
                              ->groupBy('product_type')
                              ->get();

        // التصنيفات المتاحة للشركة
        $availableCategories = Category::whereHas('products', function($q) use ($id) {
            $q->where('supplier_id', $id)
              ->where('status', 'مقبول');
        })
        ->withCount(['products' => function($q) use ($id) {
            $q->where('supplier_id', $id)
              ->where('status', 'مقبول');
        }])
        ->get();

        return view('frontend.companies.show', compact(
            'supplier', 
            'products', 
            'totalProducts',
            'productTypes',
            'availableCategories'
        ));
    }
}
