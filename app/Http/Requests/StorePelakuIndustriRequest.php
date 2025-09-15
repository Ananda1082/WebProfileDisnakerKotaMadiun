<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePelakuIndustriRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
{
    return [
        'nib'                 => 'nullable|string|max:50',
        'skala_usaha'         => 'nullable|string|max:255',
        'jenis_perusahaan'    => 'nullable|string|max:255',
        'nama_perusahaan'     => 'nullable|string|max:255',
        'nama_proyek'         => 'nullable|string|max:255',
        'nama_pemilik'        => 'nullable|string|max:255',
        'alamat_usaha'        => 'nullable|string',
        'kecamatan'           => 'nullable|string|max:255',
        'kelurahan'           => 'nullable|string|max:255',
        'kbli'                => 'nullable|string|max:10',
        'uraian_kbli'         => 'nullable|string',
        'tingkat_risiko'      => 'nullable|string|max:255',
        'jumlah_investasi'    => 'nullable|numeric|min:0',
        'jumlah_tenaga_kerja' => 'nullable|integer|min:0',
        'nomor_telp'          => 'nullable|string|max:30',
        'email'               => 'nullable|email|max:255',
        'tanggal_terbit'      => 'nullable|string|max:255', // kolommu STRING
        'sektor_pembina'      => 'nullable|string|max:255',
    ];
}
}
