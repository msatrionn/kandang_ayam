<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDelivery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('purchase_id')->nullable();
            $table->bigInteger('angkatan_id')->nullable();
            $table->bigInteger('produk_id')->nullable();
            $table->date('tanggal')->nullable();
            $table->integer('qty')->nullable();
            $table->bigInteger('kas')->nullable();
            $table->double('biaya_pengiriman', 20)->nullable();
            $table->double('beban_angkut', 20)->nullable();
            $table->double('biaya_lain', 20)->nullable();
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
        Schema::dropIfExists('delivery');
    }
}
