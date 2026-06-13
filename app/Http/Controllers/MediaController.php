<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index()
    {
        $mediaItems = Media::with('uploader')->latest()->get();
        return view('media.index', compact('mediaItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,gif,webp,mp4,pdf,docx,doc',
        ]);

        $file = $request->file('file');
        $storedPath = $file->store('media', 'public');

        $mime = $file->getMimeType();
        if (str_starts_with($mime, 'image/')) {
            $fileType = 'image';
        } elseif (str_starts_with($mime, 'video/')) {
            $fileType = 'video';
        } else {
            $fileType = 'document';
        }

        Media::create([
            'file_name'   => $file->getClientOriginalName(),
            'file_path'   => $storedPath,
            'file_type'   => $fileType,
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('media.index')->with('success', 'Media uploaded successfully.');
    }

    public function destroy(Media $media)
    {
        if (!auth()->user()->hasRole('admin') && $media->uploaded_by !== auth()->id()) {
            abort(403);
        }

        if (Storage::disk('public')->exists($media->file_path)) {
            Storage::disk('public')->delete($media->file_path);
        }

        $media->delete();

        return redirect()->back()->with('success', 'Media deleted successfully.');
    }
}
