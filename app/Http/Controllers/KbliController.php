<?php

namespace App\Http\Controllers;

use App\Models\PelakuIndustri;
use Illuminate\Http\Request;

class KbliController extends Controller
{
    public function index(Request $request)
    {
        $prefix = (string) $request->query('prefix', '');         // 3 digit
        $uraian = (string) $request->query('uraian_kbli', '');    // uraian lengkap
        $search = trim((string) $request->query('search', ''));

        // Ambil daftar prefix (3 digit) unik
        $prefixes = PelakuIndustri::query()
            ->whereNotNull('uraian_kbli')
            ->where('uraian_kbli', '<>', '')
            ->selectRaw('DISTINCT LEFT(TRIM(kbli),3) AS prefix')
            ->orderBy('prefix')
            ->pluck('prefix');

        // Ambil daftar uraian sesuai prefix (kalau dipilih)
        $uraianOptions = PelakuIndustri::query()
            ->whereNotNull('uraian_kbli')
            ->where('uraian_kbli', '<>', '')
            ->when($prefix !== '', fn ($q) =>
                $q->whereRaw('LEFT(TRIM(kbli),3) = ?', [$prefix])
            )
            ->selectRaw('DISTINCT TRIM(uraian_kbli) AS uraian_kbli')
            ->orderBy('uraian_kbli')
            ->pluck('uraian_kbli');

        // Base filter: pakai uraian jika dipilih, jika tidak dan prefix ada → pakai prefix
        $base = PelakuIndustri::query()
            ->when($uraian !== '', fn ($q) =>
                $q->whereRaw('TRIM(uraian_kbli) = ?', [$uraian])
            )
            ->when($uraian === '' && $prefix !== '', fn ($q) =>
                $q->whereRaw('LEFT(TRIM(kbli),3) = ?', [$prefix])
            );

        // Agregasi (berdasarkan base filter saja)
        $agg = (clone $base)->selectRaw("
            COUNT(*) AS total_perusahaan,
            COALESCE(SUM(COALESCE(NULLIF(jumlah_tenaga_kerja,''),0)+0),0) AS total_tk,
            COALESCE(SUM(COALESCE(NULLIF(jumlah_investasi,''),0)+0),0) AS total_investasi
        ")->first();

        // Skala usaha (berdasarkan base filter saja)
        $skala = (clone $base)->selectRaw("
            SUM(CASE WHEN LOWER(TRIM(skala_usaha)) REGEXP '^(mikro|usaha mikro|umk|u\\.?mikro)$' THEN 1 ELSE 0 END) AS mikro,
            SUM(CASE WHEN LOWER(TRIM(skala_usaha)) REGEXP '^(kecil|usaha kecil)$' THEN 1 ELSE 0 END) AS kecil,
            SUM(CASE WHEN LOWER(TRIM(skala_usaha)) REGEXP '^(menengah|usaha menengah|medium)$' THEN 1 ELSE 0 END) AS menengah,
            SUM(CASE WHEN LOWER(TRIM(skala_usaha)) REGEXP '^(besar|usaha besar)$' THEN 1 ELSE 0 END) AS besar
        ")->first();

        // Tabel baris (base + optional search)
        // Tabel baris (base + optional search)
$rowsQuery = (clone $base)
    ->select(
        'nama_perusahaan',
        'alamat_usaha',
        'uraian_kbli',
        'nomor_telp',      // <— tambah
        'email'        // <— tambah
    );

if ($search !== '') {
    $like = '%' . str_replace(['%', '_'], ['\\%','\\_'], $search) . '%';
    $rowsQuery->where(function ($q) use ($like) {
        $q->where('nama_perusahaan', 'like', $like)
          ->orWhere('alamat_usaha', 'like', $like)
          ->orWhereRaw('TRIM(uraian_kbli) LIKE ?', [$like])
          ->orWhere('nomor_telp', 'like', $like)      // <— tambah
          ->orWhere('email', 'like', $like);      // <— tambah
    });
}
        $rows = $rowsQuery
            ->orderBy('nama_perusahaan')
            ->paginate(20)
            ->withQueryString();

        return view('dasbor.kbli', [
            'prefixes' => $prefixes,
            'prefix'   => $prefix,
            'options'  => $uraianOptions,
            'selected' => $uraian,
            'rows'     => $rows,
            'agg'      => $agg,
            'skalaCounts' => [
                'Mikro'    => (int) ($skala->mikro ?? 0),
                'Kecil'    => (int) ($skala->kecil ?? 0),
                'Menengah' => (int) ($skala->menengah ?? 0),
                'Besar'    => (int) ($skala->besar ?? 0),
            ],
        ]);
    }
}
