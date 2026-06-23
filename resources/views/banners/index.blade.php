@extends('layouts.dashboard')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h2 class="text-2xl font-bold text-slate-800">Banners</h2>
    <a href="{{ route('banners.create') }}"
       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150">
        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add New Banner
    </a>
</div>

<!-- Search and Filter Form -->
<div class="mb-6 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4">
    <form action="{{ route('banners.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="flex-1">
            <label for="search" class="sr-only">Search Banners</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by title..." class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm">
        </div>
        <div class="w-full sm:w-48">
            <label for="is_active" class="sr-only">Filter by Status</label>
            <select name="is_active" id="is_active" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm">
                <option value="">All Statuses</option>
                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div class="flex space-x-2">
            <button type="submit" class="px-4 py-2 bg-slate-800 dark:bg-slate-600 hover:bg-slate-700 dark:hover:bg-slate-500 text-white text-sm font-medium rounded-lg transition duration-150">
                Filter
            </button>
            @if(request()->hasAny(['search', 'is_active']))
                <a href="{{ route('banners.index') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-sm font-medium rounded-lg transition duration-150">
                    Clear
                </a>
            @endif
        </div>
    </form>
</div>

<div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden overflow-x-auto">
    @if($banners->isEmpty())
        <div class="py-16 text-center text-slate-500">
            <svg class="mx-auto mb-3 h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="text-sm font-medium">No banners found.</p>
            <p class="text-xs text-slate-400 mt-1">Get started by creating your first banner.</p>
        </div>
    @else
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
            <thead class="bg-slate-50 dark:bg-slate-900/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Link</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Position</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Active</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Author</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                @foreach($banners as $banner)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition duration-100">
                    <td class="px-6 py-4">
                        <img src="{{ $banner->image }}"
                             alt="{{ $banner->title }}"
                             class="w-16 h-16 object-cover rounded border border-slate-200 dark:border-slate-600">
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $banner->title }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($banner->link_url)
                            <a href="{{ $banner->link_url }}" target="_blank" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 truncate max-w-xs block">
                                {{ $banner->link_url }}
                            </a>
                        @else
                            <span class="text-slate-400 dark:text-slate-500">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                        {{ $banner->position }}
                    </td>
                    <td class="px-6 py-4">
                        @if($banner->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Yes
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                No
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                        {{ $banner->author->name ?? 'Unknown' }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex items-center space-x-2">
                            @if(auth()->user()->hasRole('admin') || auth()->id() === $banner->created_by)
                                <a href="{{ route('banners.edit', $banner) }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-medium rounded-md transition duration-150">
                                    Edit
                                </a>
                                <form action="{{ route('banners.destroy', $banner) }}" method="POST"
                                      data-confirm="Are you sure you want to delete this banner?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-medium rounded-md transition duration-150">
                                        Delete
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-slate-400 dark:text-slate-500 italic">View only</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@if($banners->hasPages())
    <div class="mt-6">
        {{ $banners->links() }}
    </div>
@endif

@endsection
