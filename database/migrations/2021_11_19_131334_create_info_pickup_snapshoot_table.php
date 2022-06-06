<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfoPickupSnapshootTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_pickup_snapshoot', function (Blueprint $table) {
            $table->id();
            $table->string('pickup_snaphoot_no_order');
            $table->text('foto_bukti');
            $table->integer('address_id');
            $table->string('hari_penjemputan');
            $table->string('waktu_penjemputan');
            $table->text('informasi_tambahan');
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
        Schema::dropIfExists('info_pickup_snapshoot');
    }
}
