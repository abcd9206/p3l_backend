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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id('id_barang');
            $table->string('nama_barang');
            $table->date('tgl_garansi');
            $table->float('harga_barang');
            $table->enum('status_barang', ['terjual', 'didonasikan', 'dikembalikan', 'kadaluarsa', 'untuk donasi', 'tersedia']);
            $table->float('rating_barang');
            $table->date('tgl_didonasikan');
            $table->date('tgl_terdonasi');
            $table->string('desc_barang');


            $table->unsignedBigInteger('id_pegawai');
            $table->unsignedBigInteger('id_penitip');
            $table->unsignedBigInteger('id_kategori');
            $table->unsignedBigInteger('id_donasi')->unsigned()->nullable();
            $table->unsignedBigInteger('id_pembeli')->unsigned()->nullable();
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais')->onDelete('cascade');
            $table->foreign('id_penitip')->references('id_penitip')->on('penitips')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategoris')->onDelete('cascade');
            $table->foreign('id_donasi')->references('id_donasi')->on('donasis')->onDelete('cascade');
            $table->foreign('id_pembeli')->references('id_pembeli')->on('pembelis')->onDelete('cascade');
            $table->timestamps();
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
