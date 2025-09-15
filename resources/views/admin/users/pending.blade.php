<x-app-layout>
  {{-- HEADER --}}
  <x-slot name="header">
    <h2 class="font-semibold text-xl leading-tight text-[#0A6FB5]">
      {{ __('Persetujuan User') }}
    </h2>
  </x-slot>

  {{-- THEME STYLES: biru–hijau–merah --}}
  <style>
    :root{
      --brand-blue:#0A6FB5;  /* utama */
      --brand-green:#45A243; /* Setujui */
      --brand-red:#F05128;   /* Tolak */
    }
    .card{background:#fff;border:1px solid color-mix(in srgb,var(--brand-blue) 20%,#fff);
      border-radius:1rem;box-shadow:0 1px 2px rgba(0,0,0,.05)}
    .table thead th{background:color-mix(in srgb,var(--brand-blue) 12%,#fff);color:var(--brand-blue)}
    .btn{display:inline-flex;align-items:center;justify-content:center;padding:.5rem .9rem;
      border-radius:.6rem;font-weight:600;line-height:1.25;transition:.15s;white-space:nowrap}
    .btn:focus{outline:2px solid transparent;box-shadow:0 0 0 4px color-mix(in srgb,currentColor 30%,#0000)}
    .btn-blue{background:var(--brand-blue);color:#fff}
    .btn-blue:hover{filter:brightness(.95)}
    .btn-green{background:var(--brand-green);color:#fff}
    .btn-green:hover{filter:brightness(.95)}
    .btn-red{background:var(--brand-red);color:#fff}
    .btn-red:hover{filter:brightness(.95)}
    .tag{display:inline-flex;gap:.4rem;align-items:center;padding:.25rem .6rem;border-radius:9999px;
      background:color-mix(in srgb,var(--brand-blue) 12%,#fff);color:var(--brand-blue);font-size:.82rem}
  </style>

  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">

      {{-- ALERTS --}}
      <div>
        <x-auth-session-status class="mb-4" :status="session('status')" />
        @if ($errors->any())
          <div class="bg-red-50 text-red-800 px-4 py-3 rounded-lg border border-red-200">
            {{ $errors->first() }}
          </div>
        @endif
      </div>

      {{-- WRAPPER --}}
      <div class="card overflow-hidden">
        <div class="px-5 py-4 border-b"
             style="background:linear-gradient(90deg,
               color-mix(in srgb,var(--brand-green) 10%,#fff),
               color-mix(in srgb,var(--brand-blue) 10%,#fff) 50%,
               color-mix(in srgb,var(--brand-red) 10%,#fff) 100%);border-color:color-mix(in srgb,var(--brand-blue) 20%,#fff)">
          <span class="tag">Pendaftaran menunggu persetujuan</span>
        </div>

        <div class="p-5">
          @if ($pending->count() === 0)
            <p class="text-gray-600">Tidak ada pendaftaran menunggu persetujuan.</p>
          @else
            {{-- ===== MOBILE (KARTU) ===== --}}
            <div class="grid gap-4 md:hidden">
              @foreach ($pending as $user)
                <div class="card p-4">
                  <div class="flex justify-between gap-3">
                    <div>
                      <div class="text-base font-semibold text-gray-900">{{ $user->name }}</div>
                      <div class="text-sm text-gray-600">{{ $user->email }}</div>
                      <div class="mt-1 text-xs text-gray-500">
                        Daftar: {{ $user->created_at->format('d M Y H:i') }}
                      </div>
                    </div>
                  </div>

                  <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="contents">
                      @csrf
                      <button type="submit" class="btn btn-green w-full">Setujui</button>
                    </form>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                          onsubmit="return confirm('Yakin tolak & hapus akun ini?');" class="contents">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-red w-full">Tolak & Hapus</button>
                    </form>
                  </div>
                </div>
              @endforeach
            </div>

            {{-- ===== DESKTOP (TABEL) ===== --}}
            <div class="hidden md:block overflow-x-auto">
              <table class="min-w-full table text-sm">
                <thead>
                  <tr class="text-left">
                    <th class="px-4 py-3 font-semibold">Nama</th>
                    <th class="px-4 py-3 font-semibold">Email</th>
                    <th class="px-4 py-3 font-semibold">Tanggal Daftar</th>
                    <th class="px-4 py-3 font-semibold">Aksi</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                  @foreach ($pending as $user)
                    <tr class="hover:bg-gray-50">
                      <td class="px-4 py-3 text-gray-900 whitespace-nowrap">{{ $user->name }}</td>
                      <td class="px-4 py-3 text-gray-700 whitespace-nowrap">{{ $user->email }}</td>
                      <td class="px-4 py-3 text-gray-700 whitespace-nowrap">{{ $user->created_at->format('d M Y H:i') }}</td>
                      <td class="px-4 py-3">
                        <div class="flex flex-wrap items-center gap-2">
                          <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                            @csrf
                            <button type="submit" class="btn btn-green">Setujui</button>
                          </form>
                          <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                onsubmit="return confirm('Yakin tolak & hapus akun ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-red">Tolak & Hapus</button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <div class="mt-4">
              {{ $pending->links() }}
            </div>
          @endif
        </div>
      </div>

    </div>
  </div>
</x-app-layout>
