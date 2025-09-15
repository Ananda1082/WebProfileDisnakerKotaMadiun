<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelaku_industri', function (Blueprint $table) {
            $table->id();
            $table->string('nib', 50)->nullable();
            $table->string('skala_usaha')->nullable();
            $table->string('jenis_perusahaan')->nullable();
            $table->string('nama_perusahaan')->nullable();
            $table->string('nama_proyek')->nullable();
            $table->string('nama_pemilik')->nullable();
            $table->string('alamat_usaha')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kbli', 10)->nullable();
            $table->text('uraian_kbli')->nullable();
            $table->string('tingkat_risiko')->nullable();
            $table->bigInteger('jumlah_investasi')->nullable();
            $table->integer('jumlah_tenaga_kerja')->nullable();
            $table->string('nomor_telp', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('tanggal_terbit')->nullable();
            $table->string('sektor_pembina')->nullable();
            $table->timestamps();

            $table->index(['kbli', 'kecamatan', 'sektor_pembina']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelaku_industri');
    }
};
