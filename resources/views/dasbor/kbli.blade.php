<x-app-layout>
  {{-- ====== STYLE + ANIMASI ====== --}}
  <style>
    :root{
      --ink:#1f2937; --bg:#ffffff; --blue:#3e4d5f;
      --brand:#0A6FB5; --brand-weak: rgba(10,111,181,.08);
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{background:var(--bg); color:var(--ink); font-family:'Figtree',sans-serif;}
    .foot{ text-align:center; color:#000; font-size:12px; margin:0; padding:10px 22px; background:#f3f4f6; width:100%; box-sizing:border-box; border-top:3px solid #3e4d5f; position:static; z-index:99;}

    /* Kartu skala */
    .skala-card{width:110px; height:110px; background:#fff; border-radius:0; box-shadow:0 2px 8px rgba(0,0,0,.04); display:flex; flex-direction:column; justify-content:center; align-items:center; text-align:center; margin:0; padding:10px 4px;}
    .skala-card .label{font-size:.85rem; font-weight:700; color:#3e4d5f; margin-bottom:6px;}
    .skala-card .val{font-size:1.3rem; font-weight:700; color:#5bbd5b;}
    @media (max-width:640px){
      .skala-card{width:80px; height:80px; font-size:.75rem; padding:6px 2px;}
      .skala-card .val{font-size:1rem;}
    }

    /* ====== ANIMASI UTIL ====== */
    .fade-up{opacity:0; transform:translateY(8px); animation:fadeUp .55s ease-out forwards; animation-delay:var(--d,0s); will-change:opacity,transform;}
    .fade-up-soft{opacity:0; transform:translateY(6px); animation:fadeUp .5s ease-out forwards; animation-delay:var(--d,0s); will-change:opacity,transform;}
    @keyframes fadeUp{from{opacity:0; transform:translateY(8px)} to{opacity:1; transform:translateY(0)}}
    /* Stagger container */
    .stagger > *{opacity:0; transform:translateY(6px); animation:fadeUp .5s ease-out forwards;}
    .stagger > *:nth-child(1){animation-delay:.06s}
    .stagger > *:nth-child(2){animation-delay:.12s}
    .stagger > *:nth-child(3){animation-delay:.18s}
    .stagger > *:nth-child(4){animation-delay:.24s}
    .stagger > *:nth-child(5){animation-delay:.30s}
    .stagger > *:nth-child(6){animation-delay:.36s}

    /* Baris tabel fade saat muncul */
    .row-fade{opacity:0; transform:translateY(6px); transition:opacity .45s ease, transform .45s ease;}
    .row-fade.in{opacity:1; transform:none;}
    /* Stagger awal 5 baris pertama */
    tbody .row-fade:nth-child(1){animation:fadeUp .45s ease-out .08s forwards}
    tbody .row-fade:nth-child(2){animation:fadeUp .45s ease-out .12s forwards}
    tbody .row-fade:nth-child(3){animation:fadeUp .45s ease-out .16s forwards}
    tbody .row-fade:nth-child(4){animation:fadeUp .45s ease-out .20s forwards}
    tbody .row-fade:nth-child(5){animation:fadeUp .45s ease-out .24s forwards}

    /* Reduced motion */
    @media (prefers-reduced-motion: reduce){
      *{animation-duration:1ms !important; animation-iteration-count:1 !important; transition-duration:1ms !important;}
      .fade-up,.fade-up-soft,.stagger > *, .row-fade, .row-fade.in { opacity:1 !important; transform:none !important; }
    }
  </style>

  {{-- ====== HEADER ====== --}}
  <x-slot name="header">
    <div class="flex items-center gap-3 fade-up" style="--d:.05s">
      <h2 class="font-semibold text-xl text-[color:var(--brand)] leading-tight">
        {{ __('Data Pelaku Industri') }}
      </h2>
    </div>
  </x-slot>

  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

      {{-- ====== PANEL RINGKASAN + FILTER KBLI ====== --}}
      <section class="bg-white/90 backdrop-blur-sm border rounded-2xl shadow-sm fade-up" style="--d:.08s">
        <header class="px-5 py-4 border-b rounded-t-2xl bg-[color:var(--brand-weak)] border-[color:var(--brand)]/20 fade-up-soft" style="--d:.1s">
          <h3 class="text-2xl md:text-3xl font-semibold text-gray-800">
            @if($selected)
              {{ $selected }}
            @elseif($prefix)
              Kelompok KBLI {{ $prefix }}
            @else
              Semua Industri (KBLI)
            @endif
          </h3>
        </header>

        {{-- FILTER KBLI (prefix & uraian) --}}
        <div class="p-5 fade-up-soft" style="--d:.14s">
          <form method="GET" action="{{ route('dasbor.kbli') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 md:items-end">
            {{-- Prefix (3 digit) - opsional --}}
            <div class="md:col-span-3">
              <label for="prefix" class="block text-sm font-medium text-gray-700 mb-1">Turunan KBLI (opsional)</label>
              <select id="prefix" name="prefix"
                      class="w-full rounded-lg border-gray-300 focus:outline-none focus:ring-4 focus:ring-[color:var(--brand-weak)] focus:border-[color:var(--brand)]"
                      onchange="this.form.submit()">
                <option value="">— Semua KBLI —</option>
                @foreach($prefixes as $p)
                  <option value="{{ $p }}" @selected($p === $prefix)>{{ $p }}</option>
                @endforeach
              </select>
              <p class="mt-1 text-xs text-gray-500">
                Kamu bisa memilih <span class="font-semibold">Uraian KBLI</span> langsung tanpa memilih turunan.
              </p>
            </div>

            {{-- Uraian KBLI - sekarang TIDAK tergantung prefix --}}
            <div class="md:col-span-6">
              <label for="uraian_kbli" class="block text-sm font-medium text-gray-700 mb-1">Pilih Uraian KBLI</label>
              <select id="uraian_kbli" name="uraian_kbli"
                      class="w-full rounded-lg border-gray-300 focus:outline-none focus:ring-4 focus:ring-[color:var(--brand-weak)] focus:border-[color:var(--brand)]"
                      onchange="this.form.submit()">
                <option value="">— Semua Uraian —</option>
                @foreach($options as $opt)
                  <option value="{{ $opt }}" @selected($opt === $selected)>{{ $opt }}</option>
                @endforeach
              </select>
              <p class="mt-1 text-xs text-gray-500">
                Memilih uraian akan memfilter data meski turunan KBLI tidak dipilih.
              </p>
            </div>

            {{-- Tombol reset --}}
            <div class="md:col-span-3 flex gap-2">
              <a href="{{ route('dasbor.kbli') }}"
                 class="inline-flex w-auto items-center justify-center px-3 py-2 text-sm rounded bg-red-500 text-white hover:bg-red-600 active:scale-95 active:opacity-80 transition">
                Reset Filter
              </a>
            </div>
          </form>
        </div>

        {{-- SKALA USAHA --}}
        <div class="px-5 pb-5 fade-up-soft" style="--d:.18s">
          <div class="overflow-x-auto">
            <div class="text-2xl font-semibold text-gray-800 text-center mb-4">Skala Usaha</div>
            <div class="flex gap-4 justify-center flex-wrap stagger">
              @php
                $kartu = [
                  ['label'=>'Skala Mikro','val'=>$skalaCounts['Mikro']??0],
                  ['label'=>'Skala Kecil','val'=>$skalaCounts['Kecil']??0],
                  ['label'=>'Skala Menengah','val'=>$skalaCounts['Menengah']??0],
                  ['label'=>'Skala Besar','val'=>$skalaCounts['Besar']??0],
                ];
              @endphp
              @foreach($kartu as $item)
                <div class="skala-card">
                  <span class="label">{{ $item['label'] }}</span>
                  <span class="val">{{ number_format($item['val'], 0, ',', '.') }}</span>
                </div>
              @endforeach
            </div>
          </div>
        </div>

        {{-- PANEL TK & INVESTASI --}}
        <div class="px-4 pb-5 fade-up-soft" style="--d:.22s">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Tenaga Kerja --}}
            <div class="aspect-square rounded-xl border border-[color:var(--brand)]/30 bg-white p-6 flex flex-col items-center justify-center text-center shadow fade-up-soft" style="--d:.24s">
              <div class="p-4 rounded-2xl shadow border border-[#45A243]/30 bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-16 h-16" fill="#45A243">
                  <path d="M19.43 12.98c.04-.32.07-.65.07-.98s-.03-.66-.07-.98l2.11-1.65a.5.5 0 0 0 .12-.64l-2-3.46a.5.5 0 0 0-.61-.22l-2.49 1a7.14 7.14 0 0 0-1.69-.98l-.38-2.65A.5.5 0 0 0 14 2h-4a.5.5 0 0 0-.5.42l-.38 2.65c-.63.24-1.2.56-1.69.98l-2.49-1a.5.5 0 0 0-.61.22l-2 3.46a.5.5 0 0 0 .12.64l2.11 1.65c-.04.32-.07.65-.07.98s.03.66.07.98L2.46 14.63a.5.5 0 0 0-.12.64l2 3.46c.14.24.42.34.68.22l2.49-1c.49.42 1.06.74 1.69.98l.38 2.65c.04.26.25.42.5.42h4c.25 0 .46-.16.5-.42l.38-2.65c.63-.24 1.2-.56 1.69-.98l2.49 1c.26.12.54.02.68-.22l2-3.46a.5.5 0 0 0-.12-.64l-2.11-1.65ZM12 15.5c-1.93 0-3.5-1.57-3.5-3.5S10.07 8.5 12 8.5s3.5 1.57 3.5 3.5-1.57 3.5-3.5 3.5Z"/>
                </svg>
              </div>
              <div class="mt-3 text-sm font-semibold text-gray-700">Jumlah Tenaga Kerja</div>
              <div class="mt-1 text-xl font-bold text-[color:var(--brand)]">{{ number_format($agg->total_tk ?? 0, 0, ',', '.') }}</div>
            </div>

            {{-- Investasi --}}
            <div class="aspect-square rounded-xl border border-[color:var(--brand)]/30 bg-white p-6 flex flex-col items-center justify-center text-center shadow fade-up-soft" style="--d:.28s">
              <div class="p-4 rounded-2xl shadow border border-[#F05128]/30 bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     class="w-16 h-16" fill="none" stroke="#F05128" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M3 17l6-6 4 4 8-8"/>
                  <path d="M21 21H3V3"/>
                </svg>
              </div>
              <div class="mt-3 text-sm font-semibold text-gray-700">Jumlah Investasi</div>
              <div class="mt-1 text-xl font-bold text-[color:var(--brand)]">Rp {{ number_format($agg->total_investasi ?? 0, 0, ',', '.') }},-</div>
            </div>
          </div>
        </div>
      </section>

      {{-- ====== KARTU TABEL + PENCARIAN ====== --}}
      <section class="bg-white/90 backdrop-blur-sm border rounded-2xl shadow-sm fade-up" style="--d:.12s">
        <div class="px-5 pt-5">
          @if($selected)
            <div class="mb-3 inline-flex items-center gap-2 rounded-full bg-[color:var(--brand-weak)] text-[color:var(--brand)] px-3 py-1 text-sm fade-up-soft" style="--d:.14s">
              <span class="font-medium">Filter:</span> "{{ $selected }}"
            </div>
          @elseif($prefix)
            <div class="mb-3 inline-flex items-center gap-2 rounded-full bg-[color:var(--brand-weak)] text-[color:var(--brand)] px-3 py-1 text-sm fade-up-soft" style="--d:.14s">
              <span class="font-medium">KBLI:</span> {{ $prefix }}
            </div>
          @endif
        </div>

        {{-- FORM CARI --}}
        <div class="px-5 pb-4 fade-up-soft" style="--d:.16s">
          <form method="GET" action="{{ route('dasbor.kbli') }}" class="flex gap-2">
            @if(request()->has('prefix'))
              <input type="hidden" name="prefix" value="{{ request('prefix') }}">
            @endif
            @if(request()->has('uraian_kbli'))
              <input type="hidden" name="uraian_kbli" value="{{ request('uraian_kbli') }}">
            @endif

            <input type="text" name="search" value="{{ request('search') }}"
              placeholder="Cari nama, alamat, uraian KBLI..."
              class="w-full md:w-1/3 rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-4 focus:ring-[color:var(--brand-weak)] focus:border-[color:var(--brand)]" />
            <button type="submit"
              class="px-4 py-2 bg-[color:var(--brand)] text-black rounded-lg hover:opacity-90 shadow-sm">
              Cari
            </button>
          </form>
        </div>

        {{-- TABEL --}}
        <div class="px-5 pb-5 overflow-x-auto fade-up-soft" style="--d:.2s">
          <table class="min-w-full text-sm border border-gray-300 border-collapse">
            <thead class="bg-[color:var(--brand-weak)]">
              <tr class="text-left text-[color:var(--brand)]">
                <th class="px-4 py-3 font-semibold border border-gray-300">Nama Perusahaan</th>
                <th class="px-4 py-3 font-semibold border border-gray-300">Alamat</th>
                <th class="px-4 py-3 font-semibold border border-gray-300">Uraian KBLI</th>
                <th class="px-4 py-3 font-semibold border border-gray-300">Nomor Telp</th>
                <th class="px-4 py-3 font-semibold border border-gray-300">Email</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              @forelse ($rows as $row)
                <tr class="hover:bg-gray-50 row-fade">
                  <td class="px-4 py-3 text-gray-900 border border-gray-300">{{ $row->nama_perusahaan ?? '—' }}</td>
                  <td class="px-4 py-3 text-gray-700 whitespace-normal border border-gray-300">{{ $row->alamat_usaha ?? '—' }}</td>
                  <td class="px-4 py-3 text-gray-700 border border-gray-300">{{ $row->uraian_kbli ?? '—' }}</td>
                  <td class="px-4 py-3 text-gray-700 border border-gray-300">{{ $row->nomor_telp ?? '—' }}</td>
                  <td class="px-4 py-3 text-gray-700 border border-gray-300">{{ $row->email ?? '—' }}</td>
                </tr>
              @empty
                <tr class="row-fade in">
                  <td colspan="5" class="px-4 py-6 text-center text-gray-500 border border-gray-300">Tidak ada data.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </section>

      {{-- ====== PAGINATION (TERPISAH) ====== --}}
      <div class="fade-up-soft" style="--d:.22s">
        @include('components.pagination', ['paginator' => $rows])
      </div>

    </div>
  </div>

  <div class="foot fade-up-soft" style="--d:.24s">Copyright © 2025 TrapCode Team. All rights reserved.</div>

  {{-- Fade-in baris saat masuk viewport --}}
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      if (reduce) return;

      const rows = document.querySelectorAll('tbody .row-fade');
      const obs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
          if (e.isIntersecting) {
            e.target.classList.add('in');
            obs.unobserve(e.target);
          }
        });
      }, {threshold: 0.06, rootMargin: '0px 0px -10% 0px'});

      rows.forEach(r => obs.observe(r));
    });
  </script>
</x-app-layout>
