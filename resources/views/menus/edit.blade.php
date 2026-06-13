@extends('layouts.dashboard')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h2 class="text-2xl font-bold text-slate-800">Edit Menu Item</h2>
    <a href="{{ route('menus.index') }}"
       class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition duration-150">
        &larr; Cancel
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 max-w-3xl">
    <form method="POST" action="{{ route('menus.update', $menu) }}">
        @csrf
        @method('PUT')

        {{-- Label --}}
        <div class="mb-6">
            <label for="label" class="block text-sm font-medium text-slate-700 mb-1.5">Label</label>
            <input type="text"
                   id="label"
                   name="label"
                   value="{{ old('label', $menu->label) }}"
                   placeholder="e.g. About Us"
                   class="w-full px-4 py-2.5 border {{ $errors->has('label') ? 'border-red-400 bg-red-50' : 'border-slate-300' }} rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150" required>
            @error('label')
                <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- URL --}}
        <div class="mb-6">
            <label for="url" class="block text-sm font-medium text-slate-700 mb-1.5">URL</label>
            <input type="text"
                   id="url"
                   name="url"
                   value="{{ old('url', $menu->url) }}"
                   placeholder="/about or https://example.com"
                   class="w-full px-4 py-2.5 border {{ $errors->has('url') ? 'border-red-400 bg-red-50' : 'border-slate-300' }} rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150" required>
            @error('url')
                <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Position --}}
        <div class="mb-6">
            <label for="position" class="block text-sm font-medium text-slate-700 mb-1.5">Position</label>
            <input type="number"
                   id="position"
                   name="position"
                   value="{{ old('position', $menu->position) }}"
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
                       {{ old('is_active', $menu->is_active ? '1' : '0') == '1' ? 'checked' : '' }}
                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded">
                <label for="is_active" class="ml-2 block text-sm font-medium text-slate-700">
                    Active (visible on website navigation)
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
                Update Menu Item
            </button>
            <a href="{{ route('menus.index') }}"
               class="inline-flex items-center px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition duration-150">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
