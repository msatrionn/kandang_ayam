<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListTrans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_list', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('header_id')->nullable();
            $table->bigInteger('kandang_id')->nullable();
            $table->enum('type', ['jual_ayam', 'jual_lain', 'setor_modal', 'tarik_modal', 'pengeluaran_lain', 'mutasi_masuk', 'mutasi_keluar'])->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->bigInteger('stok_id')->nullable();
            $table->integer('qty')->nullable();
            $table->double('harga_satuan', 20)->nullable();
            $table->double('total_harga', 20)->nullable();
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
        Schema::dropIfExists('trans_list');
    }
}
