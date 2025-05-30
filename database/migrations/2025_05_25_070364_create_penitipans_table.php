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
            $table->integer('id_penitipan')->unique();
            $table->date('tgl_penitipan');
            $table->date('tgl_kadaluarsa');
            $table->date('tgl_pengembalian');
            $table->string('status_penitipan');
            $table->boolean('konfirmasi_perpanjangan')->default(false);
            $table->string('nama_QC');

            $table->integer('id_pegawai');
            $table->integer('id_barang');
            $table->integer('id_penitip');
            $table->timestamps();

            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais')->onDelete('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('barangs')->onDelete('cascade');
            $table->foreign('id_penitip')->references('id_penitip')->on('penitips')->onDelete('cascade');
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
