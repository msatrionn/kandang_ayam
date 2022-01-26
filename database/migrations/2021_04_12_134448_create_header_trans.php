<?php

use Brick\Math\BigInteger;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeaderTrans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_header', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent')->nullable();
            $table->bigInteger('adj')->nullable();
            $table->bigInteger('kandang_id')->nullable();
            $table->enum('jenis', ['penjualan_ayam', 'penjualan_lain', 'setor_modal', 'tarik_modal', 'pengeluaran_lain', 'mutasi_masuk', 'mutasi_keluar'])->nullable();
            $table->integer('nomor')->nullable();
            $table->date('tanggal')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('purchase_id')->nullable();
            $table->bigInteger('konsumen_id')->nullable();
            $table->string('nama_konsumen', 100)->nullable();
            $table->double('total_trans', 20)->nullable();
            $table->double('payment', 20)->nullable();
            $table->double('cashback', 20)->nullable();
            $table->bigInteger('payment_method')->nullable();
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('trans_header');
    }
}
