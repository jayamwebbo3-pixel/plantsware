<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    /**
     * Show the form for editing the specified resource by slug.
     */
    public function edit($slug)
    {
        $page = Page::where('slug', $slug)->first();
        
        if (!$page && $slug === 'ad-banner') {
            $page = Page::create([
                'title' => 'Ad Banner',
                'slug' => 'ad-banner',
                'content' => 'Discover our organic, sustainable products for a healthier lifestyle',
                'extra_content' => [
                    'button_text' => 'Shop Now',
                    'button_link' => 'categories'
                ]
            ]);
        }

        if (!$page) {
            abort(404);
        }

        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        $data = $request->validate([
            'title' => 'nullable|string',
            'content' => 'nullable|string',
            'policy_date' => 'nullable|date',
            'image' => 'nullable|image|max:2048',
            'features' => 'nullable|array',
            'features.*.icon' => 'nullable|string',
            'features.*.title' => 'nullable|string',
            'features.*.description' => 'nullable|string',
            'extra_content' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($page->image && Storage::disk('public')->exists($page->image)) {
                Storage::disk('public')->delete($page->image);
            }
            $path = $request->file('image')->store('pages', 'public');
            $page->image = $path;
        }

        if ($request->has('title')) {
            $page->title = $request->input('title');
        }
        
        $page->content = $request->input('content');
        $page->policy_date = $request->input('policy_date');
        
        if ($request->has('features')) {
            $page->extra_content = ['features' => $request->input('features')];
        } elseif ($request->has('extra_content')) {
            $page->extra_content = $request->input('extra_content');
        }

        $page->save();

        return back()->with('success', 'Page content updated successfully.');
    }
}
