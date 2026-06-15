@extends('layouts.dashboard')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Create New Banner</h2>
    <a href="{{ route('banners.index') }}"
       class="inline-flex items-center px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-lg transition duration-150">
        &larr; Cancel
    </a>
</div>

<div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-8 max-w-3xl">
    <form method="POST" action="{{ route('banners.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Title --}}
        <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Title</label>
            <input type="text"
                   id="title"
                   name="title"
                   value="{{ old('title') }}"
                   placeholder="Enter banner title"
                   class="w-full px-4 py-2.5 border {{ $errors->has('title') ? 'border-red-400 bg-red-50 dark:border-red-500/50 dark:bg-red-950/20' : 'border-slate-300 dark:border-slate-700' }} rounded-lg text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 bg-white dark:bg-slate-800" required>
            @error('title')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Image --}}
        <div class="mb-6">
            <label for="image" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                Banner Image <span class="text-slate-400 dark:text-slate-500 font-normal">(required, jpg/png/webp, max 2MB)</span>
            </label>
            <input type="file"
                   id="image"
                   name="image"
                   accept="image/*"
                   class="w-full px-4 py-2.5 border {{ $errors->has('image') ? 'border-red-400 bg-red-50 dark:border-red-500/50 dark:bg-red-950/20' : 'border-slate-300 dark:border-slate-700' }} rounded-lg text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 bg-white dark:bg-slate-800 file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium file:bg-blue-50 dark:file:bg-blue-950/50 file:text-blue-700 dark:file:text-blue-400 hover:file:bg-blue-100 dark:hover:file:bg-blue-900/50" required>
            @error('image')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Link URL --}}
        <div class="mb-6">
            <label for="link_url" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                Link URL <span class="text-slate-400 dark:text-slate-500 font-normal">(optional)</span>
            </label>
            <input type="text"
                   id="link_url"
                   name="link_url"
                   value="{{ old('link_url') }}"
                   placeholder="https://..."
                   class="w-full px-4 py-2.5 border {{ $errors->has('link_url') ? 'border-red-400 bg-red-50 dark:border-red-500/50 dark:bg-red-950/20' : 'border-slate-300 dark:border-slate-700' }} rounded-lg text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 bg-white dark:bg-slate-800">
            @error('link_url')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Position --}}
        <div class="mb-6">
            <label for="position" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Position</label>
            <input type="number"
                   id="position"
                   name="position"
                   value="{{ old('position', 0) }}"
                   min="0"
                   class="w-full px-4 py-2.5 border {{ $errors->has('position') ? 'border-red-400 bg-red-50 dark:border-red-500/50 dark:bg-red-950/20' : 'border-slate-300 dark:border-slate-700' }} rounded-lg text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 bg-white dark:bg-slate-800" required>
            @error('position')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Active Checkbox --}}
        <div class="mb-8">
            <div class="flex items-center">
                <input type="checkbox"
                       id="is_active"
                       name="is_active"
                       value="1"
                       {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 dark:border-slate-700 dark:bg-slate-800 rounded">
                <label for="is_active" class="ml-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                    Active (visible on website)
                </label>
            </div>
            @error('is_active')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center space-x-3">
            <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition duration-150">
                Create Banner
            </button>
            <a href="{{ route('banners.index') }}"
               class="inline-flex items-center px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-lg transition duration-150">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
