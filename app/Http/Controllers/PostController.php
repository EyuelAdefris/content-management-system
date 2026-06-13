<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('author')->latest()->get();
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        // --- DIAGNOSTIC (remove after debugging) ---
        $fileInfo = 'none';
        if ($request->hasFile('featured_image')) {
            $f = $request->file('featured_image');
            $fileInfo = $f->getClientOriginalName() . ' | ' . $f->getSize() . ' bytes | ' . $f->getMimeType();
        }
        Log::info('PostController@store hit', [
            'has_file'    => $request->hasFile('featured_image'),
            'all_files'   => array_keys($request->allFiles()),
            'file_info'   => $fileInfo,
            'content_type'=> $request->header('Content-Type'),
        ]);
        // --- END DIAGNOSTIC ---

        // Generate unique slug
        $slug = Str::slug($request->title);
        $original = $slug;
        $counter = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $counter;
            $counter++;
        }

        // Handle featured image upload
        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            try {
                $path = $request->file('featured_image')->storePublicly('posts', 'public');
                if ($path === false) {
                    return back()->withInput()->withErrors([
                        'featured_image' => 'The image could not be saved. Check storage permissions.',
                    ]);
                }
                $imagePath = $path;
            } catch (\Throwable $e) {
                Log::error('Featured image upload failed in store(): ' . $e->getMessage());
                return back()->withInput()->withErrors([
                    'featured_image' => 'The featured image failed to upload: ' . $e->getMessage(),
                ]);
            }
        }

        Post::create([
            'title'          => $request->title,
            'slug'           => $slug,
            'excerpt'        => $request->excerpt,
            'content'        => $request->content,
            'featured_image' => $imagePath,
            'status'         => $request->status,
            'created_by'     => auth()->id(),
        ]);

        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, Post $post)
    {
        // Regenerate slug only if title changed
        $slug = $post->slug;
        if ($request->title !== $post->title) {
            $slug = Str::slug($request->title);
            $original = $slug;
            $counter = 1;
            while (Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                $slug = $original . '-' . $counter;
                $counter++;
            }
        }

        // Handle featured image replacement
        $imagePath = $post->featured_image;
        if ($request->hasFile('featured_image')) {
            try {
                $path = $request->file('featured_image')->storePublicly('posts', 'public');
                if ($path === false) {
                    return back()->withInput()->withErrors([
                        'featured_image' => 'The image could not be saved. Check storage permissions.',
                    ]);
                }
                // Delete old image only after successful new upload
                if ($post->featured_image) {
                    Storage::disk('public')->delete($post->featured_image);
                }
                $imagePath = $path;
            } catch (\Throwable $e) {
                Log::error('Featured image upload failed in update() for post ' . $post->id . ': ' . $e->getMessage());
                return back()->withInput()->withErrors([
                    'featured_image' => 'The featured image failed to upload: ' . $e->getMessage(),
                ]);
            }
        }

        $post->update([
            'title'          => $request->title,
            'slug'           => $slug,
            'excerpt'        => $request->excerpt,
            'content'        => $request->content,
            'featured_image' => $imagePath,
            'status'         => $request->status,
        ]);

        return redirect()->route('posts.index')
            ->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Delete featured image from storage if exists
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return redirect()->back()
            ->with('success', 'Post deleted successfully.');
    }
}
