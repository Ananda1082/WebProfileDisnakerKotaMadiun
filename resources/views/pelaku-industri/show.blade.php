{{-- resources/views/pelaku-industri/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <title>Kelola Data Pelaku Industri</title>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pelaku Industri') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-6">

                <div class="flex items-center justify-between">
                    <a href="{{ route('pelaku-industri.index') }}" class="text-indigo-700 underline">← Kembali</a>
                    <div class="flex gap-2">
                        <a href="{{ route('pelaku-industri.edit', $pelakuIndustri) }}"
                           class="px-3 py-1.5 bg-yellow-500 text-white rounded">Edit</a>
                        <form method="POST" action="{{ route('pelaku-industri.destroy', $pelakuIndustri) }}"
                              onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="px-3 py-1.5 bg-red-600 text-white rounded">Hapus</button>
                        </form>
                    </div>
                </div>

                @php
                    $f = $pelakuIndustri;

                    // Format angka investasi (boleh null/str)
                    $fmtInvest = function($v) {
                        if (is_null($v) || $v === '') return '-';
                        $num = is_numeric($v) ? $v : preg_replace('/[^\d]/','',$v);
                        if ($num === '' ) return e((string)$v);
                        return 'Rp '.number_format((float)$num, 0, ',', '.');
                    };

                    // Tanggal aman: coba parse, kalau gagal tampilkan teks asli
                    $fmtTanggal = function($v) {
                        if (empty($v)) return '-';
                        try {
                            $d = \Carbon\Carbon::parse($v);
                            return e($d->format('d M Y'));
                        } catch (\Exception $e) {
                            return e($v);
                        }
                    };

                    // Link telp & email aman
                    $fmtTelp = function($v){
                        if (empty($v)) return '-';
                        $digits = preg_replace('/\D+/', '', $v);
                        return '<a class="underline" href="tel:'.$digits.'">'.e($v).'</a>';
                    };
                    $fmtEmail = function($v){
                        if (empty($v)) return '-';
                        return '<a class="underline" href="mailto:'.e($v).'">'.e($v).'</a>';
                    };
                @endphp

                {{-- Header ringkas --}}
                <div class="border rounded-lg p-4 bg-gray-50">
                    <div class="text-2xl font-semibold text-gray-900">
                        {{ $f->nama_perusahaan ?? '—' }}
                    </div>
                    <div class="text-sm text-gray-600 mt-1">
                        NIB: {{ $f->nib ?? '—' }} · KBLI: {{ $f->kbli ?? '—' }} · Risiko: {{ $f->tingkat_risiko ?? '—' }}
                    </div>
                </div>

                {{-- Biodata / Detail --}}
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">NIB</dt>
                        <dd class="mt-1 text-gray-900">{{ $f->nib ?? '—' }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Skala Usaha</dt>
                        <dd class="mt-1 text-gray-900">{{ $f->skala_usaha ?? '—' }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Jenis Perusahaan</dt>
                        <dd class="mt-1 text-gray-900">{{ $f->jenis_perusahaan ?? '—' }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Perusahaan</dt>
                        <dd class="mt-1 text-gray-900">{{ $f->nama_perusahaan ?? '—' }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Proyek</dt>
                        <dd class="mt-1 text-gray-900">{{ $f->nama_proyek ?? '—' }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Pemilik</dt>
                        <dd class="mt-1 text-gray-900">{{ $f->nama_pemilik ?? '—' }}</dd>
                    </div>

                    <div class="md:col-span-2">
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Alamat Usaha</dt>
                        <dd class="mt-1 text-gray-900">{{ $f->alamat_usaha ?? '—' }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Kecamatan</dt>
                        <dd class="mt-1 text-gray-900">{{ $f->kecamatan ?? '—' }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Kelurahan</dt>
                        <dd class="mt-1 text-gray-900">{{ $f->kelurahan ?? '—' }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">KBLI</dt>
                        <dd class="mt-1 text-gray-900">{{ $f->kbli ?? '—' }}</dd>
                    </div>

                    <div class="md:col-span-2">
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Uraian KBLI</dt>
                        <dd class="mt-1 text-gray-900">{{ $f->uraian_kbli ?? '—' }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Tingkat Risiko</dt>
                        <dd class="mt-1 text-gray-900">{{ $f->tingkat_risiko ?? '—' }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Jumlah Investasi</dt>
                        <dd class="mt-1 text-gray-900">{!! $fmtInvest($f->jumlah_investasi) !!}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Jumlah Tenaga Kerja</dt>
                        <dd class="mt-1 text-gray-900">{{ $f->jumlah_tenaga_kerja ?? '—' }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Nomor Telp</dt>
                        <dd class="mt-1 text-gray-900">{!! $fmtTelp($f->nomor_telp) !!}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Email</dt>
                        <dd class="mt-1 text-gray-900">{!! $fmtEmail($f->email) !!}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal Terbit</dt>
                        <dd class="mt-1 text-gray-900">{!! $fmtTanggal($f->tanggal_terbit) !!}</dd>
                    </div>

                    <div class="md:col-span-2">
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Sektor Pembina</dt>
                        <dd class="mt-1 text-gray-900">{{ $f->sektor_pembina ?? '—' }}</dd>
                    </div>
                </dl>

            </div>
        </div>
    </div>
</x-app-layout>
