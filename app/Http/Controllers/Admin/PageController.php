<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

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
        ]);

        $page->update($data);

        return back()->with('success', 'Page content updated successfully.');
    }
}
