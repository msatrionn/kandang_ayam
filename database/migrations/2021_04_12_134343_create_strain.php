<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStrain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('strain', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('strain_id')->nullable();
            $table->string('category', 10)->nullable();
            $table->integer('minggu')->nullable();
            $table->integer('dari')->nullable();
            $table->integer('sampai')->nullable();
            $table->double('angka', 20)->nullable();
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
        Schema::dropIfExists('strain');
    }
}
