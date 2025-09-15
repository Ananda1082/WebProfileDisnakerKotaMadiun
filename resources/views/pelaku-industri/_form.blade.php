<title>Input Data Pelaku Industri</title>
@php($isEdit = isset($pelakuIndustri) && $pelakuIndustri && $pelakuIndustri->exists)
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    {{-- NIB (varchar 50, nullable) --}}
    <div>
        <label class="block text-sm font-medium">NIB</label>
        <input name="nib" maxlength="50"
               value="{{ old('nib', $pelakuIndustri->nib ?? '') }}"
               class="border rounded w-full px-3 py-2">
        @error('nib') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    {{-- Skala Usaha (varchar, nullable) --}}
    <div>
        <label class="block text-sm font-medium">Skala Usaha</label>
        <input name="skala_usaha"
               value="{{ old('skala_usaha', $pelakuIndustri->skala_usaha ?? '') }}"
               class="border rounded w-full px-3 py-2" placeholder="Usaha Mikro/Kecil/Menengah/Besar">
        @error('skala_usaha') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    {{-- Jenis Perusahaan (varchar, nullable) --}}
    <div>
        <label class="block text-sm font-medium">Jenis Perusahaan</label>
        <input name="jenis_perusahaan"
               value="{{ old('jenis_perusahaan', $pelakuIndustri->jenis_perusahaan ?? '') }}"
               class="border rounded w-full px-3 py-2" placeholder="PT/CV/Firma/Perorangan/...">
        @error('jenis_perusahaan') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    {{-- Nama Perusahaan (varchar, nullable) --}}
    <div>
        <label class="block text-sm font-medium">Nama Perusahaan</label>
        <input name="nama_perusahaan"
               value="{{ old('nama_perusahaan', $pelakuIndustri->nama_perusahaan ?? '') }}"
               class="border rounded w-full px-3 py-2">
        @error('nama_perusahaan') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    {{-- Nama Proyek (varchar, nullable) --}}
    <div>
        <label class="block text-sm font-medium">Nama Proyek</label>
        <input name="nama_proyek"
               value="{{ old('nama_proyek', $pelakuIndustri->nama_proyek ?? '') }}"
               class="border rounded w-full px-3 py-2">
        @error('nama_proyek') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    {{-- Nama Pemilik (varchar, nullable) --}}
    <div>
        <label class="block text-sm font-medium">Nama Pemilik</label>
        <input name="nama_pemilik"
               value="{{ old('nama_pemilik', $pelakuIndustri->nama_pemilik ?? '') }}"
               class="border rounded w-full px-3 py-2">
        @error('nama_pemilik') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    {{-- Alamat Usaha (varchar, nullable) -> pakai textarea untuk kenyamanan input panjang --}}
    <div class="md:col-span-2">
        <label class="block text-sm font-medium">Alamat Usaha</label>
        <textarea name="alamat_usaha" rows="3"
                  class="border rounded w-full px-3 py-2">{{ old('alamat_usaha', $pelakuIndustri->alamat_usaha ?? '') }}</textarea>
        @error('alamat_usaha') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    {{-- Kecamatan (varchar, nullable) --}}
    <div>
        <label class="block text-sm font-medium">Kecamatan</label>
        <input name="kecamatan"
               value="{{ old('kecamatan', $pelakuIndustri->kecamatan ?? '') }}"
               class="border rounded w-full px-3 py-2">
        @error('kecamatan') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    {{-- Kelurahan (varchar, nullable) --}}
    <div>
        <label class="block text-sm font-medium">Kelurahan</label>
        <input name="kelurahan"
               value="{{ old('kelurahan', $pelakuIndustri->kelurahan ?? '') }}"
               class="border rounded w-full px-3 py-2">
        @error('kelurahan') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    {{-- KBLI (varchar 10, nullable) --}}
    <div>
        <label class="block text-sm font-medium">KBLI</label>
        <input name="kbli" maxlength="10"
               value="{{ old('kbli', $pelakuIndustri->kbli ?? '') }}"
               class="border rounded w-full px-3 py-2">
        @error('kbli') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    {{-- Uraian KBLI (text, nullable) --}}
    <div class="md:col-span-2">
        <label class="block text-sm font-medium">Uraian KBLI</label>
        <textarea name="uraian_kbli" rows="3"
                  class="border rounded w-full px-3 py-2">{{ old('uraian_kbli', $pelakuIndustri->uraian_kbli ?? '') }}</textarea>
        @error('uraian_kbli') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    {{-- Tingkat Risiko (varchar, nullable) --}}
    <div>
        <label class="block text-sm font-medium">Tingkat Risiko</label>
        <input name="tingkat_risiko"
               value="{{ old('tingkat_risiko', $pelakuIndustri->tingkat_risiko ?? '') }}"
               class="border rounded w-full px-3 py-2" placeholder="Rendah / Menengah Rendah / Menengah Tinggi / Tinggi">
        @error('tingkat_risiko') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    {{-- Jumlah Investasi (bigInteger, nullable) --}}
    <div>
        <label class="block text-sm font-medium">Jumlah Investasi (Rp)</label>
        <input type="number" name="jumlah_investasi" min="0" step="1"
               value="{{ old('jumlah_investasi', $pelakuIndustri->jumlah_investasi ?? '') }}"
               class="border rounded w-full px-3 py-2">
        @error('jumlah_investasi') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    {{-- Jumlah Tenaga Kerja (integer, nullable) --}}
    <div>
        <label class="block text-sm font-medium">Jumlah Tenaga Kerja</label>
        <input type="number" name="jumlah_tenaga_kerja" min="0" step="1"
               value="{{ old('jumlah_tenaga_kerja', $pelakuIndustri->jumlah_tenaga_kerja ?? '') }}"
               class="border rounded w-full px-3 py-2">
        @error('jumlah_tenaga_kerja') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    {{-- Nomor Telp (varchar 30, nullable) --}}
    <div>
        <label class="block text-sm font-medium">Nomor Telp</label>
        <input name="nomor_telp" maxlength="30" inputmode="tel"
               value="{{ old('nomor_telp', $pelakuIndustri->nomor_telp ?? '') }}"
               class="border rounded w-full px-3 py-2" placeholder="+62… / 08…">
        @error('nomor_telp') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    {{-- Email (varchar, nullable) --}}
    <div>
        <label class="block text-sm font-medium">Email</label>
        <input type="email" name="email"
               value="{{ old('email', $pelakuIndustri->email ?? '') }}"
               class="border rounded w-full px-3 py-2">
        @error('email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    {{-- Tanggal Terbit (string, nullable) --}}
    <div>
        <label class="block text-sm font-medium">Tanggal Terbit</label>
        <input type="text" name="tanggal_terbit"
               value="{{ old('tanggal_terbit', $pelakuIndustri->tanggal_terbit ?? '') }}"
               class="border rounded w-full px-3 py-2"
               placeholder="Contoh: 2024-12-31 atau 31/12/2024">
        @error('tanggal_terbit') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    {{-- Sektor Pembina (varchar, nullable) --}}
    <div class="md:col-span-2">
        <label class="block text-sm font-medium">Sektor Pembina</label>
        <input name="sektor_pembina"
               value="{{ old('sektor_pembina', $pelakuIndustri->sektor_pembina ?? '') }}"
               class="border rounded w-full px-3 py-2">
        @error('sektor_pembina') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

</div>
