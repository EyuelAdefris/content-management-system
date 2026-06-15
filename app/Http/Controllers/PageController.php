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
    public function index(\Illuminate\Http\Request $request)
    {
        $query = Page::with('author')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pages = $query->paginate(10)->withQueryString();

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

        $page = Page::create([
            'title'      => $request->title,
            'slug'       => $slug,
            'content'    => $request->content,
            'status'     => $request->status,
            'created_by' => auth()->id(),
        ]);

        try {
            $users = \App\Models\User::all();
            \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\ContentChangeNotification('Page', 'created', $page->title, auth()->user()->name ?? 'Unknown'));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send ContentChangeNotification: ' . $e->getMessage());
        }

        return redirect()->route('pages.index')
            ->with('success', 'Page created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        if (!auth()->user()->hasRole('admin') && auth()->id() !== $page->created_by) {
            abort(403, 'Unauthorized action.');
        }

        return view('pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PageRequest $request, Page $page)
    {
        if (!auth()->user()->hasRole('admin') && auth()->id() !== $page->created_by) {
            abort(403, 'Unauthorized action.');
        }

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

        try {
            $users = \App\Models\User::all();
            \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\ContentChangeNotification('Page', 'updated', $page->title, auth()->user()->name ?? 'Unknown'));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send ContentChangeNotification: ' . $e->getMessage());
        }

        return redirect()->route('pages.index')
            ->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        if (!auth()->user()->hasRole('admin') && auth()->id() !== $page->created_by) {
            abort(403, 'Unauthorized action.');
        }

        $page->delete();

        return redirect()->back()
            ->with('success', 'Page deleted successfully.');
    }
}
