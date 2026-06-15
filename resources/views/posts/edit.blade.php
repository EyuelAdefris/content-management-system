@extends('layouts.dashboard')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Edit Post</h2>
    <a href="{{ route('posts.index') }}"
       class="inline-flex items-center px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-lg transition duration-150">
        &larr; Cancel
    </a>
</div>

<div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-8 max-w-3xl">
    <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Title --}}
        <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Title</label>
            <input type="text"
                   id="title"
                   name="title"
                   value="{{ old('title', $post->title) }}"
                   placeholder="Enter post title"
                   class="w-full px-4 py-2.5 border {{ $errors->has('title') ? 'border-red-400 bg-red-50' : 'border-slate-300' }} rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150">
            @error('title')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Excerpt --}}
        <div class="mb-6">
            <label for="excerpt" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                Excerpt <span class="text-slate-400 dark:text-slate-500 font-normal">(optional)</span>
            </label>
            <textarea id="excerpt"
                      name="excerpt"
                      rows="3"
                      placeholder="Short description or summary..."
                      class="w-full px-4 py-2.5 border {{ $errors->has('excerpt') ? 'border-red-400 bg-red-50 dark:border-red-500/50 dark:bg-red-950/20' : 'border-slate-300 dark:border-slate-700' }} rounded-lg text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 resize-y bg-white dark:bg-slate-800">{{ old('excerpt', $post->excerpt) }}</textarea>
            @error('excerpt')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Content --}}
        <div class="mb-6">
            <label for="content" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Content</label>
            <textarea id="content"
                      name="content"
                      rows="10"
                      placeholder="Write your post content here..."
                      class="w-full px-4 py-2.5 border {{ $errors->has('content') ? 'border-red-400 bg-red-50 dark:border-red-500/50 dark:bg-red-950/20' : 'border-slate-300 dark:border-slate-700' }} rounded-lg text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 resize-y bg-white dark:bg-slate-800">{{ old('content', $post->content) }}</textarea>
            @error('content')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Featured Image --}}
        <div class="mb-6">
            <label for="featured_image" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                Featured Image <span class="text-slate-400 dark:text-slate-500 font-normal">(optional, jpg/png/webp, max 2MB)</span>
            </label>

            @if($post->featured_image)
                <div class="mb-3 flex items-center space-x-4">
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($post->featured_image) }}"
                         alt="Current featured image"
                         class="w-24 h-24 object-cover rounded-lg border border-slate-200 dark:border-slate-800 shadow-sm">
                    <div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Current image</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Upload a new file below to replace it.</p>
                    </div>
                </div>
            @endif

            <input type="file"
                   id="featured_image"
                   name="featured_image"
                   accept="image/*"
                   class="w-full px-4 py-2.5 border {{ $errors->has('featured_image') ? 'border-red-400 bg-red-50 dark:border-red-500/50 dark:bg-red-950/20' : 'border-slate-300 dark:border-slate-700' }} rounded-lg text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 bg-white dark:bg-slate-800 file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium file:bg-blue-50 dark:file:bg-blue-950/50 file:text-blue-700 dark:file:text-blue-400 hover:file:bg-blue-100 dark:hover:file:bg-blue-900/50">
            @error('featured_image')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Status --}}
        <div class="mb-8">
            <label for="status" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Status</label>
            <select id="status"
                    name="status"
                    class="w-full px-4 py-2.5 border {{ $errors->has('status') ? 'border-red-400 bg-red-50 dark:border-red-500/50 dark:bg-red-950/20' : 'border-slate-300 dark:border-slate-700' }} rounded-lg text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 bg-white dark:bg-slate-800">
                <option value="draft"     {{ old('status', $post->status) == 'draft'     ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published</option>
            </select>
            @error('status')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center space-x-3">
            <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150">
                Update Post
            </button>
            <a href="{{ route('posts.index') }}"
               class="inline-flex items-center px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-lg transition duration-150">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
window.addEventListener('load', function() {
    function initTinyMCE(isDark) {
        if (tinymce.get('content')) {
            tinymce.triggerSave();
            tinymce.remove('#content');
        }
        tinymce.init({
            selector: '#content',
            height: 400,
            menubar: false,
            plugins: 'lists link image table code',
            toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | code',
            skin: isDark ? 'oxide-dark' : 'oxide',
            content_css: isDark ? 'dark' : 'default',
            content_style: isDark ? 'body { background-color: #0f172a; color: #f8fafc; font-family: Figtree, sans-serif; font-size: 14px; }' : 'body { font-family: Figtree, sans-serif; font-size: 14px; }'
        });
    }

    const isDark = document.documentElement.classList.contains('dark');
    initTinyMCE(isDark);

    window.addEventListener('dark-mode-toggle', function(e) {
        initTinyMCE(e.detail);
    });
});
</script>
@endsection
