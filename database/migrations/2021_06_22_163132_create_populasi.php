<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePopulasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('populasi', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('riwayat_id')->nullable();
            $table->bigInteger('kandang')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->integer('hari')->nullable();
            $table->date('tanggal_input')->nullable();
            $table->double('populasi_mati', 20)->nullable();
            $table->double('populasi_afkir', 20)->nullable();
            $table->double('populasi_panen', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('populasi');
    }
}
