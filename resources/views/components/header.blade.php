<header class="topbar fixed-header">
  <div class="logo">
    <img src="{{ asset('img/logo.png') }}" alt="Logo"
         onerror="this.outerHTML='<strong>Logo.png</strong>';">
    <span>Perindustrian KUKM Kota Madiun</span>
  </div>

  <div class="auth">
    @if (Route::has('login'))
      @auth
        <a href="{{ url('/dashboard') }}" class="btn">Dashboard</a>
      @else
        <a href="{{ route('login') }}" class="btn btn-auth">Login</a>
      @endauth
    @endif
  </div>
</header>
<link rel="stylesheet" href="{{ asset('css/header.css') }}">
