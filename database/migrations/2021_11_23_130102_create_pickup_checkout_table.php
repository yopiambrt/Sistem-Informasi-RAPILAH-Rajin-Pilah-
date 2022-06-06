<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickupCheckoutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pickup_checkout', function (Blueprint $table) {
            $table->id();
            $table->string('no_order');
            $table->string('jenis_sampah');
            $table->date('tanggal_transaksi');
            $table->string('petugas');
            $table->string('status');
            $table->string('pendapatan');
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
        Schema::dropIfExists('pickup_checkout');
    }
}
