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
            $table->integer('id_barang')->unique();
            $table->string('nama_barang');
            $table->date('tgl_garansi');
            $table->float('harga_barang');
            $table->enum('status_barang', ['terjual', 'didonasikan', 'dikembalikan', 'kadaluarsa', 'untuk donasi']);
            $table->integer('rating_barang');
            $table->date('tgl_didonasikan');
            $table->date('tgl_terdonasi');

            $table->integer('id_pegawai');
            $table->integer('id_penitip');
            $table->integer('id_kategori');
            $table->integer('id_pembelian');
            $table->integer('id_donasi');
            $table->integer('id_pembeli');

            $table->timestamps();

            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais')->onDelete('cascade');
            $table->foreign('id_penitip')->references('id_penitip')->on('penitips')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategoris')->onDelete('cascade');
            $table->foreign('id_pembelian')->references('id_pembelian')->on('pembelians')->onDelete('cascade');
            $table->foreign('id_donasi')->references('id_donasi')->on('donasis')->onDelete('cascade');
            $table->foreign('id_pembeli')->references('id_pembeli')->on('pembelis')->onDelete('cascade');
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
