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
        Schema::create('pembelians', function (Blueprint $table) {
            $table->string('id_pembelian')->unique();
            $table->integer('jml_barang');
            $table->string('metode_pembayaran');
            $table->float('total_pembelian');
            $table->string('status_pembayaran');
            $table->string('foto_buktiPembayaran');
            $table->string('verifikasi_pembayaran');
            $table->string('status_pembelian');
            $table->date('tgl_pembelian');
            $table->date('tgl_selesai');
            $table->date('tgl_pengambilan');
            $table->string('status_proses');

            $table->string('id_pegawai');
            $table->string('id_pembeli');
            $table->timestamps();

            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais')->onDelete('cascade');
            $table->foreign('id_pembeli')->references('id_pembeli')->on('pembelis')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelians');
    }
};
