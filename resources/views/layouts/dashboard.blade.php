<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') !== 'false' }" x-init="$watch('darkMode', val => { localStorage.setItem('darkMode', val); window.dispatchEvent(new CustomEvent('dark-mode-toggle', { detail: val })); })" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Content Management System') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=3">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Prevent Flash of Unstyled Content (FOUC) for Dark Mode -->
    <script>
        if (localStorage.getItem('darkMode') !== 'false') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 transition-colors duration-300" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden relative">
        <!-- Backdrop overlay for mobile -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false" 
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-30 bg-slate-900/40 backdrop-blur-sm md:hidden"
             style="display: none;">
        </div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-40 w-64 bg-slate-900 text-slate-300 flex-shrink-0 flex flex-col justify-between transform md:relative md:translate-x-0 transition-transform duration-300 ease-in-out">
            <div>
                <!-- Brand Logo / Header -->
                <div class="h-16 flex items-center justify-between px-6 bg-slate-950 border-b border-slate-800">
                    <span class="text-xl font-bold tracking-wider text-white">CMS Engine</span>
                    <!-- Close button for mobile -->
                    <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="mt-6 px-4 space-y-1">

                    {{-- Dashboard --}}
                    @php $active = request()->routeIs('dashboard'); @endphp
                    <a href="/dashboard"
                       class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition duration-150
                              {{ $active
                                  ? 'bg-blue-600 text-white shadow-sm'
                                  : 'text-slate-300 hover:bg-blue-600/10 hover:text-blue-400' }}">
                        <svg class="mr-3 h-5 w-5 {{ $active ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>

                    {{-- Pages --}}
                    @php $active = request()->routeIs('pages.*'); @endphp
                    <a href="{{ route('pages.index') }}"
                       class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition duration-150
                              {{ $active
                                  ? 'bg-blue-600 text-white shadow-sm'
                                  : 'text-slate-300 hover:bg-blue-600/10 hover:text-blue-400' }}">
                        <svg class="mr-3 h-5 w-5 {{ $active ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 012 2v6a2 2 0 01-2 2h-2m-6 4h.01" />
                        </svg>
                        Pages
                    </a>

                    {{-- Posts --}}
                    @php $active = request()->routeIs('posts.*'); @endphp
                    <a href="{{ route('posts.index') }}"
                       class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition duration-150
                              {{ $active
                                  ? 'bg-blue-600 text-white shadow-sm'
                                  : 'text-slate-300 hover:bg-blue-600/10 hover:text-blue-400' }}">
                        <svg class="mr-3 h-5 w-5 {{ $active ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Posts
                    </a>

                    {{-- Media --}}
                    @php $active = request()->routeIs('media.*'); @endphp
                    <a href="{{ route('media.index') }}"
                       class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition duration-150
                              {{ $active
                                  ? 'bg-blue-600 text-white shadow-sm'
                                  : 'text-slate-300 hover:bg-blue-600/10 hover:text-blue-400' }}">
                        <svg class="mr-3 h-5 w-5 {{ $active ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Media
                    </a>

                    {{-- Banners --}}
                    @php $active = request()->routeIs('banners.*'); @endphp
                    <a href="{{ route('banners.index') }}"
                       class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition duration-150
                              {{ $active
                                  ? 'bg-blue-600 text-white shadow-sm'
                                  : 'text-slate-300 hover:bg-blue-600/10 hover:text-blue-400' }}">
                        <svg class="mr-3 h-5 w-5 {{ $active ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Banners
                    </a>

                    {{-- Menus --}}
                    @php $active = request()->routeIs('menus.*'); @endphp
                    <a href="{{ route('menus.index') }}"
                       class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition duration-150
                              {{ $active
                                  ? 'bg-blue-600 text-white shadow-sm'
                                  : 'text-slate-300 hover:bg-blue-600/10 hover:text-blue-400' }}">
                        <svg class="mr-3 h-5 w-5 {{ $active ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        Menus
                    </a>

                    {{-- Users (admin only) --}}
                    @role('admin')
                    @php $active = request()->routeIs('users.*'); @endphp
                    <a href="/dashboard/users"
                       class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition duration-150
                              {{ $active
                                  ? 'bg-blue-600 text-white shadow-sm'
                                  : 'text-slate-300 hover:bg-blue-600/10 hover:text-blue-400' }}">
                        <svg class="mr-3 h-5 w-5 {{ $active ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Users
                    </a>
                    @endrole

                </nav>
            </div>
            
            <!-- Sidebar Footer / Powered by -->
            <div class="p-4 bg-slate-950 text-xs text-slate-500 text-center border-t border-slate-800">
                CMS System v1.0.0
            </div>
        </aside>

        <!-- Main Body -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Topbar -->
            <header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-4 md:px-8 shadow-sm flex-shrink-0 transition-colors duration-300">
                <div class="flex items-center space-x-3">
                    <!-- Hamburger button for mobile -->
                    <button @click="sidebarOpen = true" class="md:hidden p-2 -ml-2 rounded-lg text-slate-500 dark:text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <!-- Optional topbar title/breadcrumb -->
                    <h1 class="text-lg font-semibold text-slate-800 dark:text-white">Dashboard</h1>
                </div>
                
                <div class="flex items-center space-x-4 md:space-x-6">
                    <!-- Dark Mode Toggle -->
                    <button @click="darkMode = !darkMode"
                            class="p-2 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-700 dark:hover:text-slate-200 transition duration-150 focus:outline-none"
                            :title="darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
                        <!-- Sun icon (shown in dark mode) -->
                        <svg x-show="darkMode" class="h-5 w-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <!-- Moon icon (shown in light mode) -->
                        <svg x-show="!darkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <div class="relative" x-data="{
                        notifOpen: false,
                        unreadCount: 0,
                        notifications: [],
                        fetchNotifications() {
                            fetch('/notifications')
                                .then(res => res.json())
                                .then(data => {
                                    this.notifications = data.notifications;
                                    this.unreadCount = data.unreadCount;
                                });
                        },
                        markAsSeen() {
                            if (this.unreadCount > 0) {
                                fetch('/notifications/mark-as-read', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').getAttribute('content')
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        this.unreadCount = 0;
                                    }
                                });
                            }
                        }
                    }" x-init="fetchNotifications()">
                        <button @click="notifOpen = !notifOpen; if(notifOpen) markAsSeen();" class="relative p-2 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-700 focus:outline-none transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <template x-if="unreadCount > 0">
                                <span class="absolute -top-0.5 -right-0.5 min-w-[1.25rem] h-5 px-1 bg-red-500 text-white rounded-full flex items-center justify-center text-[10px] font-bold" x-text="unreadCount"></span>
                            </template>
                        </button>
                        <div x-show="notifOpen" @click.outside="notifOpen = false" class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 z-50 overflow-hidden" x-transition style="display: none;">
                            <div class="p-4 border-b border-slate-100 dark:border-slate-700">
                                <h3 class="font-semibold text-slate-800 dark:text-white">Notifications</h3>
                            </div>
                            <div class="max-h-64 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-700">
                                <template x-for="notif in notifications" :key="notif.id">
                                    <div class="p-4 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                                        <p class="text-sm font-semibold text-slate-800 dark:text-white" x-text="notif.data.title"></p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1" x-text="(notif.data.action_text || 'New post by ') + notif.data.author_name"></p>
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 block" x-text="notif.created_at"></span>
                                    </div>
                                </template>
                                <template x-if="notifications.length === 0">
                                    <div class="p-8 text-sm text-slate-500 dark:text-slate-400 text-center">
                                        No new notifications
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ userOpen: false }">
                        <button @click="userOpen = !userOpen"
                                class="flex items-center gap-3 px-3 py-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 transition duration-150 focus:outline-none">
                            @if(auth()->user()->profile_image)
                                <img src="{{ asset(auth()->user()->profile_image) }}" alt="Avatar" style="width: 2rem; height: 2rem;" class="flex-shrink-0 rounded-full object-cover border border-slate-200 dark:border-slate-600 shadow-sm">
                            @else
                                <div style="width: 2rem; height: 2rem;" class="flex-shrink-0 rounded-full bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center text-white text-xs font-bold uppercase tracking-wider">
                                    {{ substr(auth()->user()->name, 0, 2) }}
                                </div>
                            @endif
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300 hidden sm:inline">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-slate-400 hidden sm:block transition-transform duration-200" :class="userOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="userOpen" @click.outside="userOpen = false"
                             class="absolute right-0 mt-2 w-52 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 z-50 overflow-hidden"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             style="display:none;">
                            <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
                                <p class="text-xs text-slate-500 dark:text-slate-400">Signed in as</p>
                                <p class="text-sm font-semibold text-slate-800 dark:text-white truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('account.index') }}"
                                   class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/60 transition">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Account Settings
                                </a>
                                <div class="border-t border-slate-100 dark:border-slate-700 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition text-left">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-slate-950 transition-colors duration-300">
                <div class="py-6 px-4 md:py-8 md:px-8 max-w-7xl mx-auto">
                    {{-- Global validation error banner --}}
                    @if($errors->any())
                        <div class="mb-6 bg-red-50 dark:bg-red-900/30 border border-red-300 dark:border-red-800 rounded-lg p-4">
                            <p class="text-sm font-semibold text-red-700 dark:text-red-400 mb-1">Please fix the following errors:</p>
                            <ul class="list-disc list-inside space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li class="text-sm text-red-600 dark:text-red-300">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const isDark = () => document.documentElement.classList.contains('dark');

            // Toast notification setup
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                background: isDark() ? '#1e293b' : '#ffffff',
                color: isDark() ? '#f8fafc' : '#0f172a',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            // Show session success messages
            @if(session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif

            // Show session error messages
            @if(session('error'))
                Toast.fire({
                    icon: 'error',
                    title: "{{ session('error') }}"
                });
            @endif

            // Intercept delete forms
            document.querySelectorAll('form[data-confirm]').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const message = this.getAttribute('data-confirm');
                    
                    Swal.fire({
                        title: 'Are you sure?',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: isDark() ? '#475569' : '#94a3b8',
                        confirmButtonText: 'Yes, delete it!',
                        background: isDark() ? '#1e293b' : '#ffffff',
                        color: isDark() ? '#f8fafc' : '#0f172a'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
