@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome banner -->
    <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-2xl p-8 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10">
            <h2 class="text-3xl font-bold tracking-tight">Welcome back, {{ auth()->user()->name }}!</h2>
            <p class="text-indigo-100 mt-2 max-w-xl">
                Here's a quick overview of your Content Management System. Manage pages, posts, media assets, banners and users all from one place.
            </p>
        </div>
        <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-12 translate-y-12">
            <svg class="w-96 h-96" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v-2H7v-2h7v-2H7V7h7v10z"/>
            </svg>
        </div>
    </div>

    <!-- Stat cards grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Pages Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition duration-200 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pages</span>
                <div class="text-3xl font-extrabold text-slate-800">{{ $pagesCount }}</div>
            </div>
            <div class="p-4 bg-indigo-50 text-indigo-600 rounded-xl">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 012 2v6a2 2 0 01-2 2h-2m-6 4h.01" />
                </svg>
            </div>
        </div>

        <!-- Posts Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition duration-200 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Posts</span>
                <div class="text-3xl font-extrabold text-slate-800">{{ $postsCount }}</div>
            </div>
            <div class="p-4 bg-emerald-50 text-emerald-600 rounded-xl">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
        </div>

        <!-- Media Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition duration-200 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Media</span>
                <div class="text-3xl font-extrabold text-slate-800">{{ $mediaCount }}</div>
            </div>
            <div class="p-4 bg-violet-50 text-violet-600 rounded-xl">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>

        <!-- Banners Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition duration-200 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Banners</span>
                <div class="text-3xl font-extrabold text-slate-800">{{ $bannersCount }}</div>
            </div>
            <div class="p-4 bg-amber-50 text-amber-600 rounded-xl">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
    </div>
</div>
@endsection
