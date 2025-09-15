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

    <div class="mb-4 text-sm text-gray-600">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
