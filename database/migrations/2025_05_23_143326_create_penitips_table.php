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
        Schema::create('penitips', function (Blueprint $table) {
            $table->id('id_penitip');
            $table->string('NIK');
            $table->string('nama_penitip');
            $table->integer('point_reward');
            $table->string('email');
            $table->string('password');
            $table->float('saldo');
            $table->integer('jml_terjual');
            $table->integer('jml_terdonasi');
            $table->string('badge_penitip');
            $table->float('ratarata_rating');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penitips');
    }
};
