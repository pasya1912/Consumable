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
        Schema::create('tt_stock', function (Blueprint $table) {
            $table->id();
            $table->string('part_no');
            $table->date('date');
            $table->string('pic');
            $table->time('time');
            $table->string('name');
            $table->string('supplier');
            $table->string('source');
            $table->integer('qty');
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
        Schema::dropIfExists('tt_stock');
    }
};
