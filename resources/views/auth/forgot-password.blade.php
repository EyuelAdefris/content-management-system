<x-guest-layout>
    <x-slot name="wrapperClass">min-h-screen flex items-center justify-center bg-gray-50 dark:bg-slate-900 py-12 px-4 sm:px-6 lg:px-8 transition-colors duration-300</x-slot>
    <x-slot name="cardClass">bg-white dark:bg-slate-800 rounded-2xl shadow-xl overflow-hidden max-w-4xl w-full flex flex-col md:flex-row animate-fade-in</x-slot>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    <!-- LEFT PANEL: Brand & Greeting -->
    <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-blue-600 to-green-500 p-12 text-white relative overflow-hidden flex-col justify-between">
        <!-- Decorative Shapes -->
        <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -right-20 w-60 h-60 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute top-1/3 right-8 w-16 h-16 bg-white/5 rounded-full pointer-events-none"></div>

        <div class="relative z-10">
            <span class="text-xl font-bold tracking-wider text-white">CMS Engine</span>
        </div>

        <div class="relative z-10 my-auto space-y-4">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
            <h2 class="text-3xl font-bold leading-tight">Reset Password</h2>
            <p class="text-blue-100 text-base">Enter your email and we'll send you a secure link to reset your password.</p>
        </div>

        <div class="relative z-10 text-xs text-blue-200/80">
            &copy; {{ date('Y') }} CMS Engine. All rights reserved.
        </div>
    </div>

    <!-- RIGHT PANEL: Form -->
    <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Forgot Password?</h2>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">No worries — we'll send you a reset link.</p>
        </div>

        <!-- Session Status (success message) -->
        @if (session('status'))
            <div class="mb-5 flex items-center gap-2 px-4 py-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 text-sm rounded-xl">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1.5">Email Address</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="you@example.com"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition"
                />
                @error('email')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-4 pt-1">
                <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-green-500 hover:from-blue-700 hover:to-green-600 text-white rounded-xl py-2.5 font-semibold transition-all duration-200 hover:scale-[1.02] transform shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-slate-800 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Send Reset Link
                </button>

                <p class="text-center text-sm text-gray-500 dark:text-slate-400">
                    Remembered it?
                    <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold ml-1">
                        Back to Sign In
                    </a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
