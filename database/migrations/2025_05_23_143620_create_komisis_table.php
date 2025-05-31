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
        Schema::create('komisis', function (Blueprint $table) {
            $table->id('id_komisi');
            $table->float('jml_komisiHunter');
            $table->float('jml_komisiPenitip');
            $table->float('jml_komisiReuseMart');


            $table->unsignedBigInteger('id_pegawai');
            $table->unsignedBigInteger('id_pembelian');
            $table->unsignedBigInteger('id_penitip');
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais')->onDelete('cascade');
            $table->foreign('id_penitip')->references('id_penitip')->on('penitips')->onDelete('cascade');
            $table->foreign('id_pembelian')->references('id_pembelian')->on('pembelians')->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komisis');
    }
};
