<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKartustok extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kartustok', function (Blueprint $table) {
            $table->id();
            $table->string('tipekartu', 100)->nullable();
            $table->bigInteger('recording_id')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->date('tanggal_kartu')->nullable();
            $table->integer('hari')->nullable();
            $table->bigInteger('jenis')->nullable();
            $table->double('masuk', 20)->nullable();
            $table->double('keluar', 20)->nullable();
            $table->bigInteger('penerima')->nullable();
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('kartustok');
    }
}
