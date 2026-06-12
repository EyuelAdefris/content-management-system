<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Http\Requests\PageRequest;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::with('author')->latest()->get();
        return view('pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PageRequest $request)
    {
        $slug = Str::slug($request->title);
        $original = $slug;
        $counter = 1;
        while (Page::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $counter;
            $counter++;
        }

        Page::create([
            'title'      => $request->title,
            'slug'       => $slug,
            'content'    => $request->content,
            'status'     => $request->status,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('pages.index')
            ->with('success', 'Page created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        return view('pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PageRequest $request, Page $page)
    {
        $slug = $page->slug;

        if ($request->title !== $page->title) {
            $slug = Str::slug($request->title);
            $original = $slug;
            $counter = 1;
            while (Page::where('slug', $slug)->where('id', '!=', $page->id)->exists()) {
                $slug = $original . '-' . $counter;
                $counter++;
            }
        }

        $page->update([
            'title'   => $request->title,
            'slug'    => $slug,
            'content' => $request->content,
            'status'  => $request->status,
        ]);

        return redirect()->route('pages.index')
            ->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->back()
            ->with('success', 'Page deleted successfully.');
    }
}
