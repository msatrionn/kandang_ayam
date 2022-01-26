<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Cashbon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashbon', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('karyawan_id')->nullable();
            $table->date('tanggal')->nullable();
            $table->double('nominal', 20)->nullable();
            $table->bigInteger('payment_id')->nullable();
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
        Schema::dropIfExists('cashbon');
    }
}
