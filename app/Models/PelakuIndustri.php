<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelakuIndustri extends Model
{
    use HasFactory;

    protected $table = 'pelaku_industri';

    protected $fillable = [
        'nib',
        'skala_usaha',
        'jenis_perusahaan',
        'nama_perusahaan',
        'nama_proyek',
        'nama_pemilik',
        'alamat_usaha',
        'kecamatan',
        'kelurahan',
        'kbli',
        'uraian_kbli',
        'tingkat_risiko',
        'jumlah_investasi',
        'jumlah_tenaga_kerja',
        'nomor_telp',
        'email',
        'tanggal_terbit',
        'sektor_pembina',
    ];
}
