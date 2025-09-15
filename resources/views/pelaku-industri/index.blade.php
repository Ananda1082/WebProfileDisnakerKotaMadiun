{{-- resources/views/pelaku-industri/index.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <title>Kelola Data Pelaku Industri</title>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight fade-up" style="--d: .05s">
      {{ __('Kelola Data Pelaku Industri') }}
    </h2>
  </x-slot>

  {{-- AlpineJS untuk interaksi ringan --}}
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <style>
    /* ====== ANIMASI ====== */
    .fade-up{
      opacity:0;
      transform:translateY(8px);
      animation:fadeUp .55s ease-out forwards;
      animation-delay:var(--d, 0s);
      will-change:opacity, transform;
    }
    .fade-up-soft{
      opacity:0;
      transform:translateY(6px);
      animation:fadeUp .5s ease-out forwards;
      animation-delay:var(--d, 0s);
      will-change:opacity, transform;
    }
    @keyframes fadeUp{
      from{opacity:0; transform:translateY(8px)}
      to{opacity:1; transform:translateY(0)}
    }

    /* Fade item beruntun (stagger) menggunakan nth-child */
    .stagger > *{opacity:0; transform:translateY(6px); animation:fadeUp .5s ease-out forwards;}
    .stagger > *:nth-child(1){animation-delay:.08s}
    .stagger > *:nth-child(2){animation-delay:.14s}
    .stagger > *:nth-child(3){animation-delay:.2s}
    .stagger > *:nth-child(4){animation-delay:.26s}
    .stagger > *:nth-child(5){animation-delay:.32s}
    .stagger > *:nth-child(6){animation-delay:.38s}

    /* Baris tabel fade saat muncul */
    .row-fade{opacity:0; transform:translateY(6px); transition:opacity .45s ease, transform .45s ease;}
    .row-fade.in{opacity:1; transform:none;}
    /* Sedikit stagger per baris (awal render) */
    tbody .row-fade:nth-child(1){animation:fadeUp .45s ease-out .08s forwards}
    tbody .row-fade:nth-child(2){animation:fadeUp .45s ease-out .12s forwards}
    tbody .row-fade:nth-child(3){animation:fadeUp .45s ease-out .16s forwards}
    tbody .row-fade:nth-child(4){animation:fadeUp .45s ease-out .20s forwards}
    tbody .row-fade:nth-child(5){animation:fadeUp .45s ease-out .24s forwards}
    /* dst tidak perlu—IntersectionObserver akan meneruskan efek saat discroll */

    /* Hormati preferensi reduced motion */
    @media (prefers-reduced-motion: reduce){
      *{animation-duration:1ms !important; animation-iteration-count:1 !important; transition-duration:1ms !important;}
      .fade-up,.fade-up-soft,.stagger > *, .row-fade, .row-fade.in { opacity:1 !important; transform:none !important; }
    }

    /* ====== TABEL RESPONSIF TANPA MENGHILANGKAN KOLOM ====== */
    .table-wrap {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      scrollbar-width: thin;
    }
    .table-sticky thead th {
      position: sticky; top: 0;
      background: #f8fafc; /* bg-gray-50 */
      z-index: 5;
    }
    .sticky-col-left {
      position: sticky; left: 0;
      background: #ffffff;
      z-index: 6; /* di atas sel biasa */
      box-shadow: 2px 0 0 rgba(0,0,0,0.04); /* garis tipis di kanan */
    }
    .sticky-col-right {
      position: sticky; right: 0;
      background: #ffffff;
      z-index: 6;
      box-shadow: -2px 0 0 rgba(0,0,0,0.04); /* garis tipis di kiri */
    }
    /* Kompakkan sel tabel di mobile */
    @media (max-width: 640px){
      .table-sticky th, .table-sticky td { padding: .4rem .5rem; font-size: .8rem; }
    }
  </style>

  <div class="py-6">
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">

      {{-- Notifikasi --}}
      @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded fade-up-soft" style="--d:.06s">{{ session('success') }}</div>
      @endif
      @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded fade-up-soft" style="--d:.06s">
          <ul class="list-disc list-inside">
            @foreach ($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 sm:p-6 fade-up" style="--d:.08s">

        {{-- Pencarian + Aksi (adaptif) --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4 stagger">
          {{-- Search global --}}
          <form method="GET" class="w-full md:w-auto flex flex-col sm:flex-row gap-2">
            <input type="text" name="q" value="{{ $q }}" placeholder="Cari apa saja (NIB/Nama/KBLI/dll)"
                   class="border rounded px-3 py-2 w-full sm:w-72">
            <div class="flex items-center gap-2">
              <button class="px-4 py-2 bg-gray-800 text-white rounded w-full sm:w-auto">Cari</button>
            </div>

            {{-- pertahankan semua filter selain q & page --}}
            @foreach(request()->except(['q','page']) as $k=>$v)
              @if(is_array($v))
                @foreach($v as $vv)
                  <input type="hidden" name="{{ $k }}[]" value="{{ $vv }}">
                @endforeach
              @else
                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
              @endif
            @endforeach
          </form>

          {{-- Aksi Import + Create --}}
          <div class="w-full md:w-auto flex flex-col md:flex-row gap-2">
            <form method="POST" action="{{ route('pelaku-industri.import') }}" enctype="multipart/form-data"
                  class="w-full md:w-auto flex flex-col sm:flex-row gap-2 md:items-center">
              @csrf
              <input type="file" name="file" accept=".xlsx,.xls" required class="border rounded px-3 py-2 w-full sm:w-auto">
              <input type="hidden" name="positional" value="1" />
              <button class="px-4 py-2 bg-indigo-600 text-white rounded w-full sm:w-auto">Import Excel</button>
            </form>
            <a href="{{ route('pelaku-industri.create') }}"
               class="px-4 py-2 bg-indigo-600 text-white rounded text-center">+ Input Data Manual</a>
          </div>
        </div>

        {{-- ====== FILTER LANJUTAN ====== --}}
        <form method="GET" class="mb-4 fade-up-soft" style="--d:.14s">
          <input type="hidden" name="q" value="{{ $q }}">

          <div x-data="{open:false}" class="border rounded-lg overflow-hidden">
            <button type="button" @click="open=!open"
                    class="w-full flex items-center justify-between px-4 py-2 bg-gray-50 hover:bg-gray-100">
              <span class="font-semibold text-gray-700">Filter Lanjutan</span>
              <svg :class="{'rotate-180':open}" class="h-5 w-5 text-gray-500 transition-transform" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
              </svg>
            </button>

            <div x-show="open" x-collapse>
              <div class="p-4 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-3">
                {{-- Skala Usaha --}}
                <div>
                  <label class="block text-xs text-gray-600 mb-1">Skala Usaha</label>
                  <select name="skala_usaha" class="w-full border rounded px-2 py-2">
                    <option value="">— Semua —</option>
                    @foreach($optSkala as $v)
                      <option value="{{ $v }}" @selected(($v??'')===($f_skala??''))>{{ $v }}</option>
                    @endforeach
                  </select>
                </div>
                {{-- Jenis Perusahaan --}}
                <div>
                  <label class="block text-xs text-gray-600 mb-1">Jenis Perusahaan</label>
                  <select name="jenis_perusahaan" class="w-full border rounded px-2 py-2">
                    <option value="">— Semua —</option>
                    @foreach($optJenis as $v)
                      <option value="{{ $v }}" @selected(($v??'')===($f_jenis??''))>{{ $v }}</option>
                    @endforeach
                  </select>
                </div>
                {{-- Kecamatan --}}
                <div>
                  <label class="block text-xs text-gray-600 mb-1">Kecamatan</label>
                  <select name="kecamatan" class="w-full border rounded px-2 py-2">
                    <option value="">— Semua —</option>
                    @foreach($optKec as $v)
                      <option value="{{ $v }}" @selected(($v??'')===($f_kecamatan??''))>{{ $v }}</option>
                    @endforeach
                  </select>
                </div>
                {{-- Kelurahan --}}
                <div>
                  <label class="block text-xs text-gray-600 mb-1">Kelurahan</label>
                  <select name="kelurahan" class="w-full border rounded px-2 py-2">
                    <option value="">— Semua —</option>
                    @foreach($optKel as $v)
                      <option value="{{ $v }}" @selected(($v??'')===($f_kelurahan??''))>{{ $v }}</option>
                    @endforeach
                  </select>
                </div>
                {{-- Turunan KBLI --}}
                <div>
                  <label class="block text-xs text-gray-600 mb-1">Turunan KBLI (3 digit)</label>
                  <select name="kbli_prefix" class="w-full border rounded px-2 py-2">
                    <option value="">— Semua —</option>
                    @foreach($optKbliPref as $v)
                      <option value="{{ $v }}" @selected(($v??'')===($f_kbli_prefix??''))>{{ $v }}</option>
                    @endforeach
                  </select>
                </div>
                {{-- Uraian KBLI --}}
                <div>
                  <label class="block text-xs text-gray-600 mb-1">Uraian KBLI</label>
                  <select name="uraian_kbli" class="w-full border rounded px-2 py-2">
                    <option value="">— Semua —</option>
                    @foreach($optUraian as $v)
                      <option value="{{ $v }}" @selected(($v??'')===($f_uraian_kbli??''))>{{ $v }}</option>
                    @endforeach
                  </select>
                  <p class="text-[11px] text-gray-500 mt-1">Bisa pilih uraian saja tanpa memilih turunan.</p>
                </div>
                {{-- Tingkat Risiko --}}
                <div>
                  <label class="block text-xs text-gray-600 mb-1">Tingkat Risiko</label>
                  <select name="tingkat_risiko" class="w-full border rounded px-2 py-2">
                    <option value="">— Semua —</option>
                    @foreach($optRisiko as $v)
                      <option value="{{ $v }}" @selected(($v??'')===($f_risiko??''))>{{ $v }}</option>
                    @endforeach
                  </select>
                </div>
                {{-- Sektor Pembina --}}
                <div>
                  <label class="block text-xs text-gray-600 mb-1">Sektor Pembina</label>
                  <select name="sektor_pembina" class="w-full border rounded px-2 py-2">
                    <option value="">— Semua —</option>
                    @foreach($optSektor as $v)
                      <option value="{{ $v }}" @selected(($v??'')===($f_sektor??''))>{{ $v }}</option>
                    @endforeach
                  </select>
                </div>

                {{-- Rentang tanggal --}}
                <div>
                  <label class="block text-xs text-gray-600 mb-1">Tanggal Terbit (From)</label>
                  <input type="date" name="tgl_from" value="{{ $f_tgl_from }}" class="w-full border rounded px-2 py-2">
                </div>
                <div>
                  <label class="block text-xs text-gray-600 mb-1">Tanggal Terbit (To)</label>
                  <input type="date" name="tgl_to" value="{{ $f_tgl_to }}" class="w-full border rounded px-2 py-2">
                </div>

                {{-- Rentang angka --}}
                <div>
                  <label class="block text-xs text-gray-600 mb-1">Investasi Min</label>
                  <input type="number" inputmode="numeric" step="1" name="invest_min" value="{{ old('invest_min', $f_invest_min) }}" class="w-full border rounded px-2 py-2" placeholder="0">
                </div>
                <div>
                  <label class="block text-xs text-gray-600 mb-1">Investasi Max</label>
                  <input type="number" inputmode="numeric" step="1" name="invest_max" value="{{ old('invest_max', $f_invest_max) }}" class="w-full border rounded px-2 py-2" placeholder="999999999">
                </div>
                <div>
                  <label class="block text-xs text-gray-600 mb-1">Tenaga Kerja Min</label>
                  <input type="number" inputmode="numeric" step="1" name="tk_min" value="{{ old('tk_min', $f_tk_min) }}" class="w-full border rounded px-2 py-2" placeholder="0">
                </div>
                <div>
                  <label class="block text-xs text-gray-600 mb-1">Tenaga Kerja Max</label>
                  <input type="number" inputmode="numeric" step="1" name="tk_max" value="{{ old('tk_max', $f_tk_max) }}" class="w-full border rounded px-2 py-2" placeholder="999999">
                </div>

                {{-- Per Page --}}
                <div>
                  <label class="block text-xs text-gray-600 mb-1">Baris per halaman</label>
                  <select name="per_page" class="w-full border rounded px-2 py-2">
                    @foreach([10,20,50,100] as $pp)
                      <option value="{{ $pp }}" @selected($pp===$perPage)>{{ $pp }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="px-4 py-3 bg-gray-50 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="text-[11px] text-gray-500">
                  Gunakan beberapa filter sekaligus untuk mempersempit hasil.
                </div>
                <div class="flex gap-2 w-full sm:w-auto">
                  <a href="{{ route('pelaku-industri.index') }}" class="px-3 py-2 bg-red-500 text-white rounded w-full sm:w-auto text-center">Reset</a>
                  <button class="px-4 py-2 bg-gray-800 text-white rounded w-full sm:w-auto">Terapkan Filter</button>
                </div>
              </div>
            </div>
          </div>
        </form>

        {{-- ====== TABEL + BULK DELETE ====== --}}
        <div x-data="bulkSelection()" class="fade-up-soft" style="--d:.18s">
          {{-- Toolbar bulk --}}
          <div class="flex flex-wrap items-center gap-3 mb-3">
            <form x-ref="bulkForm" method="POST" action="{{ route('pelaku-industri.bulk-destroy') }}"
                  @submit.prevent="confirmAndSubmit" class="flex items-center gap-2">
              @csrf
              {{-- container untuk input hidden ids[] --}}
              <div x-ref="idsContainer"></div>

              <button type="submit"
                      class="px-3 py-2 rounded bg-red-600 text-white disabled:opacity-50 disabled:cursor-not-allowed"
                      :disabled="count === 0">
                Hapus Terpilih
              </button>

              <span class="text-sm text-gray-600" x-text="count + ' dipilih'"></span>
            </form>
          </div>

          <div class="table-wrap rounded border border-gray-200 fade-up-soft" style="--d:.22s">
            <table class="table-sticky min-w-[1600px] w-full text-sm">
              <colgroup>
                <col class="w-10"><!-- Checkbox -->
                <col class="w-40"><col class="w-32"><col class="w-40"><col class="w-64">
                <col class="w-72"><col class="w-56"><col class="w-96"><col class="w-40">
                <col class="w-40"><col class="w-28"><col class="w-96"><col class="w-40">
                <col class="w-56"><col class="w-40"><col class="w-44"><col class="w-64">
                <col class="w-40"><col class="w-56"><col class="w-40">
              </colgroup>

              <thead>
                <tr class="bg-gray-100 text-left align-top">
                  <th class="p-2 sticky-col-left">
                    {{-- Select all (halaman ini) --}}
                    <input type="checkbox" class="h-4 w-4"
                           @change="toggleAll($event)" :checked="allChecked">
                  </th>
                  <th class="p-2">NIB</th>
                  <th class="p-2">Skala Usaha</th>
                  <th class="p-2">Jenis Perusahaan</th>
                  <th class="p-2">Nama Perusahaan</th>
                  <th class="p-2">Nama Proyek</th>
                  <th class="p-2">Nama Pemilik</th>
                  <th class="p-2">Alamat Usaha</th>
                  <th class="p-2">Kecamatan</th>
                  <th class="p-2">Kelurahan</th>
                  <th class="p-2">KBLI</th>
                  <th class="p-2">Uraian KBLI</th>
                  <th class="p-2">Tingkat Risiko</th>
                  <th class="p-2">Jumlah Investasi</th>
                  <th class="p-2">Jumlah Tenaga Kerja</th>
                  <th class="p-2">Nomor Telp</th>
                  <th class="p-2">Email</th>
                  <th class="p-2">Tanggal Terbit</th>
                  <th class="p-2">Sektor Pembina</th>
                  <th class="p-2 sticky-col-right">Aksi</th>
                </tr>
              </thead>

              <tbody>
                @forelse($items as $row)
                  <tr class="border-b align-top leading-snug odd:bg-white even:bg-gray-50 row-fade"
                      x-data="{ id: {{ $row->id }} }"
                      :class="isChecked(id) ? 'bg-red-50' : ''">
                    <td class="p-2 sticky-col-left">
                      <input type="checkbox" class="h-4 w-4"
                             :value="id" @change="toggle(id)" :checked="isChecked(id)">
                    </td>
                    <td class="p-2 whitespace-nowrap">{{ $row->nib ?? '-' }}</td>
                    <td class="p-2">{{ $row->skala_usaha ?? '-' }}</td>
                    <td class="p-2">{{ $row->jenis_perusahaan ?? '-' }}</td>
                    <td class="p-2 whitespace-normal break-words">
                      @if($row->nama_perusahaan)
                        <a class="text-indigo-700 underline" href="{{ route('pelaku-industri.show', $row) }}">{{ $row->nama_perusahaan }}</a>
                      @else - @endif
                    </td>
                    <td class="p-2 whitespace-normal break-words">{{ $row->nama_proyek ?? '-' }}</td>
                    <td class="p-2 whitespace-normal break-words">{{ $row->nama_pemilik ?? '-' }}</td>
                    <td class="p-2 whitespace-normal break-words">{{ $row->alamat_usaha ?? '-' }}</td>
                    <td class="p-2">{{ $row->kecamatan ?? '-' }}</td>
                    <td class="p-2">{{ $row->kelurahan ?? '-' }}</td>
                    <td class="p-2 whitespace-nowrap">{{ $row->kbli ?? '-' }}</td>
                    <td class="p-2 whitespace-normal break-words">{{ $row->uraian_kbli ?? '-' }}</td>
                    <td class="p-2">{{ $row->tingkat_risiko ?? '-' }}</td>
                    <td class="p-2 whitespace-nowrap">
                      @php $invest = $row->jumlah_investasi; @endphp
                      {{ is_numeric($invest)? number_format($invest, 0, ',', '.'): '-' }}
                    </td>
                    <td class="p-2">{{ $row->jumlah_tenaga_kerja ?? '-' }}</td>
                    <td class="p-2">
                      @if(!empty($row->nomor_telp))
                        <a class="underline" href="tel:{{ preg_replace('/\D+/', '', $row->nomor_telp) }}">{{ $row->nomor_telp }}</a>
                      @else - @endif
                    </td>
                    <td class="p-2 whitespace-normal break-words">
                      @if(!empty($row->email))
                        <a class="underline" href="mailto:{{ $row->email }}">{{ $row->email }}</a>
                      @else - @endif
                    </td>
                    <td class="p-2 whitespace-nowrap">
                      @php
                        $raw = trim((string)($row->tanggal_terbit ?? ''));
                        $display = '-';

                        if ($raw !== '' && $raw !== '0000-00-00') {
                            try {
                                $parsed = null;

                                if (preg_match('/^\d{5,}$/', $raw)) {
                                    $parsed = \Carbon\Carbon::create(1899, 12, 30)->addDays((int)$raw);
                                } else {
                                    $fmts = ['Y-m-d','d/m/Y','d-m-Y','d.m.Y','m/d/Y','m-d-Y'];
                                    foreach ($fmts as $f) {
                                        try { $parsed = \Carbon\Carbon::createFromFormat($f, $raw); break; }
                                        catch (\Exception $e) {}
                                    }
                                    if (!$parsed) $parsed = \Carbon\Carbon::parse($raw);
                                }

                                $display = $parsed ? $parsed->format('d M Y') : $raw;
                            } catch (\Exception $e) {
                                $display = $raw;
                            }
                        }
                      @endphp
                      {{ $display }}
                    </td>

                    <td class="p-2 whitespace-normal break-words">{{ $row->sektor_pembina ?? '-' }}</td>
                    <td class="p-2 sticky-col-right">
                      <div class="flex flex-wrap gap-2">
                        <a href="{{ route('pelaku-industri.edit', $row) }}" class="px-3 py-1 bg-yellow-500 text-white rounded">Edit</a>
                        <form method="POST" action="{{ route('pelaku-industri.destroy', $row) }}" onsubmit="return confirm('Yakin hapus data ini?')">
                          @csrf
                          @method('DELETE')
                          <button class="px-3 py-1 bg-red-600 text-white rounded">Hapus</button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr class="row-fade in"><td colspan="20" class="p-4 text-center text-gray-500">Belum ada data.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-4 fade-up-soft" style="--d:.24s">
          <x-pagination :paginator="$items->withQueryString()" />
        </div>

      </div>
    </div>
  </div>

  {{-- Alpine helpers untuk Bulk Delete --}}
  <script>
    function bulkSelection() {
      return {
        selected: new Set(),
        get count() { return this.selected.size; },
        get allChecked() {
          const boxes = document.querySelectorAll('tbody input[type="checkbox"][value]');
          return boxes.length > 0 && Array.from(boxes).every(b => this.selected.has(parseInt(b.value)));
        },
        isChecked(id) { return this.selected.has(parseInt(id)); },
        toggle(id) {
          id = parseInt(id);
          if (this.selected.has(id)) this.selected.delete(id); else this.selected.add(id);
          this.syncHiddenInputs();
        },
        toggleAll(ev) {
          const boxes = document.querySelectorAll('tbody input[type="checkbox"][value]');
          if (ev.target.checked) {
            boxes.forEach(b => this.selected.add(parseInt(b.value)));
          } else {
            boxes.forEach(b => this.selected.delete(parseInt(b.value)));
          }
          this.syncHiddenInputs();
        },
        syncHiddenInputs() {
          const c = this.$refs.idsContainer;
          c.innerHTML = '';
          this.selected.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            c.appendChild(input);
          });
        },
        confirmAndSubmit() {
          if (this.count === 0) return;
          if (confirm(`Yakin ingin menghapus ${this.count} data terpilih? Tindakan ini tidak dapat dibatalkan.`)) {
            this.$refs.bulkForm.submit();
          }
        }
      }
    }

    // ====== Fade-in baris saat masuk viewport (scroll) ======
    document.addEventListener('DOMContentLoaded', function(){
      // Jangan jalankan jika user prefer-reduced-motion
      const reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      if (reduce) return;

      const rows = document.querySelectorAll('tbody .row-fade');
      const obs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
          if (e.isIntersecting) {
            e.target.classList.add('in');
            // Unobserve supaya tidak toggle-toggling saat scroll balik
            obs.unobserve(e.target);
          }
        });
      }, {threshold: 0.05, rootMargin: '0px 0px -10% 0px'});
      rows.forEach(r => obs.observe(r));
    });
  </script>
</x-app-layout>
