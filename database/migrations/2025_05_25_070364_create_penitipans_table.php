<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penitipans', function (Blueprint $table) {
            $table->id('id_penitipan');
            $table->date('tgl_penitipan');
            $table->date('tgl_kadaluarsa');
            $table->date('tgl_pengembalian');
            $table->boolean('konfirmasi_perpanjangan')->default(false);
            $table->string('nama_QC');

            $table->unsignedBigInteger('id_pegawai');
            $table->unsignedBigInteger('id_barang');
            $table->unsignedBigInteger('id_penitip');
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais')->onDelete('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('barangs')->onDelete('cascade');
            $table->foreign('id_penitip')->references('id_penitip')->on('penitips')->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penitipans');
    }
};
