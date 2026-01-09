<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CosmeticsController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('product_type', 'منتجات تجميل')
                            ->withCount('products')
                            ->orderBy('name', 'asc')
                            ->get();

        $companies = Product::where('product_type', 'منتجات تجميل')
                           ->where('status', 'مقبول')
                           ->whereNotNull('company_name')
                           ->pluck('company_name')
                           ->unique()
                           ->filter()
                           ->sort()
                           ->values()
                           ->toArray();

        $productsQuery = Product::where('product_type', 'منتجات تجميل')
                               ->where('status', 'مقبول')
                               ->with(['category', 'supplier']);

        if ($request->filled('search')) {
            $search = $request->search;
            $productsQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $productsQuery->whereIn('category_id', (array)$request->category);
        }

        if ($request->filled('company')) {
            $productsQuery->whereIn('company_name', (array)$request->company);
        }

        if ($request->filled('price_min')) {
            $productsQuery->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $productsQuery->where('price', '<=', $request->price_max);
        }

        if ($request->filled('available')) {
            $productsQuery->where('total_units', '>', 0);
        }

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
                $productsQuery->orderBy('created_at', 'desc');
                break;
        }

        $products = $productsQuery->paginate(12);

        // مستحضرات التجميل بدون خصومات - السعر العادي فقط
        $products->getCollection()->transform(function ($product) {
            $product->final_price = $product->price;
            return $product;
        });

        $totalProducts = Product::where('product_type', 'منتجات تجميل')
                               ->where('status', 'مقبول')
                               ->count();

        $featuredProducts = Product::where('product_type', 'منتجات تجميل')
                                  ->where('status', 'مقبول')
                                  ->where('featured', true)
                                  ->take(8)
                                  ->get();

        return view('frontend.cosmetics.index', compact(
            'categories', 
            'companies', 
            'products', 
            'totalProducts',
            'featuredProducts'
        ));
    }

    public function show($slug)
    {
        $product = Product::where('product_type', 'منتجات تجميل')
                         ->where('status', 'مقبول')
                         ->where('slug', $slug)
                         ->with(['category', 'supplier'])
                         ->firstOrFail();

        $product->increment('views');

        // بدون خصم
        $product->final_price = $product->price;

        $relatedProducts = Product::where('product_type', 'منتجات تجميل')
                                 ->where('status', 'مقبول')
                                 ->where('category_id', $product->category_id)
                                 ->where('id', '!=', $product->id)
                                 ->take(6)
                                 ->get();

        // Pass variables for unified template
        $productType = 'cosmetics';
        $categoryName = 'مستحضرات التجميل';
        $categoryRoute = route('frontend.cosmetics.index');

        return view('frontend.products.unified-show', compact('product', 'productType', 'categoryName', 'categoryRoute'));
    }

    public function quickOrder()
    {
        $products = Product::where('product_type', 'منتجات تجميل')
                         ->where('status', 'مقبول')
                         ->with(['supplier'])
                         ->orderBy('name', 'asc')
                         ->get();

        return view('frontend.cosmetics.quick-order', compact('products'));
    }
}
