<?php

namespace App\Http\Controllers;

use App\Models\PelakuIndustri;
use Illuminate\Http\Request;
use App\Http\Requests\StorePelakuIndustriRequest;
use App\Http\Requests\UpdatePelakuIndustriRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

// PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

// Download response
use Symfony\Component\HttpFoundation\StreamedResponse;

class PelakuIndustriController extends Controller
{
    public function index(Request $request)
    {
        $q          = trim((string) $request->query('q', ''));
        $page       = max(1, (int) $request->integer('page', 1));
        $perPage    = (int) $request->integer('per_page', 20);
        if (!in_array($perPage, [10, 20, 50, 100], true)) {
            $perPage = 20;
        }

        // ====== FILTER PARAMS ======
        $f_skala        = trim((string) $request->query('skala_usaha', ''));
        $f_jenis        = trim((string) $request->query('jenis_perusahaan', ''));
        $f_kecamatan    = trim((string) $request->query('kecamatan', ''));
        $f_kelurahan    = trim((string) $request->query('kelurahan', ''));
        $f_kbli_prefix  = trim((string) $request->query('kbli_prefix', ''));   // 3 digit
        $f_uraian_kbli  = trim((string) $request->query('uraian_kbli', ''));
        $f_risiko       = trim((string) $request->query('tingkat_risiko', ''));
        $f_sektor       = trim((string) $request->query('sektor_pembina', ''));
        $f_tgl_from     = trim((string) $request->query('tgl_from', ''));
        $f_tgl_to       = trim((string) $request->query('tgl_to', ''));
        $f_invest_min   = $request->filled('invest_min') ? (float) str_replace(['.',','], ['','.'], $request->query('invest_min')) : null;
        $f_invest_max   = $request->filled('invest_max') ? (float) str_replace(['.',','], ['','.'], $request->query('invest_max')) : null;
        $f_tk_min       = $request->filled('tk_min') ? (int) $request->query('tk_min') : null;
        $f_tk_max       = $request->filled('tk_max') ? (int) $request->query('tk_max') : null;

        $columns = [
            'nib','skala_usaha','jenis_perusahaan','nama_perusahaan','nama_proyek','nama_pemilik',
            'alamat_usaha','kecamatan','kelurahan','kbli','uraian_kbli','tingkat_risiko',
            'jumlah_investasi','jumlah_tenaga_kerja','nomor_telp','email','tanggal_terbit','sektor_pembina',
        ];

        $query = PelakuIndustri::query();

        // ====== SEARCH BEBAS (q) ======
        if ($q !== '') {
            $terms = preg_split('/\s+/', \Illuminate\Support\Str::squish($q));
            $query->where(function ($outer) use ($terms, $columns) {
                foreach ($terms as $term) {
                    $outer->where(function ($inner) use ($term, $columns) {
                        foreach ($columns as $col) {
                            $inner->orWhere($col, 'LIKE', '%' . $term . '%');
                        }
                    });
                }
            });

            if (preg_match('/^\d{10,}$/', $q)) {
                $query->orWhere('nib', $q);
            }
            if (filter_var($q, FILTER_VALIDATE_EMAIL)) {
                $query->orWhere('email', $q);
            }
            $digits = preg_replace('/\D+/', '', $q);
            if (strlen($digits) >= 7) {
                $query->orWhereRaw("
                    REPLACE(REPLACE(REPLACE(COALESCE(nomor_telp, ''), ' ', ''), '-', ''), '+', '') LIKE ?
                ", ["%{$digits}%"]);
            }
            // parse tanggal sederhana
            $date = null;
            foreach (['Y-m-d', 'd/m/Y', 'd-m-Y', 'd.m.Y'] as $fmt) {
                try {
                    $d = \Carbon\Carbon::createFromFormat($fmt, $q);
                    if ($d !== false) { $date = $d; break; }
                } catch (\Exception $e) {}
            }
            if ($date) {
                $query->orWhereDate('tanggal_terbit', $date->toDateString());
            }
        }

        // ====== FILTER KHUSUS (form di bawah search) ======
        $query
            ->when($f_skala   !== '', fn($qb) => $qb->where('skala_usaha', $f_skala))
            ->when($f_jenis   !== '', fn($qb) => $qb->where('jenis_perusahaan', $f_jenis))
            ->when($f_kecamatan !== '', fn($qb) => $qb->where('kecamatan', $f_kecamatan))
            ->when($f_kelurahan !== '', fn($qb) => $qb->where('kelurahan', $f_kelurahan))
            ->when($f_kbli_prefix !== '', fn($qb) =>
                $qb->whereRaw('LEFT(TRIM(kbli),3) = ?', [$f_kbli_prefix])
            )
            ->when($f_uraian_kbli !== '', fn($qb) =>
                $qb->whereRaw('TRIM(uraian_kbli) = ?', [$f_uraian_kbli])
            )
            ->when($f_risiko !== '', fn($qb) => $qb->where('tingkat_risiko', $f_risiko))
            ->when($f_sektor !== '', fn($qb) => $qb->where('sektor_pembina', $f_sektor))
            ->when($f_tgl_from !== '' && $f_tgl_to !== '', fn($qb) =>
                $qb->whereBetween('tanggal_terbit', [
                    \Carbon\Carbon::parse($f_tgl_from)->startOfDay(),
                    \Carbon\Carbon::parse($f_tgl_to)->endOfDay()
                ])
            )
            ->when($f_tgl_from !== '' && $f_tgl_to === '', fn($qb) =>
                $qb->whereDate('tanggal_terbit', '>=', \Carbon\Carbon::parse($f_tgl_from)->toDateString())
            )
            ->when($f_tgl_from === '' && $f_tgl_to !== '', fn($qb) =>
                $qb->whereDate('tanggal_terbit', '<=', \Carbon\Carbon::parse($f_tgl_to)->toDateString())
            )
            ->when(!is_null($f_invest_min), fn($qb) =>
                $qb->whereRaw('COALESCE(NULLIF(jumlah_investasi, \'\'),0)+0 >= ?', [$f_invest_min])
            )
            ->when(!is_null($f_invest_max), fn($qb) =>
                $qb->whereRaw('COALESCE(NULLIF(jumlah_investasi, \'\'),0)+0 <= ?', [$f_invest_max])
            )
            ->when(!is_null($f_tk_min), fn($qb) =>
                $qb->whereRaw('COALESCE(NULLIF(jumlah_tenaga_kerja, \'\'),0)+0 >= ?', [$f_tk_min])
            )
            ->when(!is_null($f_tk_max), fn($qb) =>
                $qb->whereRaw('COALESCE(NULLIF(jumlah_tenaga_kerja, \'\'),0)+0 <= ?', [$f_tk_max])
            );

        // Order yang stabil
        $query->when(
            \Schema::hasColumn((new PelakuIndustri)->getTable(), 'created_at'),
            fn($q2) => $q2->orderByDesc('created_at'),
            fn($q2) => $q2->orderByDesc('id')
        );

        // ====== OPTIONS utk dropdown (unique) ======
        // batasi agar tidak berat; urut alfabet
        $optSkala     = PelakuIndustri::query()->select('skala_usaha')->whereNotNull('skala_usaha')->where('skala_usaha','<>','')->distinct()->orderBy('skala_usaha')->pluck('skala_usaha');
        $optJenis     = PelakuIndustri::query()->select('jenis_perusahaan')->whereNotNull('jenis_perusahaan')->where('jenis_perusahaan','<>','')->distinct()->orderBy('jenis_perusahaan')->pluck('jenis_perusahaan');
        $optKec       = PelakuIndustri::query()->select('kecamatan')->whereNotNull('kecamatan')->where('kecamatan','<>','')->distinct()->orderBy('kecamatan')->pluck('kecamatan');
        $optKel       = PelakuIndustri::query()->select('kelurahan')->whereNotNull('kelurahan')->where('kelurahan','<>','')->distinct()->orderBy('kelurahan')->pluck('kelurahan');
        $optRisiko    = PelakuIndustri::query()->select('tingkat_risiko')->whereNotNull('tingkat_risiko')->where('tingkat_risiko','<>','')->distinct()->orderBy('tingkat_risiko')->pluck('tingkat_risiko');
        $optSektor    = PelakuIndustri::query()->select('sektor_pembina')->whereNotNull('sektor_pembina')->where('sektor_pembina','<>','')->distinct()->orderBy('sektor_pembina')->pluck('sektor_pembina');
        $optKbliPref  = PelakuIndustri::query()->selectRaw('DISTINCT LEFT(TRIM(kbli),3) AS pref')->orderBy('pref')->pluck('pref');
        $optUraian    = PelakuIndustri::query()
                          ->when($f_kbli_prefix !== '', fn($qU) => $qU->whereRaw('LEFT(TRIM(kbli),3) = ?', [$f_kbli_prefix]))
                          ->select('uraian_kbli')
                          ->whereNotNull('uraian_kbli')->where('uraian_kbli','<>','')
                          ->distinct()->orderBy('uraian_kbli')->pluck('uraian_kbli');

        // Paginate
        $items = $query->paginate($perPage, ['*'], 'page', $page)->withQueryString();

        if ($items->isEmpty() && $items->lastPage() > 0 && $page > $items->lastPage()) {
            return redirect()->route('pelaku-industri.index', array_merge(
                $request->except('page'),
                ['page' => $items->lastPage()]
            ));
        }

        return view('pelaku-industri.index', compact(
            'items','q','perPage',
            'f_skala','f_jenis','f_kecamatan','f_kelurahan','f_kbli_prefix','f_uraian_kbli',
            'f_risiko','f_sektor','f_tgl_from','f_tgl_to','f_invest_min','f_invest_max','f_tk_min','f_tk_max',
            'optSkala','optJenis','optKec','optKel','optRisiko','optSektor','optKbliPref','optUraian'
        ));
    }

    public function create()
    {
        $pelakuIndustri = new PelakuIndustri();
        return view('pelaku-industri.create', compact('pelakuIndustri'));
    }

    public function store(StorePelakuIndustriRequest $request)
    {
        PelakuIndustri::create($request->validated());
        return redirect()->route('pelaku-industri.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function show(PelakuIndustri $pelakuIndustri)
    {
        return view('pelaku-industri.show', compact('pelakuIndustri'));
    }

    public function edit(PelakuIndustri $pelakuIndustri)
    {
        return view('pelaku-industri.edit', compact('pelakuIndustri'));
    }

    public function update(UpdatePelakuIndustriRequest $request, PelakuIndustri $pelakuIndustri)
    {
        $pelakuIndustri->update($request->validated());
        return redirect()->route('pelaku-industri.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(PelakuIndustri $pelakuIndustri)
    {
        $pelakuIndustri->delete();
        return redirect()->route('pelaku-industri.index')->with('success', 'Data berhasil dihapus.');
    }

    /**
     * Helper: normalisasi & hash semua kolom penting
     */
    private function rowHash(array $row): string
    {
        $ordered = [
            'nib','skala_usaha','jenis_perusahaan','nama_perusahaan','nama_proyek','nama_pemilik',
            'alamat_usaha','kecamatan','kelurahan','kbli','uraian_kbli','tingkat_risiko',
            'jumlah_investasi','jumlah_tenaga_kerja','nomor_telp','email','tanggal_terbit','sektor_pembina',
        ];
        $vals = [];
        foreach ($ordered as $k) {
            $v = $row[$k] ?? '';
            $v = is_null($v) ? '' : trim((string)$v);
            $vals[] = $v;
        }
        return md5(implode('|', $vals));
    }

    /**
     * Helper: parse tanggal dari berbagai format:
     * - Angka serial Excel (1900-based)
     * - Teks dengan nama bulan Indonesia/Inggris (mis. "10 Januari 2024", "10-Des-24", "10 Sep 2024")
     * - Pola numerik umum (Y-m-d, d/m/Y, dst.)
     * Return: 'Y-m-d' atau null bila gagal.
     */
    private function parseTanggalTerbit($raw): ?string
    {
        $Q = trim((string)($raw ?? ''));
        if ($Q === '') return null;

        // 1) Serial Excel (dipakai bila cell tipe Date)
        if (is_numeric($Q)) {
            $num = (int)$Q;
            if ($num > 25000 && $num < 60000) { // guard kasar supaya tidak salah deteksi
                try {
                    return ExcelDate::excelToDateTimeObject($num)->format('Y-m-d');
                } catch (\Throwable $e) {
                    // lanjut ke parsing string
                }
            }
        }

        // 2) Ganti nama bulan (ID/EN singkat) → angka
        $bulanMap = [
            'januari'=>'01','jan'=>'01',
            'februari'=>'02','feb'=>'02',
            'maret'=>'03','mar'=>'03',
            'april'=>'04','apr'=>'04',
            'mei'=>'05',
            'juni'=>'06','jun'=>'06',
            'juli'=>'07','jul'=>'07',
            'agustus'=>'08','agu'=>'08','ags'=>'08','aug'=>'08',
            'september'=>'09','sep'=>'09',
            'oktober'=>'10','okt'=>'10','oct'=>'10',
            'november'=>'11','nov'=>'11',
            'desember'=>'12','des'=>'12','dec'=>'12',
        ];
        $low = mb_strtolower($Q, 'UTF-8');
        foreach ($bulanMap as $name => $num) {
            $low = preg_replace('/\b'.$name.'\b/u', $num, $low);
        }
        // Samakan pemisah ke '-'
        $norm = preg_replace('~[./\s]+~', '-', trim($low, "- ")); // "10 januari 2024" → "10-01-2024"

        // 3) Coba beberapa pola umum (termasuk 2-digit year)
        $patterns = [
            'Y-m-d','Y-n-j','Y/m/d',
            'd-m-Y','d/m/Y','d.m.Y',
            'd-m-y','d/m/y','d.m.y','j-n-y',
            'm-d-Y','m/d/Y','n/j/Y',
        ];
        foreach ($patterns as $fmt) {
            try {
                $dt = Carbon::createFromFormat($fmt, $norm);
                if ($dt !== false) {
                    if ((stripos($fmt, 'y') !== false) && $dt->year < 100) {
                        $dt->year += ($dt->year >= 70 ? 1900 : 2000);
                    }
                    return $dt->format('Y-m-d');
                }
            } catch (\Throwable $e) {
                // lanjut pola berikutnya
            }
        }

        // 4) Fallback bebas
        try {
            return Carbon::parse($Q)->format('Y-m-d');
        } catch (\Throwable $e) {}

        return null;
    }

    /**
     * Import Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required','file','mimes:xlsx,xls']
        ]);

        $file  = $request->file('file');
        $sheet = IOFactory::load($file->getRealPath())->getActiveSheet();

        // Penting: formatData = false agar dapat RAW (angka serial tetap angka)
        // toArray(null, calculateFormulas=true, formatData=false, returnCellRef=true)
        $rows  = $sheet->toArray(null, true, false, true);
        if (empty($rows)) {
            return back()->withErrors(['file'=>'File kosong / tidak terbaca.']);
        }

        $startRow  = 2;                  // baris data pertama (1 = header)
        $keyCols   = ['nib','kbli'];     // kunci gabungan untuk cek existing

        $fileSeenHashes = [];     // deteksi duplikat antar-baris di file (identik)
        $parsed = [];             // baris hasil parsing (siap diproses)
        $keyValues = [];          // kumpulan nilai kunci untuk query bulk ke DB

        // ---- 1) Parse Excel + normalisasi + hash
        for ($r = $startRow; $r <= count($rows); $r++) {
            $A = trim((string)($rows[$r]['A'] ?? '')); // nib
            $J = strtoupper(trim((string)($rows[$r]['J'] ?? ''))); // kbli

            // skip baris kosong (tanpa nib+kbli+nama perusahaan)
            if ($A === '' && $J === '' && trim((string)($rows[$r]['D'] ?? '')) === '') continue;

            // Ambil raw Q langsung dari cell agar lebih robust (bisa angka serial atau teks)
            $cellQ = $sheet->getCell('Q'.$r);
            $rawQ  = $cellQ ? $cellQ->getValue() : ($rows[$r]['Q'] ?? null);

            $data = [
                'nib'                 => preg_replace('/\s+/', '', $A) ?: null,
                'skala_usaha'         => trim((string)($rows[$r]['B'] ?? '')) ?: null,
                'jenis_perusahaan'    => trim((string)($rows[$r]['C'] ?? '')) ?: null,
                'nama_perusahaan'     => trim((string)($rows[$r]['D'] ?? '')) ?: null,
                'nama_proyek'         => trim((string)($rows[$r]['E'] ?? '')) ?: null,
                'nama_pemilik'        => trim((string)($rows[$r]['F'] ?? '')) ?: null,
                'alamat_usaha'        => trim((string)($rows[$r]['G'] ?? '')) ?: null,
                'kecamatan'           => trim((string)($rows[$r]['H'] ?? '')) ?: null,
                'kelurahan'           => trim((string)($rows[$r]['I'] ?? '')) ?: null,
                'kbli'                => $J ?: null,
                'uraian_kbli'         => trim((string)($rows[$r]['K'] ?? '')) ?: null,
                'tingkat_risiko'      => trim((string)($rows[$r]['L'] ?? '')) ?: null,
                'jumlah_investasi'    => ($m = trim((string)($rows[$r]['M'] ?? ''))) !== '' ? (int)preg_replace('/[^\d]/','',$m) : null,
                'jumlah_tenaga_kerja' => ($n = trim((string)($rows[$r]['N'] ?? ''))) !== '' ? (int)$n : null,
                'nomor_telp'          => trim((string)($rows[$r]['O'] ?? '')) ?: null,
                'email'               => trim((string)($rows[$r]['P'] ?? '')) ?: null,
                'tanggal_terbit'      => $this->parseTanggalTerbit($rawQ),
                'sektor_pembina'      => trim((string)($rows[$r]['R'] ?? '')) ?: null,
            ];

            $hash = $this->rowHash($data);
            if (isset($fileSeenHashes[$hash])) {
                // identik dengan baris sebelumnya di file → skip
                continue;
            }
            $fileSeenHashes[$hash] = true;

            $data['_hash'] = $hash;
            $data['_key']  = implode('|', array_map(fn($k)=>$data[$k] ?? '', $keyCols));
            $parsed[] = $data;

            $keyValues[] = $data['_key'];
        }

        if (!$parsed) {
            return back()->with('success', 'Tidak ada baris baru (semua duplikat di file).');
        }

        // ---- 2) Ambil data eksisting untuk semua kunci di file (sekali query)
        $pairs = array_unique($keyValues);
        $mapExisting = []; // '_key' => ['model'=>PelakuIndustri, 'hash'=>...]
        if ($pairs) {
            $conditions = [];
            foreach ($pairs as $pair) {
                [$nibKey,$kbliKey] = explode('|', $pair) + [null,null];
                $conditions[] = ['nib'=>$nibKey ?: null, 'kbli'=>$kbliKey ?: null];
            }

            PelakuIndustri::query()
                ->where(function($q) use ($conditions) {
                    foreach ($conditions as $i=>$c) {
                        $q->orWhere(function($qq) use ($c){
                            $qq->where('nib',  $c['nib'])
                               ->where('kbli', $c['kbli']);
                        });
                    }
                })
                ->get()
                ->each(function($m) use (&$mapExisting){
                    $row = $m->only([
                        'nib','skala_usaha','jenis_perusahaan','nama_perusahaan','nama_proyek','nama_pemilik',
                        'alamat_usaha','kecamatan','kelurahan','kbli','uraian_kbli','tingkat_risiko',
                        'jumlah_investasi','jumlah_tenaga_kerja','nomor_telp','email','tanggal_terbit','sektor_pembina',
                    ]);
                    $hash = $this->rowHash($row);
                    $key  = ($row['nib'] ?? '').'|'.($row['kbli'] ?? '');
                    $mapExisting[$key] = ['model'=>$m,'hash'=>$hash];
                });
        }

        // ---- 3) Tentukan aksi per baris: SKIP identik, UPDATE jika beda, INSERT jika belum ada
        $toInsert = [];
        $toUpdate = [];
        $now = now();
        foreach ($parsed as $data) {
            $key = $data['_key'];
            $curHash = $data['_hash'];
            unset($data['_key'], $data['_hash']);

            if (isset($mapExisting[$key])) {
                if ($mapExisting[$key]['hash'] === $curHash) {
                    // Sama persis → SKIP
                    continue;
                }
                // Ada tapi beda → UPDATE
                $data['updated_at'] = $now;
                $data['id'] = $mapExisting[$key]['model']->id; // untuk bulk update manual
                $toUpdate[] = $data;
            } else {
                // Belum ada → INSERT
                $data['created_at'] = $now;
                $data['updated_at'] = $now;
                $toInsert[] = $data;
            }
        }

        // ---- 4) Eksekusi DB (bulk)
        if ($toInsert) {
            foreach (array_chunk($toInsert, 1000) as $chunk) {
                PelakuIndustri::insert($chunk);
            }
        }
        if ($toUpdate) {
            foreach (array_chunk($toUpdate, 1000) as $chunk) {
                PelakuIndustri::upsert(
                    $chunk,
                    ['id'], // unique by
                    [
                        'skala_usaha','jenis_perusahaan','nama_perusahaan','nama_proyek','nama_pemilik',
                        'alamat_usaha','kecamatan','kelurahan','uraian_kbli','tingkat_risiko',
                        'jumlah_investasi','jumlah_tenaga_kerja','nomor_telp','email','tanggal_terbit','sektor_pembina','updated_at'
                    ]
                );
            }
        }

        $msg = sprintf(
            "Import selesai. Insert: %d, Update: %d, Skip identik (file atau DB): %d",
            count($toInsert),
            count($toUpdate),
            count($parsed) - count($toInsert) - count($toUpdate)
        );
        return back()->with('success', $msg);
    }

    /**
     * Unduh template Excel (urutan kolom yang diharapkan impor positional).
     */
    public function template()
    {
        $headers = [
            'NIB',
            'Skala Usaha',
            'Jenis Perusahaan',
            'Nama Perusahaan',
            'Nama Proyek',
            'Nama Pemilik',
            'Alamat Usaha',
            'Kecamatan',
            'Kelurahan',
            'KBLI',
            'Uraian KBLI',
            'Tingkat Risiko',
            'Jumlah Investasi',
            'Jumlah Tenaga Kerja',
            'Nomor Telp',
            'Email',
            'Tanggal Terbit',
            'Sektor Pembina',
        ];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        foreach ($headers as $i => $text) {
            $sheet->setCellValueByColumnAndRow($i + 1, 1, $text);
        }

        // Contoh baris (opsional)
        $sheet->setCellValue('A2', '1234567890123');
        $sheet->setCellValue('B2', 'Menengah');
        $sheet->setCellValue('C2', 'PT');
        $sheet->setCellValue('D2', 'PT Contoh Maju');
        $sheet->setCellValue('E2', 'Pabrik A');
        $sheet->setCellValue('F2', 'Budi');
        $sheet->setCellValue('G2', 'Jl. Mawar No. 1');
        $sheet->setCellValue('H2', 'Cimahi Tengah');
        $sheet->setCellValue('I2', 'Cigugur');
        $sheet->setCellValue('J2', '10740');
        $sheet->setCellValue('K2', 'Industri makanan lainnya');
        $sheet->setCellValue('L2', 'Rendah');
        $sheet->setCellValue('M2', 100000000);
        $sheet->setCellValue('N2', 25);
        $sheet->setCellValue('O2', '081234567890');
        $sheet->setCellValue('P2', 'email@contoh.com');
        // Set contoh tanggal sebagai tanggal (serial), bukan string
        $sheet->setCellValue('Q2', \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(time()));
        $sheet->getStyle('Q2')->getNumberFormat()->setFormatCode('dd mmmm yyyy');
        $sheet->setCellValue('R2', 'Perindustrian');

        // Auto width
        foreach (range(1, count($headers)) as $colIndex) {
            $sheet->getColumnDimensionByColumn($colIndex)->setAutoSize(true);
        }

        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $filename = 'template_pelaku_industri.xlsx';
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', "attachment; filename=\"{$filename}\"");

        return $response;
    }

    public function publicIndex(Request $request)
    {
        $q = trim($request->get('q', ''));

        $items = PelakuIndustri::query()
            ->select('id','nama_perusahaan','alamat_usaha','nomor_telp')
            ->when($q, function ($qb) use ($q) {
                $qb->where(function ($w) use ($q) {
                    $w->where('nama_perusahaan', 'like', "%{$q}%")
                      ->orWhere('alamat_usaha', 'like', "%{$q}%")
                      ->orWhere('nomor_telp', 'like', "%{$q}%");
                });
            })
            ->orderBy('nama_perusahaan')
            ->paginate(20)
            ->withQueryString();

        return view('data-pelaku', compact('items','q'));
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids'   => ['required','array','min:1'],
            'ids.*' => ['integer','distinct']
        ], [
            'ids.required' => 'Tidak ada data yang dipilih.',
            'ids.array'    => 'Format pilihan tidak valid.',
            'ids.min'      => 'Pilih minimal satu data.',
        ]);

        $ids = array_map('intval', $validated['ids']);

        $existsCount = PelakuIndustri::query()->whereIn('id', $ids)->count();
        if ($existsCount === 0) {
            return back()->with('success', 'Tidak ada data yang cocok untuk dihapus.');
        }

        DB::transaction(function () use ($ids) {
            PelakuIndustri::query()->whereIn('id', $ids)->delete();
        });

        return back()->with('success', "Berhasil menghapus {$existsCount} data terpilih.");
    }
}
