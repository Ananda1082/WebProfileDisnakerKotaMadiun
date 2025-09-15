@props(['paginator'])

@if ($paginator->hasPages())
@php
  $current = $paginator->currentPage();
  $last    = $paginator->lastPage();
  $start   = max(1, $current - 2);
  $end     = min($last, $current + 2);

  // Konfigurasi daftar "halaman tersedia" ringkas
  $window = 5; // tampilkan 1..5, lalu …, lalu last (jika >5)
  $headPages = range(1, min($last, $window));
  $hasTail   = $last > $window;
@endphp

<div class="mt-5">
  {{-- Desktop --}}
  <div class="hidden md:flex flex-col gap-2">
    <div class="flex items-center justify-between bg-white border border-gray-200 rounded-2xl px-4 py-3 shadow-sm">
      <div class="text-sm text-gray-600">
        Menampilkan
        <span class="font-semibold text-gray-800">{{ $paginator->firstItem() }}</span>–<span class="font-semibold text-gray-800">{{ $paginator->lastItem() }}</span>
        dari <span class="font-semibold text-gray-800">{{ $paginator->total() }}</span> data
      </div>

      <nav class="flex items-center gap-1" role="navigation" aria-label="Pagination">
        {{-- First --}}
        <a href="{{ $current > 1 ? $paginator->url(1) : '#' }}"
           class="inline-flex items-center h-9 px-3 text-sm rounded-lg border transition-all duration-200
                  {{ $current > 1
                    ? 'bg-white text-[#0A6FB5] border-[#0A6FB5]/30 hover:bg-[#0A6FB5]/10 hover:text-[#084d7c] hover:scale-105'
                    : 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed' }}">
          «
        </a>
        {{-- Prev --}}
        <a href="{{ $paginator->previousPageUrl() ?: '#' }}"
           class="inline-flex items-center h-9 px-3 text-sm rounded-lg border transition-all duration-200
                  {{ $paginator->onFirstPage()
                    ? 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed'
                    : 'bg-white text-[#0A6FB5] border-[#0A6FB5]/30 hover:bg-[#0A6FB5]/10 hover:text-[#084d7c] hover:scale-105' }}">
          ‹
        </a>

        {{-- Gap depan --}}
        @if ($start > 1)
          <a href="{{ $paginator->url(1) }}"
             class="inline-flex items-center h-9 px-3 text-sm rounded-lg border bg-white text-[#0A6FB5] border-[#0A6FB5]/30 transition-all duration-200 hover:bg-[#0A6FB5]/10 hover:text-[#084d7c] hover:scale-105">1</a>
          <span class="px-2 text-gray-400">…</span>
        @endif

        {{-- Range sekitar current --}}
        @for ($i = $start; $i <= $end; $i++)
          @if ($i == $current)
            <span aria-current="page"
                  class="inline-flex items-center h-9 px-3 text-sm rounded-lg border bg-[#0A6FB5] text-white border-[#0A6FB5] font-semibold shadow-inner">
              {{ $i }}
            </span>
          @else
            <a href="{{ $paginator->url($i) }}"
               class="inline-flex items-center h-9 px-3 text-sm rounded-lg border bg-white text-[#0A6FB5] border-[#0A6FB5]/30 transition-all duration-200 hover:bg-[#0A6FB5]/10 hover:text-[#084d7c] hover:scale-105">
              {{ $i }}
            </a>
          @endif
        @endfor

        {{-- Gap belakang --}}
        @if ($end < $last)
          <span class="px-2 text-gray-400">…</span>
          <a href="{{ $paginator->url($last) }}"
             class="inline-flex items-center h-9 px-3 text-sm rounded-lg border bg-white text-[#0A6FB5] border-[#0A6FB5]/30 transition-all duration-200 hover:bg-[#0A6FB5]/10 hover:text-[#084d7c] hover:scale-105">{{ $last }}</a>
        @endif

        {{-- Next --}}
        <a href="{{ $paginator->nextPageUrl() ?: '#' }}"
           class="inline-flex items-center h-9 px-3 text-sm rounded-lg border transition-all duration-200
                  {{ $current < $last
                    ? 'bg-white text-[#0A6FB5] border-[#0A6FB5]/30 hover:bg-[#0A6FB5]/10 hover:text-[#084d7c] hover:scale-105'
                    : 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed' }}">
          ›
        </a>
        {{-- Last --}}
        <a href="{{ $current < $last ? $paginator->url($last) : '#' }}"
           class="inline-flex items-center h-9 px-3 text-sm rounded-lg border transition-all duration-200
                  {{ $current < $last
                    ? 'bg-white text-[#0A6FB5] border-[#0A6FB5]/30 hover:bg-[#0A6FB5]/10 hover:text-[#084d7c] hover:scale-105'
                    : 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed' }}">
          »
        </a>
      </nav>
    </div>

    {{-- Halaman tersedia (ringkas) --}}
    <div class="text-xs text-gray-600">
      <span class="mr-2">Halaman tersedia:</span>
      @foreach ($headPages as $p)
        @if ($p == $current)
          <span class="mx-0.5 inline-flex items-center h-7 px-2 rounded bg-[#0A6FB5] text-white border border-[#0A6FB5] font-medium">{{ $p }}</span>
        @else
          <a href="{{ $paginator->url($p) }}" class="mx-0.5 inline-flex items-center h-7 px-2 rounded border bg-white text-[#0A6FB5] border-[#0A6FB5]/30 hover:bg-[#0A6FB5]/10 hover:text-[#084d7c] transition">
            {{ $p }}
          </a>
        @endif
      @endforeach

      @if ($hasTail)
        <span class="mx-1 text-gray-400">…</span>
        <a href="{{ $paginator->url($last) }}" class="mx-0.5 inline-flex items-center h-7 px-2 rounded border bg-white text-[#0A6FB5] border-[#0A6FB5]/30 hover:bg-[#0A6FB5]/10 hover:text-[#084d7c] transition">
          {{ $last }}
        </a>
      @endif
    </div>
  </div>

  {{-- Mobile --}}
  <div class="md:hidden">
    <div class="flex items-center justify-between gap-2 bg-white border border-gray-200 rounded-2xl px-3 py-2 shadow-sm">
      <a href="{{ $paginator->previousPageUrl() ?: '#' }}"
         class="inline-flex items-center h-9 px-3 text-sm rounded-lg border transition-all duration-200
                {{ $paginator->onFirstPage()
                  ? 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed'
                  : 'bg-white text-[#0A6FB5] border-[#0A6FB5]/30 hover:bg-[#0A6FB5]/10 hover:text-[#084d7c] hover:scale-105' }}">
        Sebelumnya
      </a>

      <span class="text-xs text-gray-600 flex-1 text-center">
        Hal <span class="font-semibold text-gray-800">{{ $current }}</span> / {{ $last }}
      </span>

      <a href="{{ $paginator->nextPageUrl() ?: '#' }}"
         class="inline-flex items-center h-9 px-3 text-sm rounded-lg border transition-all duration-200
                {{ $current < $last
                  ? 'bg-white text-[#0A6FB5] border-[#0A6FB5]/30 hover:bg-[#0A6FB5]/10 hover:text-[#084d7c] hover:scale-105'
                  : 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed' }}">
        Berikutnya
      </a>
    </div>

    {{-- Halaman tersedia (ringkas) mobile --}}
    <div class="mt-2 text-[11px] text-gray-600 text-center">
      @foreach ($headPages as $p)
        @if ($p == $current)
          <span class="mx-0.5 inline-flex items-center h-6 px-2 rounded bg-[#0A6FB5] text-white border border-[#0A6FB5]">{{ $p }}</span>
        @else
          <a href="{{ $paginator->url($p) }}" class="mx-0.5 inline-flex items-center h-6 px-2 rounded border bg-white text-[#0A6FB5] border-[#0A6FB5]/30">
            {{ $p }}
          </a>
        @endif
      @endforeach
      @if ($hasTail)
        <span class="mx-1 text-gray-400">…</span>
        <a href="{{ $paginator->url($last) }}" class="mx-0.5 inline-flex items-center h-6 px-2 rounded border bg-white text-[#0A6FB5] border-[#0A6FB5]/30">
          {{ $last }}
        </a>
      @endif
    </div>

    <div class="mt-1 text-[11px] text-gray-500 text-center">
      {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data
    </div>
  </div>
</div>
@endif
