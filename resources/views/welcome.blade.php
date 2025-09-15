<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Perindustrian KUKM Kota Madiun</title>

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,600,700&display=swap" rel="stylesheet" />

  <style>
    :root{
      --ink:#1f2937;
      --bg:#ffffff;
      --panel:#99c4e0;
      --blue:#000000;
      --blue-700:#b7c5fa;
      --green:#5bbd5b;
      --green-700:#006600;
      --red:#e26c6c;
      --red-700:#990000;
      --b1:0px;
      --b2:0px;
      --r:0px;
    }

    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;
      font-family:Figtree, system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
      color:var(--ink);
      background:var(--bg);
      padding-top:80px; /* supaya konten tidak ketiban header fixed */
    }

    /* Header */
    .topbar{
      background:#52c958;
      border:var(--b2) solid var(--blue);
      margin:0;
      padding:18px 22px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      width:100%;
      box-sizing:border-box;
    }
    .fixed-header{
      position:fixed;
      top:0;
      left:0;
      right:0;
      z-index:1000;
    }
    .logo img{max-height:48px;width:auto}

    .auth{display:flex;flex-direction:column;gap:8px;align-items:flex-end}

    .btn{
      display:inline-block;
      background:var(--blue);
      border:var(--b1) solid var(--blue-700);
      color:#000000;
      font-weight:700;
      text-decoration:none;
      padding:10px 22px;
      border-radius:var(--r);
      letter-spacing:.2px;
      transition:transform .2s ease, opacity .2s ease, background .2s ease;
    }
    .btn-auth{min-width:120px;font-size:16px;text-align:center}
    .btn-auth:hover{transform:translateY(-1px);opacity:.9}

    /* Container tengah */
    .wrap{max-width:980px;margin:34px auto 60px;padding:0 18px;}

    /* PANEL full layar */
    .panel{
      background:var(--panel);
      border:var(--b2) solid var(--blue);
      padding:38px 32px 52px;
      text-align:center;
      width:100%;
      margin:0;
      /* Animasi masuk panel (fade + slight up) */
      opacity:0;
      transform:translateY(8px);
      animation:fadeUp .6s ease-out forwards;
      animation-delay:.1s;
    }
    .panel .jumbo-link{
      background:#5bbd5b!important;
      color:#000000!important;
      border-radius:0!important;
      font-size:18px;
      font-weight:600;
      text-decoration:none;
      display:inline-block;
      padding:8px 16px;
      margin-top:20px;
      transition:background .2s ease, transform .2s ease, opacity .2s ease;
      /* Stagger terakhir */
      opacity:0; transform:translateY(8px);
      animation:fadeUp .6s ease-out forwards;
      animation-delay:.6s;
    }
    .panel .jumbo-link:hover{background:#4aa84a!important;color:#fff!important;transform:translateY(-1px)}

    /* Stats */
    .stats{display:flex;gap:12px;justify-content:center;flex-wrap:nowrap;
      /* Stagger container */
      opacity:0; transform:translateY(8px);
      animation:fadeUp .6s ease-out forwards;
      animation-delay:.4s;
    }
    .stat{
      width:140px;
      padding:10px 8px 12px;
      background:#fff;
      position:relative;
      border-radius:4px;
      box-shadow:0 1px 0 rgba(0,0,0,.05);
    }
    .stat::before{
      content:"";
      position:absolute;
      inset:0 0 auto 0;
      height:6px;
      background:var(--green-700);
      border-top-left-radius:4px;
      border-top-right-radius:4px;
    }
    .stat--green::before{background:var(--green)}
    .stat--red::before{background:var(--red)}

    .stat .num{font-size:20px;font-weight:700;margin:6px 0 2px;}
    .stat .label{margin-top:4px;font-size:12px;font-weight:600;}

    /* Footer */
    .foot{
      text-align:center;
      color:#000;
      font-size:12px;
      margin:0;
      padding:10px 22px;
      background:#52c958;
      width:100%;
      border-top:3px solid #3e4d5f;
      /* Fade footer ringan */
      opacity:0; transform:translateY(6px);
      animation:fadeUp .6s ease-out forwards;
      animation-delay:.7s;
    }
    .footer-content{display:flex;flex-wrap:wrap;gap:32px;justify-content:center;align-items:flex-start;margin-bottom:10px}
    .contact-item{min-width:180px;font-size:13px;text-align:left;margin:6px 0}

    .logo-footer{display:flex;align-items:center;justify-content:center;gap:8px;margin-bottom:14px;font-weight:700;font-size:16px;color:var(--blue)}
    .logo-footer svg{flex-shrink:0}

    /* Panel heading (judul & subjudul) + animasi */
    .panel-title{
      margin:0 0 4px 0;
      font-size:28px;
      font-weight:700;
      letter-spacing:.2px;
      color:#0b3b0b;
      opacity:0; transform:translateY(8px);
      animation:fadeUp .6s ease-out forwards;
      animation-delay:.2s;
    }
    .panel-subtitle{
      margin:0 0 16px 0;
      font-size:18px;
      font-weight:600;
      color:#0b3b0b;
      opacity:.8;
      /* ikut fade */
      opacity:0; transform:translateY(8px);
      animation:fadeUp .6s ease-out forwards;
      animation-delay:.3s;
    }
    @media (max-width:480px){
      .panel .jumbo-link{font-size:14px}
      .stat{width:100%}
      .auth{align-items:flex-start}
      .panel-title{font-size:22px}
      .panel-subtitle{font-size:12px}
    }

    /* ==== Keyframes & accessibility ==== */
    @keyframes fadeUp{
      from{opacity:0; transform:translateY(8px)}
      to{opacity:1; transform:translateY(0)}
    }

    /* Hormati preferensi reduced motion */
    @media (prefers-reduced-motion: reduce){
      *{
        animation-duration:1ms !important;
        animation-iteration-count:1 !important;
        transition-duration:1ms !important;
      }
      .panel,.panel-title,.panel-subtitle,.stats,.panel .jumbo-link,.foot{
        opacity:1 !important; transform:none !important;
      }
    }
  </style>
</head>
<body>
  {{-- Panggil component header --}}
  <x-header />

  <!-- PANEL full-bleed -->
  <section class="panel">
    <h1 class="panel-title">Data Pelaku Industri</h1>
    <p class="panel-subtitle">2024 – {{ now()->year }}</p>

    <div class="stats">
      <div class="stat stat--green">
        <div class="num">{{ $jumlahPerusahaan }}</div>
        <div class="label">Jumlah Perusahaan</div>
      </div>
      <div class="stat stat--red">
        <div class="num">{{ $jumlahPelaku }}</div>
        <div class="label">Jumlah Pelaku Industri</div>
      </div>
      <div class="stat stat--blue">
        <div class="num">{{ $jumlahUsaha }}</div>
        <div class="label">Jumlah Usaha</div>
      </div>
    </div>

    <a href="{{ route('dasbor.kbli') }}" class="jumbo-link">Lihat Data Pelaku Industri</a>
  </section>

  <!-- Konten lain -->
  <main class="wrap">
    <!-- konten tambahan di sini -->
  </main>

  <!-- Footer -->
  <footer class="foot">
    <div class="logo-footer">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="40" fill="currentColor" viewBox="0 0 24 24">
        <path d="M19.14,12.94a7.48,7.48,0,0,0,.05-1,7.48,7.48,0,0,0-.05-1l2.11-1.65a.5.5,0,0,0,.12-.64l-2-3.46a.5.5,0,0,0-.61-.22l-2.49,1a7.28,7.28,0,0,0-1.73-1L14.5,2.5a.5.5,0,0,0-.5-.5H10a.5.5,0,0,0-.5.5L9.47,5A7.28,7.28,0,0,0,7.74,6L5.25,5a.5.5,0,0,0-.61.22l-2,3.46a.5.5,0,0,0,.12.64L4.86,10a7.48,7.48,0,0,0,0,2l-2.1,1.65a.5.5,0,0,0-.12.64l2,3.46a.5.5,0,0,0,.61.22l2.49-1a7.28,7.28,0,0,0,1.73,1L9.5,21.5a.5.5,0,0,0,.5.5h4a.5.5,0,0,0,.5-.5l.03-2.52a7.28,7.28,0,0,0,1.73-1l2.49,1a.5.5,0,0,0,.61-.22l2-3.46a.5.5,0,0,0-.12-.64ZM12,15.5A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z"/>
      </svg>
      <span>Perindustrian KUKM Kota Madiun</span>
    </div>
    <div class="contact-item">
      <strong>Contact:</strong>
      <a href="https://wa.me/628123456789" target="_blank" rel="noopener noreferrer">
        +62 812-3456-789
      </a>
    </div>
    <div class="contact-item">
      <strong>Instagram:</strong>
      <a href="https://www.instagram.com/perindustrian.kotamadiun" target="_blank" rel="noopener noreferrer">
        @perindustrian.kotamadiun
      </a>
    </div>
    <div class="contact-item">
      <strong>Alamat:</strong> Jl. Bolodewo No.8, Kartoharjo, Kec. Kartoharjo, Kota Madiun, Jawa Timur 63117
    </div>
    <div class="contact-item">
      <strong>Tentang Kami:</strong> Kami adalah pusat data & informasi pelaku industri KUKM Kota Madiun.
    </div>
    <div style="margin-top:10px;">
      Copyright © 2025 TrapCode Team. All rights reserved.
    </div>
  </footer>

  <!-- (Opsional) Tambahkan kelas 'animate' ke body setelah DOM siap untuk memicu CSS yang perlu JS -->
  <script>
    // Tidak wajib karena kita pakai CSS animation dengan delay;
    // tapi kalau kamu mau memicu via kelas, bisa pakai ini.
    document.addEventListener('DOMContentLoaded', function(){
      document.body.classList.add('animate');
    });
  </script>
</body>
</html>
