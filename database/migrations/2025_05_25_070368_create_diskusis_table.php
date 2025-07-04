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
        Schema::create('diskusis', function (Blueprint $table) {
            $table->id('id_diskusi');
            $table->string('comment');

            $table->unsignedBigInteger('id_pegawai');
            $table->unsignedBigInteger('id_barang');
            $table->unsignedBigInteger('id_pembeli');
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais')->onDelete('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('barangs')->onDelete('cascade');
            $table->foreign('id_pembeli')->references('id_pembeli')->on('pembelis')->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diskusis');
    }
};
