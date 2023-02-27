<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_boms', function (Blueprint $table) {
            $table->id();
            $table->foreign('id_parent')->references('id')->on('tm_part_numbers');
            $table->foreign('id_child')->references('id')->on('tm_part_numbers');;
            $table->integer('qty_use');
            $table->string('uom');
            $table->integer('process_id');
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
        Schema::dropIfExists('tm_boms');
    }
};
