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
        Schema::create('request_item', function (Blueprint $table) {
            $table->integer('id');

            $table->integer('code_item', 10);
            $table->string('id_jam', 11);
            $table->string('nama', 50);
            $table->string('user', 25);
            $table->string('jumlah', 11);
            $table->string('satuan', 16);
            $table->string('status', 11);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
