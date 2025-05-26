<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->string('id_barang')->unique();
            $table->string('nama_barang');
            $table->string('status_garansi');
            $table->date('tgl_garansi');
            $table->float('harga_barang');
            $table->string('status_barang');
            $table->integer('rating_barang');
            $table->date('tgl_didonasikan');

            $table->string('id_pegawai');
            $table->string('id_penitip');
            $table->string('id_kategori');
            $table->string('id_pembelian');
            $table->string('id_donasi');
            $table->timestamps();

            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais')->onDelete('cascade');
            $table->foreign('id_penitip')->references('id_penitip')->on('penitips')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategoris')->onDelete('cascade');
            $table->foreign('id_pembelian')->references('id_pembelian')->on('pembelians')->onDelete('cascade');
            $table->foreign('id_donasi')->references('id_donasi')->on('donasis')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
