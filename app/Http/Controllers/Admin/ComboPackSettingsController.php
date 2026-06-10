<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComboPackSetting;
use App\Models\ComboPackDiscountSlab;
use Illuminate\Http\Request;

class ComboPackSettingsController extends Controller
{
    public function settings()
    {
        $settings = ComboPackSetting::first();
        if (!$settings) {
            $settings = ComboPackSetting::create([
                'is_enabled' => false,
                'max_products' => 3
            ]);
        }
        $slabs = ComboPackDiscountSlab::orderBy('min_amount', 'asc')->get();

        return view('admin.custom-combo.settings', compact('settings', 'slabs'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'is_enabled' => 'required|boolean',
            'max_products' => 'required|integer|min:2|max:20',
        ]);

        $settings = ComboPackSetting::first();
        if (!$settings) {
            $settings = new ComboPackSetting();
        }

        $settings->is_enabled = $request->input('is_enabled');
        $settings->max_products = $request->input('max_products');
        $settings->save();

        return redirect()->route('admin.custom-combo.settings')->with('success', 'Combo Pack settings updated successfully.');
    }

    public function storeSlab(Request $request)
    {
        $request->validate([
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|gt:min_amount',
            'discount_percentage' => 'required|numeric|min:0|max:100',
        ]);

        ComboPackDiscountSlab::create([
            'min_amount' => $request->input('min_amount'),
            'max_amount' => $request->input('max_amount'),
            'discount_percentage' => $request->input('discount_percentage'),
            'status' => $request->input('status', true),
        ]);

        return redirect()->route('admin.custom-combo.settings', ['tab' => 'slabs'])->with('success', 'Discount slab created successfully.');
    }

    public function updateSlab(Request $request, $id)
    {
        $request->validate([
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|gt:min_amount',
            'discount_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $slab = ComboPackDiscountSlab::findOrFail($id);
        $slab->update([
            'min_amount' => $request->input('min_amount'),
            'max_amount' => $request->input('max_amount'),
            'discount_percentage' => $request->input('discount_percentage'),
            'status' => $request->input('status', true),
        ]);

        return redirect()->route('admin.custom-combo.settings', ['tab' => 'slabs'])->with('success', 'Discount slab updated successfully.');
    }

    public function deleteSlab($id)
    {
        $slab = ComboPackDiscountSlab::findOrFail($id);
        $slab->delete();

        return redirect()->route('admin.custom-combo.settings', ['tab' => 'slabs'])->with('success', 'Discount slab deleted successfully.');
    }
}
