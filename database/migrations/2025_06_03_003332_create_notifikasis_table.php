<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_pembeli')->nullable();
            $table->unsignedBigInteger('id_penitip')->nullable();
            $table->unsignedBigInteger('id_pegawai')->nullable();

            $table->string('judul');
            $table->text('pesan');
            $table->boolean('dibaca')->default(false);
            $table->timestamps();

            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais')->onDelete('cascade');
            $table->foreign('id_pembeli')->references('id_pembeli')->on('pembelis')->onDelete('cascade');
            $table->foreign('id_penitip')->references('id_penitip')->on('penitips')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
