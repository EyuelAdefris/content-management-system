<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::with('uploader')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('file_name', 'like', "%{$search}%");
        }

        if ($request->filled('file_type')) {
            $query->where('file_type', $request->file_type);
        }

        $mediaItems = $query->paginate(12)->withQueryString();

        return view('media.index', compact('mediaItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,gif,webp,mp4,pdf,docx,doc',
        ]);

        $file       = $request->file('file');
        $storedPath = $file->store('media', 'cloudinary');
        $fileUrl    = Storage::disk('cloudinary')->url($storedPath);

        $mime = $file->getMimeType();
        if (str_starts_with($mime, 'image/')) {
            $fileType = 'image';
        } elseif (str_starts_with($mime, 'video/')) {
            $fileType = 'video';
        } else {
            $fileType = 'document';
        }

        $media = Media::create([
            'file_name'   => $file->getClientOriginalName(),
            'file_path'   => $fileUrl,
            'file_type'   => $fileType,
            'uploaded_by' => auth()->id(),
        ]);

        try {
            $users = \App\Models\User::all();
            \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\ContentChangeNotification('Media', 'uploaded', $media->file_name, auth()->user()->name ?? 'Unknown'));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send ContentChangeNotification: ' . $e->getMessage());
        }

        return redirect()->route('media.index')->with('success', 'Media uploaded successfully.');
    }

    public function destroy(Media $media)
    {
        if (!auth()->user()->hasRole('admin') && $media->uploaded_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Extract the Cloudinary path from the stored CDN URL for deletion
        $storedPath = $media->file_path;
        if (str_starts_with($storedPath, 'http')) {
            // Parse public_id from URL: .../upload/{public_id}.{ext}
            if (preg_match('#/upload/(?:v\d+/)?(.+)$#', $storedPath, $matches)) {
                $storedPath = $matches[1];
            }
        }
        try {
            Storage::disk('cloudinary')->delete($storedPath);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Cloudinary delete failed: ' . $e->getMessage());
        }

        $media->delete();

        return redirect()->back()->with('success', 'Media deleted successfully.');
    }
}
