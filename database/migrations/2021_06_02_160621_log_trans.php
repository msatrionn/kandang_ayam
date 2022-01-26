<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LogTrans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_trans', function (Blueprint $table) {
            $table->id();
            $table->string('table', 30)->nullable();
            $table->bigInteger('table_id')->nullable();
            $table->bigInteger('produk_id')->nullable();
            $table->bigInteger('kandang_id')->nullable();
            $table->string('jenis', 40)->nullable();
            $table->date('tanggal')->nullable();
            $table->bigInteger('kas')->nullable();
            $table->integer('qty')->nullable();
            $table->double('nominal')->nullable();
            $table->timestamps();
            $table->integer('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_trans');
    }
}
