<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\GoldPriceService;
use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Display the landing homepage.
     */
    public function index(GoldPriceService $goldPriceService)
    {
        $products = Product::all();
        $metalPrices = $goldPriceService->getAllPrices();
        
        return view('website.index', compact('products', 'metalPrices'));
    }

    /**
     * Get the live precious metal prices and product calculations.
     */
    public function getGoldPrice(GoldPriceService $goldPriceService)
    {
        $prices = $goldPriceService->getAllPrices();
        $products = Product::all();
        $productPrices = [];

        foreach ($products as $product) {
            $base = $prices[$product->metal_type] ?? 0;
            $cost = $base * $product->weight_oz;
            $productPrices[$product->id] = $cost * (1 + ($product->premium_percentage / 100));
        }

        return response()->json([
            'prices' => $prices,
            'product_prices' => $productPrices,
        ]);
    }

    /**
     * Store contact us submissions.
     */
    public function submitContact(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'interest' => 'required|string|in:sell,buying,storage,ira',
            'message' => 'required|string|min:5',
        ]);

        \App\Models\Contact::create($validated);

        return back()->with('success', 'Thank you! Your message has been sent successfully. Our precious metals consultant will contact you shortly.');
    }
}
