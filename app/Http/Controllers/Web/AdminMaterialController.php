<?php

namespace App\Http\Controllers\Web;

use App\Models\AdminMaterial;
use App\Http\Controllers\Controller;
use App\Services\GoldPriceService;
use Illuminate\Http\Request;

class AdminMaterialController extends Controller
{
    public function index()
    {
        $materials = AdminMaterial::all();
        return view('admin.materials', compact('materials'));
    }

    public function store(Request $request, GoldPriceService $goldPriceService)
    {
        $request->validate([
            'metal' => 'required|string|in:gold,silver,platinum,palladium',
            'amount' => 'required|numeric|min:0.0001',
        ]);

        $buyPrice = $goldPriceService->getCurrentPrice($request->metal);

        AdminMaterial::create([
            'metal' => $request->metal,
            'amount' => $request->amount,
            'buy_price' => $buyPrice,
        ]);

        return redirect()->back()->with('success', 'Material added successfully.');
    }

    public function update(Request $request, $id)
    {
        $material = AdminMaterial::findOrFail($id);
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);
        $material->update(['amount' => $request->amount]);
        return redirect()->back()->with('success', 'Material updated.');
    }

    public function destroy($id)
    {
        AdminMaterial::destroy($id);
        return redirect()->back()->with('success', 'Material removed.');
    }
}
