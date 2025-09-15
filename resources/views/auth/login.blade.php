<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <style>
    :root {
      --blue: #3e4d5f;
      --blue-700: #b7c5fa;
      --green: #5bbd5b;
      --red: #e26c6c;
      --panel: #e09999;
      --r: 0px;
    }
    body {
      background: var(--bg, #fff);
      color: var(--blue);
      font-family: 'Figtree', sans-serif;
    }
    input, select, textarea, .block, .rounded, .shadow-sm, .focus\:ring-indigo-500 {
      border: none !important;
      border-radius: var(--r) !important;
      box-shadow: none !important;
      outline: none !important;
    }
    .x-primary-button, button[type="submit"] {
      background: var(--green) !important;
      color: #fff !important;
      border: none !important;
      border-radius: var(--r) !important;
      font-weight: 700;
      padding: 10px 24px;
      transition: filter 0.2s;
    }
    .x-primary-button:hover, button[type="submit"]:hover {
      filter: brightness(0.95);
    }
    a {
      color: var(--blue);
      border-radius: var(--r) !important;
      text-decoration: underline;
      transition: color 0.2s;
    }
    a:hover {
      color: var(--green);
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    select,
    textarea {
      border: 2px solid var(--blue) !important;
      border-radius: var(--r) !important;
      box-shadow: none !important;
      outline: none !important;
    }

    input[type="checkbox"] {
      border: 2px solid var(--blue) !important;
      border-radius: var(--r) !important;
      box-shadow: none !important;
      outline: none !important;
      width: 18px;
      height: 18px;
    }
    </style>
</x-guest-layout>
