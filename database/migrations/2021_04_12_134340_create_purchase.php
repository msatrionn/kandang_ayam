<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('kandang_id')->nullable();
            $table->integer('nomor')->nullable();
            $table->bigInteger('supplier_id')->nullable();
            $table->bigInteger('tipe')->nullable();
            $table->integer('qty')->nullable();
            $table->double('total_harga', 20)->nullable();
            $table->double('dibayarkan', 20)->nullable();
            $table->date('tanggal')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->text('produk')->nullable();
            $table->integer('tax')->nullable();
            $table->text('keterangan')->nullable();
            $table->double('down_payment', 20)->nullable();
            $table->bigInteger('kas')->nullable();
            $table->integer('termin')->nullable();
            $table->date('termin_tanggal')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('purchase');
    }
}
