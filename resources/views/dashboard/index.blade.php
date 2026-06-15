@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">

    {{-- Stat cards grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Total Posts Card --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-md transition duration-200">
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl inline-block">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-800 dark:text-white mt-3">{{ $postsCount }}</div>
                    <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">Total Posts</span>
                </div>
            </div>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-2">{{ $postsPublishedCount }} published · {{ $postsDraftCount }} drafts</p>
        </div>

        {{-- Pages Card --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-md transition duration-200">
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <div class="p-3 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-xl inline-block">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-800 dark:text-white mt-3">{{ $pagesCount }}</div>
                    <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">Pages</span>
                </div>
            </div>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-2">Active site pages</p>
        </div>

        {{-- Media Card --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-md transition duration-200">
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <div class="p-3 bg-violet-50 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400 rounded-xl inline-block">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-800 dark:text-white mt-3">{{ $mediaCount }}</div>
                    <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">Media Files</span>
                </div>
            </div>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-2">Images, videos & files</p>
        </div>

        {{-- Users Card --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-md transition duration-200">
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <div class="p-3 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-xl inline-block">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-800 dark:text-white mt-3">{{ $usersCount }}</div>
                    <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">Users</span>
                </div>
            </div>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-2">Team members</p>
        </div>
    </div>


    {{-- Recent Posts & Recent Pages --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Recent Posts --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-700">
                <div class="flex items-center space-x-2">
                    <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-base font-bold text-slate-800 dark:text-white">Recent Posts</h3>
                </div>
                <a href="{{ route('posts.index') }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 flex items-center space-x-1 transition">
                    <span>View All</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($recentPosts as $post)
                    <div class="flex items-center justify-between px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition duration-150">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-slate-700 dark:text-white truncate">{{ $post->title }}</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                                by {{ $post->author->name ?? 'Unknown' }} · {{ $post->created_at->format('M d, Y') }}
                            </p>
                        </div>
                        <span class="ml-4 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold shrink-0
                            {{ $post->status === 'published'
                                ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400'
                                : 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400' }}">
                            {{ strtoupper($post->status) }}
                        </span>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center">
                        <p class="text-sm text-slate-400 dark:text-slate-500">No posts yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Pages --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-700">
                <div class="flex items-center space-x-2">
                    <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-base font-bold text-slate-800 dark:text-white">Recent Pages</h3>
                </div>
                <a href="{{ route('pages.index') }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 flex items-center space-x-1 transition">
                    <span>View All</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($recentPages as $page)
                    <div class="flex items-center justify-between px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition duration-150">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-slate-700 dark:text-white truncate">{{ $page->title }}</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                                by {{ $page->author->name ?? 'Unknown' }} · {{ $page->created_at->format('M d, Y') }}
                            </p>
                        </div>
                        <span class="ml-4 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold shrink-0
                            {{ $page->status === 'published'
                                ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400'
                                : 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400' }}">
                            {{ strtoupper($page->status) }}
                        </span>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center">
                        <p class="text-sm text-slate-400 dark:text-slate-500">No pages yet</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
