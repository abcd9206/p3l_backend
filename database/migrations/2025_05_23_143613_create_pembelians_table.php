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
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id('id_pembelian');
            $table->integer('jml_barang');
            $table->string('metode_pembayaran');
            $table->float('total_pembelian');
            $table->string('status_pembayaran');
            $table->string('foto_buktiPembayaran');
            $table->boolean('verifikasi_pembayaran')->default(false);
            $table->date('tgl_checkout');
            $table->date('tgl_lunas');
            $table->date('tgl_pembelian');
            $table->date('tgl_selesai');
            $table->date('tgl_pengambilan');


            $table->unsignedBigInteger('id_pembeli');
            $table->unsignedBigInteger('id_pegawai');
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais')->onDelete('cascade');
            $table->foreign('id_pembeli')->references('id_pembeli')->on('pembelis')->onDelete('cascade');
            $table->timestamps();
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
