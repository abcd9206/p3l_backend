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
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Sesuaikan jika penitip_id atau pembeli_id
            $table->string('judul');
            $table->text('pesan');
            $table->boolean('dibaca')->default(false);

            $table->unsignedBigInteger('id_pembeli');
            $table->unsignedBigInteger('id_penitip');
            $table->unsignedBigInteger('id_pegawai');

            $table->timestamps();

            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais')->onDelete('cascade');
            $table->foreign('id_pembeli')->references('id_pembeli')->on('pembelis')->onDelete('cascade');
            $table->foreign('id_penitip')->references('id_penitip')->on('penitips')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembeli', function (Blueprint $table) {
            $table->dropColumn('fcm_token');
        });

        Schema::table('penitip', function (Blueprint $table) {
            $table->dropColumn('fcm_token');
        });

        Schema::table('pegawai', function (Blueprint $table) {
            $table->dropColumn('fcm_token');
        });

        // Schema::dropIfExists('notifikasis');
    }
};
