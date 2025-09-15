<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Hitung DISTINCT nama_perusahaan
        $jumlahPerusahaan = DB::table('pelaku_industri')
            ->whereNotNull('nama_perusahaan')
            ->where('nama_perusahaan', '<>', '')
            ->distinct()
            ->count('nama_perusahaan');

        // 2. Hitung DISTINCT nib
        $jumlahPelaku = DB::table('pelaku_industri')
            ->whereNotNull('nib')
            ->where('nib', '<>', '')
            ->distinct()
            ->count('nib');

        // 3. Hitung jumlah usaha (total baris)
        $jumlahUsaha = DB::table('pelaku_industri')->count();



        return view('welcome', compact('jumlahPerusahaan', 'jumlahPelaku', 'jumlahUsaha'));
    }
}
