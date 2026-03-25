<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingRate;
use Illuminate\Http\Request;

class ShippingRateController extends Controller
{
    public function index()
    {
        $shippingRates = ShippingRate::all();
        return view('admin.shipping_rates.index', compact('shippingRates'));
    }

    public function create()
    {
        return view('admin.shipping_rates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'state_name' => 'required|string|unique:shipping_rates,state_name',
            'base_weight' => 'required|integer|min:0',
            'base_cost' => 'required|numeric|min:0',
            'additional_weight_unit' => 'required|integer|min:1',
            'additional_cost_per_unit' => 'required|numeric|min:0',
        ]);

        ShippingRate::create($validated);

        return redirect()->route('admin.shipping-rates.index')->with('success', 'Shipping rate added successfully.');
    }

    public function edit(ShippingRate $shippingRate)
    {
        return view('admin.shipping_rates.edit', compact('shippingRate'));
    }

    public function update(Request $request, ShippingRate $shippingRate)
    {
        $validated = $request->validate([
            'state_name' => 'required|string|unique:shipping_rates,state_name,' . $shippingRate->id,
            'base_weight' => 'required|integer|min:0',
            'base_cost' => 'required|numeric|min:0',
            'additional_weight_unit' => 'required|integer|min:1',
            'additional_cost_per_unit' => 'required|numeric|min:0',
        ]);

        $shippingRate->update($validated);

        return redirect()->route('admin.shipping-rates.index')->with('success', 'Shipping rate updated successfully.');
    }

    public function destroy(ShippingRate $shippingRate)
    {
        $shippingRate->delete();
        return redirect()->route('admin.shipping-rates.index')->with('success', 'Shipping rate deleted successfully.');
    }
}
