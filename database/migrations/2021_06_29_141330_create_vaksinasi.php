<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaksinasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaksinasi', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('riwayat_id')->nullable();
            $table->integer('umur')->nullable();
            $table->date('tanggal')->nullable();
            $table->bigInteger('vaksin')->nullable();
            $table->string('aplikasi', 100)->nullable();
            $table->string('realisasi', 100)->nullable();
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
        Schema::dropIfExists('vaksinasi');
    }
}
