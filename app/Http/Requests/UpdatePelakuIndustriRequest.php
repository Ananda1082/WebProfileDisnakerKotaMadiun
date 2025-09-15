<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePelakuIndustriRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('pelaku_industri')->id ?? null; // via route model binding

        return [
    'nib' => [
        'required',
        'string',
        'max:50',
    ],
    'skala_usaha' => 'nullable|string',
    'jenis_perusahaan' => 'nullable|string',
    'nama_perusahaan' => 'nullable|string|max:255',
    'nama_proyek' => 'nullable|string|max:255',
    'nama_pemilik' => 'nullable|string|max:255',
    'alamat_usaha' => 'nullable|string',
    'kecamatan' => 'nullable|string|max:100',
    'kelurahan' => 'nullable|string|max:100',
    'kbli' => 'nullable|string|max:10',
    'uraian_kbli' => 'nullable|text',
    'tingkat_risiko' => 'nullable|string',
    'jumlah_investasi' => 'nullable|bigInteger|min:0',
    'jumlah_tenaga_kerja' => 'nullable|integer|min:0',
    'nomor_telp' => 'nullable|string|max:30',
    'email' => 'nullable|email',
    'tanggal_terbit' => 'nullable|string',
    'sektor_pembina' => 'nullable|string|max:255',
];

    }
}
