<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Filter berdasarkan Uraian KBLI') }}
    </h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
      {{-- Tombol/area filter --}}
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
          <form method="GET" action="{{ route('pelaku.filter-kbli') }}" class="flex flex-col md:flex-row gap-3 md:items-end">
            <div class="flex-1">
              <label for="uraian_kbli" class="block text-sm font-medium text-gray-700 mb-1">
                Pilih Uraian KBLI
              </label>
              <select id="uraian_kbli" name="uraian_kbli"
                class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">— Semua Uraian KBLI —</option>
                @foreach($options as $opt)
                  <option value="{{ $opt }}" @selected($opt === $selected)>{{ $opt }}</option>
                @endforeach
              </select>
            </div>

            <div class="flex gap-2">
              <button type="submit"
                class="inline-flex items-center px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                Filter
              </button>

              <a href="{{ route('pelaku.filter-kbli') }}"
                 class="inline-flex items-center px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50">
                Reset
              </a>
            </div>
          </form>
        </div>
      </div>

      {{-- HEADER RINGKASAN (sesuai mockup) --}}
<div class="bg-white border-4 border-gray-400 rounded-3xl shadow-sm p-5 md:p-8">
  <div class="flex items-center justify-between">
    <h3 class="text-2xl md:text-3xl font-semibold text-gray-800">
      {{ $selected ? $selected : 'Semua Industri (KBLI)' }}
    </h3>
    <span class="text-2xl text-gray-600">^--</span>
  </div>

  {{-- 4 Kartu Skala Usaha --}}
  <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
    @php
      $kartu = [
        ['label' => 'Skala Mikro', 'val' => $skalaCounts['Mikro'] ?? 0],
        ['label' => 'Skala Kecil', 'val' => $skalaCounts['Kecil'] ?? 0],
        ['label' => 'Skala Menengah', 'val' => $skalaCounts['Menengah'] ?? 0],
        ['label' => 'Skala Besar', 'val' => $skalaCounts['Besar'] ?? 0],
      ];
    @endphp

    @foreach($kartu as $k)
      <div class="rounded-2xl border-4 border-gray-400 p-6 text-center">
        <div class="text-lg text-gray-700 leading-tight">
          {{ $k['label'] }} :
        </div>
        <div class="mt-2 text-3xl font-semibold text-gray-900">
          {{ number_format($k['val'] ?? 0, 0, ',', '.') }}
        </div>
      </div>
    @endforeach
  </div>

  {{-- Dua Panel: TK & Investasi --}}
  <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Panel Tenaga Kerja --}}
    <div class="rounded-2xl border-4 border-gray-400 p-6">
      <div class="text-3xl font-semibold text-gray-800 text-center">
        Jumlah Tenaga Kerja
      </div>
      <div class="mt-4 flex items-center justify-center">
        {{-- Ikon Gear + People (SVG) --}}
        <div class="p-4 rounded-xl shadow border">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-24 h-24 fill-gray-800">
            <path d="M12 8a4 4 0 100 8 4 4 0 000-8Zm8.94 3.5a7.96 7.96 0 00-.63-1.52l1.35-2.34-2.12-2.12-2.34 1.35c-.49-.27-1-.48-1.53-.63L14.5 2h-3l-.17 3.24c-.52.15-1.03.36-1.52.63L7.47 4.52 5.35 6.64l1.35 2.34c-.27.49-.48 1-.63 1.52L2 9.5v3l3.24.17c.15.52.36 1.03.63 1.52l-1.35 2.34 2.12 2.12 2.34-1.35c.49.27 1 .48 1.52.63L11.5 22h3l.17-3.24c.52-.15 1.03-.36 1.53-.63l2.34 1.35 2.12-2.12-1.35-2.34c.27-.49.48-1 .63-1.52L22 12.5v-3l-3.06-.17Z"/>
          </svg>
        </div>
      </div>
      <div class="mt-4 text-center text-3xl font-semibold text-gray-900">
        {{ number_format($agg->total_tk ?? 0, 0, ',', '.') }}
      </div>
    </div>

    {{-- Panel Investasi --}}
    <div class="rounded-2xl border-4 border-gray-400 p-6">
      <div class="text-3xl font-semibold text-gray-800 text-center">
        Jumlah Investasi
      </div>
      <div class="mt-4 flex items-center justify-center">
        {{-- Ikon Grafik Naik (SVG) --}}
        <div class="p-4 rounded-xl shadow border">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M4 19h16M5 16v3M9 13v6M13 10v9M17 7v12" stroke-width="2"/>
            <path d="M4 12l6-5 4 3 6-5" stroke-width="2"/>
            <path d="M18 5h2v2" stroke-width="2"/>
          </svg>
        </div>
      </div>
      <div class="mt-4 text-center text-3xl font-semibold text-gray-900">
        Rp {{ number_format($agg->total_investasi ?? 0, 0, ',', '.') }},-
      </div>
    </div>
  </div>
</div>

      {{-- Hasil --}}
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
          @if($selected)
            <div class="mb-3 text-sm text-gray-600">
              Menampilkan untuk <span class="font-semibold">"{{ $selected }}"</span>
            </div>
          @endif

          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Perusahaan</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skala Usaha</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($rows as $row)
                  <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-gray-900">{{ $row->nama_perusahaan }}</td>
                    <td class="px-4 py-2 text-gray-700">{{ $row->skala_usaha }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="2" class="px-4 py-6 text-center text-gray-500">Tidak ada data.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="mt-4">
            {{ $rows->links() }}
          </div>
        </div>
      </div>

      {{-- Tombol cepat dari halaman lain (opsional) --}}

</x-app-layout>
