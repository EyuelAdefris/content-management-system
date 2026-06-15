@extends('layouts.dashboard')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h2 class="text-2xl font-bold text-slate-800">Pages</h2>
    <a href="{{ route('pages.create') }}"
       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150">
        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add New Page
    </a>
</div>

<!-- Search and Filter Form -->
<div class="mb-6 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4">
    <form action="{{ route('pages.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="flex-1">
            <label for="search" class="sr-only">Search Pages</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by title or slug..." class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm">
        </div>
        <div class="w-full sm:w-48">
            <label for="status" class="sr-only">Filter by Status</label>
            <select name="status" id="status" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm">
                <option value="">All Statuses</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
        </div>
        <div class="flex space-x-2">
            <button type="submit" class="px-4 py-2 bg-slate-800 dark:bg-slate-600 hover:bg-slate-700 dark:hover:bg-slate-500 text-white text-sm font-medium rounded-lg transition duration-150">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('pages.index') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-sm font-medium rounded-lg transition duration-150">
                    Clear
                </a>
            @endif
        </div>
    </form>
</div>

@if(session('success'))
    <div class="mb-6 flex items-center px-4 py-3 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg">
        <svg class="mr-2 h-4 w-4 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        {{ session('success') }}
    </div>
@endif

<div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden overflow-x-auto">
    @if($pages->isEmpty())
        <div class="py-16 text-center text-slate-500">
            <svg class="mx-auto mb-3 h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-sm font-medium">No pages found.</p>
            <p class="text-xs text-slate-400 mt-1">Get started by creating your first page.</p>
        </div>
    @else
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
            <thead class="bg-slate-50 dark:bg-slate-900/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Author</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Created At</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                @foreach($pages as $page)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition duration-100">
                    <td class="px-6 py-4 text-sm font-medium text-slate-800 dark:text-slate-200">{{ $page->title }}</td>
                    <td class="px-6 py-4 text-sm text-slate-500 font-mono">{{ $page->slug }}</td>
                    <td class="px-6 py-4">
                        @if($page->status === 'published')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Published
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                Draft
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">{{ $page->author->name ?? 'Unknown' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">{{ $page->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex items-center space-x-2">
                            @if(auth()->user()->hasRole('admin') || auth()->id() === $page->created_by)
                                <a href="{{ route('pages.edit', $page) }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-medium rounded-md transition duration-150">
                                    Edit
                                </a>
                                <form action="{{ route('pages.destroy', $page) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this page? This action cannot be undone.')">
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

@if($pages->hasPages())
    <div class="mt-6">
        {{ $pages->links() }}
    </div>
@endif

@endsection
