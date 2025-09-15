<style>
:root{
  --nav-bg: #52c958;
  --ink: #0b1b0b;
  --ink-weak:#2a3a2a;
  --hover:#d6e5c3;
  --accent:#2f6b2f;
  --b:2px;
  --r:10px;
}

/* ===== NAV WRAPPER (fixed) ===== */
nav.nav {
  position: fixed; top:0; left:0; right:0; z-index:1000;
  background: var(--nav-bg);
  margin:0 !important; padding: 10px 0 !important;
  border-bottom: var(--b) solid rgba(0,0,0,.08);
  /* Fade-in turun saat halaman terbuka */
  opacity:0; transform: translateY(-8px);
  animation: navDrop .5s ease-out forwards;
}
@keyframes navDrop{
  from{opacity:0; transform:translateY(-8px)}
  to{opacity:1; transform:translateY(0)}
}

/* Tambah jarak konten agar tidak ketimpa */
body { padding-top: 76px; }

/* Inner container (opsional dibatasi) */
.nav-inner{
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 18px;
  display:flex; align-items:center; justify-content:space-between; gap:12px;
}

/* Logo */
nav .logo{
  opacity:0; transform: translateY(6px);
  animation: fadeUp .45s ease-out forwards; animation-delay:.08s;
}
nav .logo img{
  width:48px; height:48px; object-fit:contain; display:block;
}

/* ===== DESKTOP LINKS ===== */
.nav-left, .nav-right{
  display:flex; align-items:center; gap:10px;
}

/* Stagger link desktop */
.nav-group{
  display:none;
}
@media (min-width: 640px){ /* >= sm */
  .nav-group{ display:flex; align-items:center; gap:6px; }
  .nav-group > *{ opacity:0; transform:translateY(6px); animation: fadeUp .45s ease-out forwards; }
  .nav-group > *:nth-child(1){ animation-delay:.10s }
  .nav-group > *:nth-child(2){ animation-delay:.14s }
  .nav-group > *:nth-child(3){ animation-delay:.18s }
  .nav-group > *:nth-child(4){ animation-delay:.22s }
  .nav-group > *:nth-child(5){ animation-delay:.26s }
  .nav-group > *:nth-child(6){ animation-delay:.30s }
}

/* link generik (x-nav-link akan merge class ini) */
.nav-link{
  display:inline-flex; align-items:center; gap:8px;
  padding:10px 14px; border-radius: var(--r);
  font-weight:700; text-decoration:none;
  color: var(--ink) !important; background: transparent !important;
  border:none !important;
  transition: background .2s, color .2s, transform .12s, box-shadow .2s;
}
.nav-link:hover{ background: var(--hover) !important; color: var(--ink) !important; transform: translateY(-1px); }
.nav-link.active{ color: var(--accent) !important; box-shadow: inset 0 -3px 0 var(--accent); border-radius: var(--r) var(--r) 0 0; }

