{{-- resources/views/pelaku-industri/public-index.blade.php --}}
<x-guest-layout>
    {{-- Beberapa guest layout tidak punya slot header, jadi judul kita taruh di konten --}}
    <title>Data Pelaku Industri (Publik)</title>

    <div class="py-6">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <h1 class="font-semibold text-xl text-gray-800">Data Pelaku Industri — Publik</h1>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                {{-- Pencarian (GET) --}}
                <form method="GET" class="flex gap-2 mb-4">
                    <input
                        type="text"
                        name="q"
                        value="{{ $q }}"
                        placeholder="Cari Nama/Alamat/No Telp"
                        class="border rounded px-3 py-2 w-64"
                    >
                    <button class="px-4 py-2 bg-gray-800 text-white rounded">Cari</button>
                </form>

                {{-- Tabel publik: hanya 3 kolom --}}
                <div class="overflow-x-auto">
                    <table class="min-w-[900px] w-full text-sm">
                        <colgroup>
                            <col class="w-1/3"><!-- Nama Perusahaan -->
                            <col class="w-1/2"><!-- Alamat Usaha -->
                            <col class="w-1/6"><!-- Nomor Telp -->
                        </colgroup>

                        <thead>
                            <tr class="bg-gray-100 text-left align-top">
                                <th class="p-2">Nama Perusahaan</th>
                                <th class="p-2">Alamat Usaha</th>
                                <th class="p-2">Nomor Telp</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($items as $row)
                                <tr class="border-b align-top leading-snug">
                                    <td class="p-2 whitespace-normal break-words">
                                        {{ $row->nama_perusahaan ?? '-' }}
                                    </td>
                                    <td class="p-2 whitespace-normal break-words">
                                        {{ $row->alamat_usaha ?? '-' }}
                                    </td>
                                    <td class="p-2 whitespace-normal break-words">
                                        @php
                                            $telp = $row->nomor_telp ?? '';
                                            $telpDial = preg_replace('/\D+/', '', $telp);
                                        @endphp
                                        @if(!empty($telp) && !empty($telpDial))
                                            <a class="underline" href="tel:{{ $telpDial }}">{{ $telp }}</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-4 text-center text-gray-500">Belum ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">{{ $items->links() }}</div>
            </div>
        </div>
    </div>
</x-guest-layout>
