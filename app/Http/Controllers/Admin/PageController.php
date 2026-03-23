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
        $page = Page::where('slug', $slug)->firstOrFail();
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        $data = $request->validate([
            'content' => 'nullable|string',
            'policy_date' => 'nullable|date',
            'image' => 'nullable|image|max:2048',
            'features' => 'nullable|array',
            'features.*.icon' => 'nullable|string',
            'features.*.title' => 'nullable|string',
            'features.*.description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($page->image && Storage::disk('public')->exists($page->image)) {
                Storage::disk('public')->delete($page->image);
            }
            $path = $request->file('image')->store('pages', 'public');
            $page->image = $path;
        }

        $page->content = $request->input('content');
        $page->policy_date = $request->input('policy_date');
        
        if ($request->has('features')) {
            $page->extra_content = ['features' => $request->input('features')];
        }

        $page->save();

        return back()->with('success', 'Page content updated successfully.');
    }
}
