<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatkandang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riwayatkandang', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('angkatan')->nullable();
            $table->bigInteger('strain_id')->nullable();
            $table->date('tanggal')->nullable();
            $table->bigInteger('kandang')->nullable();
            $table->integer('populasi')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('riwayatkandang');
    }
}
