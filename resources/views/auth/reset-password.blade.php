<x-guest-layout>
    <!-- Tambahkan di atas konten utama -->
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

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
