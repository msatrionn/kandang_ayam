<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Gaji extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gaji', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->nullable();
            $table->bigInteger('karyawan_id')->nullable();
            $table->string('metode_gaji', 20)->nullable();
            $table->double('besar_gaji', 20)->nullable();
            $table->double('hari_gaji', 20)->nullable();
            $table->string('overtime', 20)->nullable();
            $table->double('besar_overtime', 20)->nullable();
            $table->double('perkalian_overtime', 20)->nullable();
            $table->double('potong_gaji', 20)->nullable();
            $table->double('cashbon', 20)->nullable();
            $table->double('thr', 20)->nullable();
            $table->double('total_didapat', 20)->nullable();
            $table->bigInteger('metode_kas')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gaji');
    }
}
