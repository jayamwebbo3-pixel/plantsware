<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $search = request('search');
        $perPage = request('per_page', 20);

        $sliders = Slider::when($search, function ($query) use ($search) {
                return $query->where('title', 'like', "%{$search}%")
                             ->orWhere('subtitle', 'like', "%{$search}%");
            })
            ->orderBy(
                request('sort', 'sort_order'),
                request('direction', 'asc')
            )
            ->paginate($perPage)
            ->appends(request()->query());
            
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image', // max size removed temporarily for testing, should be max:2048
            'button_text' => 'nullable|string',
            'button_link' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $slider = new Slider();
        $slider->title = $validated['title'] ?? null;
        $slider->subtitle = $validated['subtitle'] ?? null;
        $slider->description = $validated['description'] ?? null;
        $slider->button_text = $validated['button_text'] ?? null;
        $slider->button_link = $validated['button_link'] ?? null;
        $slider->sort_order = $validated['sort_order'] ?? 0;
        $slider->is_active = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('sliders', 'public');
            $slider->image = $path;
        }

        $slider->save();

        return redirect()->route('admin.sliders.index')->with('success', 'Slider created successfully');
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image', // max 2MB removed for testing but should be there: max:2048
            'button_text' => 'nullable|string',
            'button_link' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $slider->title = $validated['title'] ?? $slider->title;
        $slider->subtitle = $validated['subtitle'] ?? $slider->subtitle;
        $slider->description = $validated['description'] ?? $slider->description;
        $slider->button_text = $validated['button_text'] ?? $slider->button_text;
        $slider->button_link = $validated['button_link'] ?? $slider->button_link;
        $slider->sort_order = $validated['sort_order'] ?? $slider->sort_order;
        $slider->is_active = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($slider->image && Storage::disk('public')->exists($slider->image)) {
                Storage::disk('public')->delete($slider->image);
            }
            
            // Store new image
            $path = $request->file('image')->store('sliders', 'public');
            $slider->image = $path;
        }

        $slider->save();

        return redirect()->route('admin.sliders.index')->with('success', 'Slider updated successfully');
    }

    public function destroy(Slider $slider)
    {
        if ($slider->image) {
            Storage::disk('public')->delete($slider->image);
        }
        $slider->delete();
        return redirect()->route('admin.sliders.index')->with('success', 'Slider deleted successfully');
    }
}
