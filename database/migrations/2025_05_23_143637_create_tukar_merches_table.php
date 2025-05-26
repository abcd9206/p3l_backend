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
        Schema::create('tukar_merches', function (Blueprint $table) {
            $table->string('id_tukarMerch')->unique();
            $table->date('tgl_tukarMerch');
            $table->date('tgl_ambilMerch');
            $table->string('status_merch');

            $table->string('id_pegawai');
            $table->string('id_pembeli');
            $table->string('id_merch');
            $table->string('id_penitip');
            $table->timestamps();

            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais')->onDelete('cascade');
            $table->foreign('id_pembeli')->references('id_pembeli')->on('pembelis')->onDelete('cascade');
            $table->foreign('id_merch')->references('id_merch')->on('merches')->onDelete('cascade');
            $table->foreign('id_penitip')->references('id_penitip')->on('penitips')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tukar_merches');
    }
};
