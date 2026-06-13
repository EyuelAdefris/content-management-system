<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-indigo-600 to-indigo-900 py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl p-8 max-w-md w-full space-y-6">
            <!-- Branding -->
            <h1 class="text-2xl font-bold text-indigo-600 text-center">CMS Engine</h1>
            <h2 class="text-xl font-semibold text-gray-800 text-center">Welcome Back</h2>
            <p class="text-sm text-slate-500 text-center">Sign in to access your dashboard</p>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember" />
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex flex-col space-y-4 mt-4">
    @if (Route::has('password.request'))
        <a class="text-sm text-indigo-600 hover:underline" href="{{ route('password.request') }}">
            {{ __('Forgot your password?') }}
        </a>
    @endif
    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg py-2.5 font-medium">
        {{ __('Log in') }}
    </button>
    @if (Route::has('register'))
        <p class="text-center text-sm text-slate-500">
            {{ "Don't have an account?" }}
            <a href="{{ route('register') }}" class="text-indigo-600 hover:underline font-medium">
                {{ __('Register') }}
            </a>
        </p>
    @endif
</div>
            </form>
        </div>
    </div>
</x-guest-layout>