/* Tombol profil (dropdown trigger) */
.btn-ghost{
  display:inline-flex; align-items:center; gap:8px;
  padding:8px 12px; border-radius: var(--r);
  background:#fff; color:#374151; border:1px solid rgba(0,0,0,.08);
  font-weight:600;
  transition: background .2s, transform .12s, box-shadow .2s;
  opacity:0; transform: translateY(6px);
  animation: fadeUp .45s ease-out forwards; animation-delay:.14s;
}
.btn-ghost:hover{ background:#f5f7f2; transform: translateY(-1px); }

/* ===== HAMBURGER (mobile only) ===== */
.hamburger{
  display:flex; align-items:center;
  opacity:0; transform: translateY(6px);
  animation: fadeUp .45s ease-out forwards; animation-delay:.16s;
}
@media (min-width: 640px){ .hamburger{ display:none; } }

.hamburger button{
  padding:8px; border-radius:10px; border:none; background:transparent;
  color:#0f172a;
  transition: background .2s;
}
.hamburger button:hover{ background: rgba(255,255,255,.4); }

/* ===== MOBILE PANEL ===== */
.mobile-panel{
  display:none;
  background:#ffffff;
  border-top: var(--b) solid rgba(0,0,0,.06);
  box-shadow: 0 12px 30px rgba(0,0,0,.12);
  opacity:0; transform: translateY(-6px);
  pointer-events:none;
}
.mobile-panel.open{
  display:block;
  opacity:1; transform:none;
  pointer-events:auto;
  animation: drop .3s ease-out forwards;
}
@keyframes drop{
  from{opacity:0; transform:translateY(-6px)}
  to{opacity:1; transform:translateY(0)}
}

.mobile-inner{
  padding:10px 14px;
  display:flex; flex-direction:column; gap:6px;
}
.mobile-link{
  display:flex; align-items:center; justify-content:space-between;
  padding:10px 12px; border-radius:10px;
  color:#0f172a; text-decoration:none; font-weight:700;
  border:1px solid rgba(0,0,0,.06);
  transition: background .2s, border-color .2s, transform .12s;
  /* item muncul berurutan ketika panel dibuka */
  opacity:0; transform: translateY(6px);
}
.mobile-panel.open .mobile-inner > *{ animation: fadeUp .32s ease-out forwards; }
.mobile-panel.open .mobile-inner > *:nth-child(1){ animation-delay:.06s }
.mobile-panel.open .mobile-inner > *:nth-child(2){ animation-delay:.10s }
.mobile-panel.open .mobile-inner > *:nth-child(3){ animation-delay:.14s }
.mobile-panel.open .mobile-inner > *:nth-child(4){ animation-delay:.18s }
.mobile-panel.open .mobile-inner > *:nth-child(5){ animation-delay:.22s }
.mobile-panel.open .mobile-inner > *:nth-child(6){ animation-delay:.26s }
.mobile-panel.open .mobile-inner > *:nth-child(7){ animation-delay:.30s }

.mobile-link:hover{ background:#f7faf5; transform: translateY(-1px); }
.mobile-link.active{ color: var(--accent); border-color: var(--accent); }

/* divider tipis mobile */
.mobile-sep{ height:1px; background:rgba(0,0,0,.06); margin:8px 0; }

/* Keyframes shared */
@keyframes fadeUp{ from{opacity:0; transform:translateY(6px)} to{opacity:1; transform:translateY(0)} }

/* Aksesibilitas: hormati prefers-reduced-motion */
@media (prefers-reduced-motion: reduce){
  nav.nav, .logo, .nav-group > *, .btn-ghost, .hamburger,
  .mobile-panel, .mobile-link, .mobile-panel.open, .mobile-panel.open .mobile-inner > * {
    animation-duration:1ms !important; animation-iteration-count:1 !important;
    transition-duration:1ms !important;
    opacity:1 !important; transform:none !important;
  }
}
</style>

<nav x-data="{ open:false }" class="nav">
  <div class="nav-inner">
    <!-- KIRI: Logo + link kiri -->
    <div class="nav-left">
      <a href="{{ url('/') }}" class="logo" aria-label="Beranda">
        <img src="{{ asset('img/logo.png') }}" alt="Logo">
      </a>

      <!-- Desktop: kiri -->
      <div class="nav-group">
        @auth
          <x-nav-link class="nav-link" :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
          </x-nav-link>

          <x-nav-link class="nav-link" :href="route('pelaku-industri.index')" :active="request()->routeIs('pelaku-industri.*')">
            {{ __('Kelola Data Pelaku Industri') }}
          </x-nav-link>

          <x-nav-link class="nav-link" :href="route('dasbor.kbli')" :active="request()->routeIs('dasbor.kbli')">
            {{ __('Data Pelaku Industri-Public') }}
          </x-nav-link>

          @if (Auth::user()->is_admin)
            <x-nav-link class="nav-link" :href="route('admin.users.pending')" :active="request()->routeIs('admin.users.pending')">
              {{ __('Persetujuan User') }}
            </x-nav-link>
          @endif
        @endauth

        @guest
          @if (Route::has('dasbor.kbli'))
            <x-nav-link class="nav-link" :href="route('dasbor.kbli')" :active="request()->routeIs('dasbor.kbli')">
              {{ __('KBLI') }}
            </x-nav-link>
          @endif
        @endguest
      </div>
    </div>

    <!-- KANAN: Auth -->
    <div class="nav-right">
      @auth
        <x-dropdown align="right" width="48">
          <x-slot name="trigger">
            <button class="btn-ghost" aria-haspopup="menu" :aria-expanded="open ? 'true' : 'false'">
              <div>{{ Auth::user()->name }}</div>
              <svg width="16" height="16" viewBox="0 0 20 20" class="opacity-70" aria-hidden="true">
                <path fill="currentColor" d="M5.3 7.3a1 1 0 011.4 0L10 10.6l3.3-3.3a1 1 0 111.4 1.4l-4 4a1 1 0 01-1.4 0l-4-4a1 1 0 010-1.4z"/>
              </svg>
            </button>
          </x-slot>
          <x-slot name="content">
            <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <x-dropdown-link :href="route('logout')"
                onclick="event.preventDefault(); this.closest('form').submit();">
                {{ __('Log Out') }}
              </x-dropdown-link>
            </form>
          </x-slot>
        </x-dropdown>
      @endauth

      @guest
        @if (Route::has('login'))
          <x-nav-link class="nav-link" :href="route('login')" :active="request()->routeIs('login')">
            {{ __('Login') }}
          </x-nav-link>
        @endif
      @endguest

      <!-- Hamburger (mobile) -->
      <div class="hamburger">
        <button @click="open = !open" :aria-expanded="open.toString()" aria-controls="mobileNav" aria-label="Buka menu">
          <svg class="w-7 h-7" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16" />
            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- MOBILE PANEL -->
  <div id="mobileNav" class="mobile-panel" :class="open ? 'open' : ''" x-cloak>
    <div class="mobile-inner">
      @auth
        <a class="mobile-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
        <a class="mobile-link {{ request()->routeIs('pelaku-industri.*') ? 'active' : '' }}" href="{{ route('pelaku-industri.index') }}">Kelola Data Pelaku Industri</a>
        <a class="mobile-link {{ request()->routeIs('dasbor.kbli') ? 'active' : '' }}" href="{{ route('dasbor.kbli') }}">KBLI</a>
        @if (Auth::user()->is_admin)
          <a class="mobile-link {{ request()->routeIs('admin.users.pending') ? 'active' : '' }}" href="{{ route('admin.users.pending') }}">Persetujuan User</a>
        @endif

        <div class="mobile-sep"></div>
        <a class="mobile-link" href="{{ route('profile.edit') }}">Profile</a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="mobile-link">Log Out</button>
        </form>
      @endauth

      @guest
        @if (Route::has('dasbor.kbli'))
          <a class="mobile-link {{ request()->routeIs('dasbor.kbli') ? 'active' : '' }}" href="{{ route('dasbor.kbli') }}">KBLI</a>
        @endif
        @if (Route::has('login'))
          <a class="mobile-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Login</a>
        @endif
      @endguest
    </div>
  </div>
</nav>
