<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a single product details page
     */
    public function show($slug)
    {
        // Find the product by slug, regardless of type
        $product = Product::where('slug', $slug)
                         ->where('status', 'مقبول') // Only show approved products
                         ->with(['category', 'supplier'])
                         ->firstOrFail();

        // Increment view count
        $product->increment('views');

        // Get related products based on category and type
        $relatedProducts = Product::where('category_id', $product->category_id)
                                 ->where('product_type', $product->product_type)
                                 ->where('status', 'مقبول')
                                 ->where('id', '!=', $product->id)
                                 ->with(['category', 'supplier'])
                                 ->take(8)
                                 ->get();

        // Get more products from the same supplier
        $supplierProducts = Product::where('supplier_id', $product->supplier_id)
                                  ->where('status', 'مقبول')
                                  ->where('id', '!=', $product->id)
                                  ->with(['category', 'supplier'])
                                  ->take(4)
                                  ->get();

        // Get featured products of the same type
        $featuredProducts = Product::where('product_type', $product->product_type)
                                  ->where('status', 'مقبول')
                                  ->where('featured', true)
                                  ->where('id', '!=', $product->id)
                                  ->with(['category', 'supplier'])
                                  ->take(6)
                                  ->get();

        return view('frontend.products.show', compact(
            'product', 
            'relatedProducts', 
            'supplierProducts', 
            'featuredProducts'
        ));
    }

    /**
     * Quick view for product (AJAX)
     */
    public function quickView($id)
    {
        $product = Product::where('id', $id)
                         ->where('status', 'مقبول')
                         ->with(['category', 'supplier'])
                         ->firstOrFail();

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->final_price,
                'formatted_price' => $product->formatted_price,
                'has_discount' => $product->has_discount,
                'discount_percent' => $product->discount_percent,
                'formatted_discounted_price' => $product->formatted_discounted_price,
                'image_1' => $product->image_1,
                'short_description' => $product->short_description,
                'is_available' => $product->is_available,
                'available_quantity' => $product->available_quantity,
                'unit_type' => $product->unit_type,
                'company_name' => $product->company_name,
                'category' => $product->category ? $product->category->name : null,
                'supplier' => $product->supplier ? $product->supplier->name : null,
            ]
        ]);
    }

    /**
     * Compare products
     */
    public function compare(Request $request)
    {
        $productIds = $request->input('products', []);
        
        if (count($productIds) < 2 || count($productIds) > 4) {
            return redirect()->back()->with('error', 'يرجى اختيار من 2 إلى 4 منتجات للمقارنة');
        }

        $products = Product::whereIn('id', $productIds)
                          ->where('status', 'مقبول')
                          ->with(['category', 'supplier'])
                          ->get();

        return view('frontend.products.compare', compact('products'));
    }

    /**
     * Get product reviews (AJAX)
     */
    public function getReviews($id, Request $request)
    {
        $product = Product::findOrFail($id);
        
        // For now, return empty reviews - this can be implemented later
        return response()->json([
            'success' => true,
            'reviews' => [],
            'average_rating' => 0,
            'total_reviews' => 0
        ]);
    }

    /**
     * Check product availability for specific quantity
     */
    public function checkAvailability($id, Request $request)
    {
        $product = Product::findOrFail($id);
        $quantity = intval($request->input('quantity', 1));

        $available = true;
        $message = '';

        if (!$product->is_available) {
            $available = false;
            $message = 'هذا المنتج غير متوفر حالياً';
        } elseif (is_numeric($product->available_quantity) && $quantity > $product->available_quantity) {
            $available = false;
            $message = "الكمية المطلوبة ({$quantity}) غير متوفرة! المتوفر: {$product->available_quantity}";
        } elseif ($quantity < ($product->min_order_quantity ?? 1)) {
            $available = false;
            $message = "الحد الأدنى للطلب هو {$product->min_order_quantity} وحدة";
        }

        return response()->json([
            'success' => true,
            'available' => $available,
            'message' => $message,
            'max_quantity' => is_numeric($product->available_quantity) ? $product->available_quantity : null,
            'min_quantity' => $product->min_order_quantity ?? 1
        ]);
    }
}
