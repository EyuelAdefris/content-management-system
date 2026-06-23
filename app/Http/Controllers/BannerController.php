<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Http\Requests\BannerRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Banner::latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $banners = $query->paginate(10)->withQueryString();

        return view('banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('banners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BannerRequest $request)
    {
        $storedPath = $request->file('image')->store('banners', 'cloudinary');
        $imageUrl   = Storage::disk('cloudinary')->url($storedPath);

        $banner = Banner::create([
            'title'     => $request->title,
            'image'     => $imageUrl,
            'link_url'  => $request->link_url,
            'position'  => $request->position,
            'is_active' => $request->boolean('is_active'),
            'created_by' => auth()->id(),
        ]);

        try {
            $users = \App\Models\User::all();
            \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\ContentChangeNotification('Banner', 'created', $banner->title, auth()->user()->name ?? 'Unknown'));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send ContentChangeNotification: ' . $e->getMessage());
        }

        return redirect()->route('banners.index')->with('success', 'Banner created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        if (!auth()->user()->hasRole('admin') && auth()->id() !== $banner->created_by) {
            abort(403, 'Unauthorized action.');
        }

        return view('banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BannerRequest $request, Banner $banner)
    {
        if (!auth()->user()->hasRole('admin') && auth()->id() !== $banner->created_by) {
            abort(403, 'Unauthorized action.');
        }

        $data = [
            'title'     => $request->title,
            'link_url'  => $request->link_url,
            'position'  => $request->position,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('image')) {
            $this->deleteFromCloudinary($banner->image);
            $storedPath    = $request->file('image')->store('banners', 'cloudinary');
            $data['image'] = Storage::disk('cloudinary')->url($storedPath);
        }

        $banner->update($data);

        try {
            $users = \App\Models\User::all();
            \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\ContentChangeNotification('Banner', 'updated', $banner->title, auth()->user()->name ?? 'Unknown'));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send ContentChangeNotification: ' . $e->getMessage());
        }

        return redirect()->route('banners.index')->with('success', 'Banner updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        if (!auth()->user()->hasRole('admin') && auth()->id() !== $banner->created_by) {
            abort(403, 'Unauthorized action.');
        }

        $this->deleteFromCloudinary($banner->image);

        $banner->delete();

        return redirect()->back()->with('success', 'Banner deleted successfully.');
    }

    /**
     * Helper: delete a Cloudinary asset given its stored CDN URL or path.
     */
    private function deleteFromCloudinary(?string $fileUrl): void
    {
        if (!$fileUrl) return;

        $path = $fileUrl;
        if (str_starts_with($fileUrl, 'http')) {
            if (preg_match('#/upload/(?:v\d+/)?(.+)$#', $fileUrl, $matches)) {
                $path = $matches[1];
            }
        }
        try {
            Storage::disk('cloudinary')->delete($path);
        } catch (\Throwable $e) {
            Log::warning('Cloudinary delete failed: ' . $e->getMessage());
        }
    }
}
