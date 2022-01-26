<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StockKandang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_kandang', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('stock_id')->nullable();
            $table->date('tanggal')->nullable();
            $table->bigInteger('produk_id')->nullable();
            $table->bigInteger('tipe')->nullable();
            $table->double('jumlah', 20)->nullable();
            $table->double('sisa', 20)->nullable();
            $table->bigInteger('kandang_id')->nullable();
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
        Schema::dropIfExists('stock_kandang');
    }
}
