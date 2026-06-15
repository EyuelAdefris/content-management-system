<x-guest-layout>
    <x-slot name="wrapperClass">min-h-screen flex items-center justify-center bg-gray-50 dark:bg-slate-900 py-12 px-4 sm:px-6 lg:px-8 transition-colors duration-300</x-slot>
    <x-slot name="cardClass">bg-white dark:bg-slate-800 rounded-2xl shadow-xl overflow-hidden max-w-4xl w-full flex flex-col md:flex-row animate-fade-in</x-slot>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in {
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
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
            <h2 class="text-3xl font-bold leading-tight">Welcome to CMS</h2>
            <p class="text-blue-100 text-lg">Sign in to manage your content</p>
        </div>

        <div class="relative z-10 text-xs text-blue-200/80">
            &copy; {{ date('Y') }} CMS Engine. All rights reserved.
        </div>
    </div>

    <!-- RIGHT PANEL: Form -->
    <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Sign In</h2>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1 mb-6">Enter your details to access your account</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Success Message (e.g. after password reset or registration) -->
        @if (session('success'))
            <div class="mb-5 flex items-center gap-2 px-4 py-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 text-sm rounded-xl">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-gray-700 dark:text-slate-300 font-medium" />
                <x-text-input id="email" class="block mt-1.5 w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between">
                    <x-input-label for="password" :value="__('Password')" class="text-gray-700 dark:text-slate-300 font-medium" />
                </div>
                <div class="relative mt-1.5">
                    <x-text-input id="password" class="block w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm pr-10" type="password" name="password" required autocomplete="current-password" />
                    <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:text-slate-400 dark:hover:text-slate-200 focus:outline-none cursor-pointer" aria-label="Toggle password visibility">
                        <!-- Closed Eye (Eye-Slash) Icon (default) -->
                        <svg id="eye-icon-closed" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                        <!-- Open Eye Icon (hidden by default) -->
                        <svg id="eye-icon-open" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between text-sm">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 dark:border-slate-600 dark:bg-slate-700 text-blue-600 shadow-sm focus:ring-blue-500" name="remember" />
                    <span class="ms-2 text-gray-600 dark:text-slate-400">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-blue-600 dark:text-blue-400 hover:underline font-medium" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <div class="space-y-4 pt-2">
                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-green-500 hover:from-blue-700 hover:to-green-600 text-white rounded-lg py-2.5 font-semibold transition-all duration-200 hover:scale-[1.02] transform shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-slate-800">
                    {{ __('Log in') }}
                </button>

                @if (Route::has('register'))
                    <p class="text-center text-sm text-gray-500 dark:text-slate-400">
                        {{ "Don't have an account?" }}
                        <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold ml-1">
                            {{ __('Register') }}
                        </a>
                    </p>
                @endif
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('toggle-password');
            const eyeOpen = document.getElementById('eye-icon-open');
            const eyeClosed = document.getElementById('eye-icon-closed');

            if (toggleButton && passwordInput) {
                toggleButton.addEventListener('click', function () {
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        eyeOpen.classList.remove('hidden');
                        eyeClosed.classList.add('hidden');
                    } else {
                        passwordInput.type = 'password';
                        eyeOpen.classList.add('hidden');
                        eyeClosed.classList.remove('hidden');
                    }
                });
            }
        });
    </script>
</x-guest-layout>
