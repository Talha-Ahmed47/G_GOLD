<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TradingService;

class OrderController extends Controller
{
    private TradingService $tradingService;

    public function __construct(TradingService $tradingService)
    {
        $this->tradingService = $tradingService;
    }

    public function buy(Request $request)
    {
        $request->validate(['quantity' => 'required|numeric|min:0.01']);
        
        $order = $this->tradingService->buyGold($request->user()->id, $request->quantity);

        return response()->json([
            'message' => 'Gold bought successfully',
            'order' => $order
        ]);
    }

    public function sell(Request $request)
    {
        $request->validate(['quantity' => 'required|numeric|min:0.01']);
        
        $order = $this->tradingService->sellGold($request->user()->id, $request->quantity);

        return response()->json([
            'message' => 'Gold sold successfully',
            'order' => $order
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
