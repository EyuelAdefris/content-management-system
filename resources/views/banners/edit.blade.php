@extends('layouts.dashboard')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h2 class="text-2xl font-bold text-slate-800">Edit Banner</h2>
    <a href="{{ route('banners.index') }}"
       class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition duration-150">
        &larr; Cancel
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 max-w-3xl">
    <form method="POST" action="{{ route('banners.update', $banner) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Title --}}
        <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-slate-700 mb-1.5">Title</label>
            <input type="text"
                   id="title"
                   name="title"
                   value="{{ old('title', $banner->title) }}"
                   placeholder="Enter banner title"
                   class="w-full px-4 py-2.5 border {{ $errors->has('title') ? 'border-red-400 bg-red-50' : 'border-slate-300' }} rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150" required>
            @error('title')
                <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Current Image & Upload --}}
        <div class="mb-6">
            <label class="block text-sm font-medium text-slate-700 mb-2">Current Image</label>
            <div class="mb-3">
                <img src="{{ Storage::url($banner->image) }}"
                     alt="Current Banner Image"
                     class="w-48 h-24 object-cover rounded-lg border border-slate-200 shadow-sm">
            </div>
            <label for="image" class="block text-sm font-medium text-slate-700 mb-1.5">
                Upload New Image <span class="text-slate-400 font-normal">(optional, jpg/png/webp, max 2MB)</span>
            </label>
            <input type="file"
                   id="image"
                   name="image"
                   accept="image/*"
                   class="w-full px-4 py-2.5 border {{ $errors->has('image') ? 'border-red-400 bg-red-50' : 'border-slate-300' }} rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150 file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            @error('image')
                <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Link URL --}}
        <div class="mb-6">
            <label for="link_url" class="block text-sm font-medium text-slate-700 mb-1.5">
                Link URL <span class="text-slate-400 font-normal">(optional)</span>
            </label>
            <input type="text"
                   id="link_url"
                   name="link_url"
                   value="{{ old('link_url', $banner->link_url) }}"
                   placeholder="https://..."
                   class="w-full px-4 py-2.5 border {{ $errors->has('link_url') ? 'border-red-400 bg-red-50' : 'border-slate-300' }} rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150">
            @error('link_url')
                <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Position --}}
        <div class="mb-6">
            <label for="position" class="block text-sm font-medium text-slate-700 mb-1.5">Position</label>
            <input type="number"
                   id="position"
                   name="position"
                   value="{{ old('position', $banner->position) }}"
                   min="0"
                   class="w-full px-4 py-2.5 border {{ $errors->has('position') ? 'border-red-400 bg-red-50' : 'border-slate-300' }} rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150" required>
            @error('position')
                <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Active Checkbox --}}
        <div class="mb-8">
            <div class="flex items-center">
                <input type="checkbox"
                       id="is_active"
                       name="is_active"
                       value="1"
                       {{ old('is_active', $banner->is_active ? '1' : '0') == '1' ? 'checked' : '' }}
                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded">
                <label for="is_active" class="ml-2 block text-sm font-medium text-slate-700">
                    Active (visible on website)
                </label>
            </div>
            @error('is_active')
                <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center space-x-3">
            <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition duration-150">
                Update Banner
            </button>
            <a href="{{ route('banners.index') }}"
               class="inline-flex items-center px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition duration-150">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
