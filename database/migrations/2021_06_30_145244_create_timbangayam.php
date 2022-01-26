<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimbangayam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timbangayam', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('riwayat_id')->nullable();
            $table->integer('hari')->nullable();
            $table->date('tanggal')->nullable();
            $table->text('data_timbang')->nullable();
            $table->double('jumlah', 20)->nullable();
            $table->double('berat', 20)->nullable();
            $table->double('ratarata', 20)->nullable();
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
        Schema::dropIfExists('timbangayam');
    }
}
