@extends('layouts.dashboard')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h2 class="text-2xl font-bold text-slate-800">Create New Page</h2>
    <a href="{{ route('pages.index') }}"
       class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition duration-150">
        &larr; Cancel
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 max-w-3xl">
    <form method="POST" action="{{ route('pages.store') }}">
        @csrf

        {{-- Title --}}
        <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-slate-700 mb-1.5">Title</label>
            <input type="text"
                   id="title"
                   name="title"
                   value="{{ old('title') }}"
                   placeholder="Enter page title"
                   class="w-full px-4 py-2.5 border {{ $errors->has('title') ? 'border-red-400 bg-red-50' : 'border-slate-300' }} rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150">
            @error('title')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Content --}}
        <div class="mb-6">
            <label for="content" class="block text-sm font-medium text-slate-700 mb-1.5">Content</label>
            <textarea id="content"
                      name="content"
                      rows="10"
                      placeholder="Write your page content here..."
                      class="w-full px-4 py-2.5 border {{ $errors->has('content') ? 'border-red-400 bg-red-50' : 'border-slate-300' }} rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150 resize-y">{{ old('content') }}</textarea>
            @error('content')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Status --}}
        <div class="mb-8">
            <label for="status" class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
            <select id="status"
                    name="status"
                    class="w-full px-4 py-2.5 border {{ $errors->has('status') ? 'border-red-400 bg-red-50' : 'border-slate-300' }} rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150 bg-white">
                <option value="draft"     {{ old('status') == 'draft'     ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
            </select>
            @error('status')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center space-x-3">
            <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150">
                Create Page
            </button>
            <a href="{{ route('pages.index') }}"
               class="inline-flex items-center px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition duration-150">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
window.addEventListener('load', function() {
    tinymce.init({
        selector: '#content',
        height: 400,
        menubar: false,
        plugins: 'lists link image table code',
        toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | code'
    });
});
</script>
@endsection
