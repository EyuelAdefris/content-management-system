@extends('layouts.dashboard')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h2 class="text-2xl font-bold text-slate-800">Media Library</h2>
</div>

@if(session('success'))
    <div class="mb-6 flex items-center px-4 py-3 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg shadow-sm">
        <svg class="mr-2 h-4 w-4 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        {{ session('success') }}
    </div>
@endif

<!-- Upload Form -->
<form method="POST" action="{{ route('media.store') }}" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 mb-8">
    @csrf
    <div class="flex flex-col md:flex-row md:items-end gap-4">
        <div class="flex-1">
            <label class="block text-sm font-semibold text-slate-700 mb-2" for="file">Upload File</label>
            <input type="file" name="file" id="file" class="w-full text-sm text-slate-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-lg file:border-0
                file:text-sm file:font-semibold
                file:bg-indigo-50 file:text-indigo-700
                hover:file:bg-indigo-100
                border border-slate-200 rounded-lg p-2 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
            @error('file')
                <p class="text-sm text-red-600 mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <button type="submit" class="w-full md:w-auto inline-flex justify-center items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition duration-150">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Upload
            </button>
        </div>
    </div>
</form>

<!-- Media Grid -->
@if($mediaItems->isEmpty())
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 py-16 text-center text-slate-500">
        <svg class="mx-auto mb-3 h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <p class="text-sm font-medium">No media files uploaded yet.</p>
        <p class="text-xs text-slate-400 mt-1">Upload an image, video, or document to start.</p>
    </div>
@else
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach($mediaItems as $item)
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition duration-150 flex flex-col justify-between">
                <div class="p-3">
                    <!-- Preview -->
                    <div class="mb-3">
                        @if($item->file_type === 'image')
                            <img src="{{ Storage::url($item->file_path) }}" class="w-full h-24 object-cover rounded-lg border border-slate-100">
                        @elseif($item->file_type === 'video')
                            <div class="w-full h-24 rounded-lg bg-slate-100 border border-slate-200 flex flex-col items-center justify-center text-slate-500">
                                <span class="text-3xl">🎥</span>
                                <span class="text-xs font-semibold mt-1">Video</span>
                            </div>
                        @else
                            <div class="w-full h-24 rounded-lg bg-slate-100 border border-slate-200 flex flex-col items-center justify-center text-slate-500">
                                <span class="text-3xl">📄</span>
                                <span class="text-xs font-semibold mt-1">Document</span>
                            </div>
                        @endif
                    </div>

                    <!-- Info -->
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-slate-700 truncate" title="{{ $item->file_name }}">
                            {{ $item->file_name }}
                        </p>
                        <p class="text-[10px] text-slate-500 truncate">
                            By: <span class="font-medium text-slate-700">{{ $item->uploader->name ?? 'Unknown' }}</span>
                        </p>
                        <p class="text-[10px] text-slate-400">
                            {{ $item->created_at->format('M d, Y') }}
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="px-3 pb-3 pt-1 bg-slate-50 border-t border-slate-100 flex flex-wrap gap-1.5">
                    <button type="button" 
                            onclick="navigator.clipboard.writeText('{{ asset(Storage::url($item->file_path)) }}'); const btn = this; const origText = btn.innerText; btn.innerText = 'Copied!'; btn.classList.add('bg-green-50', 'text-green-700', 'border-green-200'); setTimeout(() => { btn.innerText = origText; btn.classList.remove('bg-green-50', 'text-green-700', 'border-green-200'); }, 1500);" 
                            class="flex-1 inline-flex justify-center items-center px-2 py-1 bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 text-xs font-medium rounded transition duration-150">
                        Copy URL
                    </button>
                    @if(auth()->user()->hasRole('admin') || $item->uploaded_by === auth()->id())
                        <form action="{{ route('media.destroy', $item) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this media file?')" class="inline flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex justify-center items-center px-2 py-1 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-medium rounded transition duration-150">
                                Delete
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
