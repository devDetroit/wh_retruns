<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartsComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parts_components', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_part');
            $table->foreign('id_part')->references('id_part')->on('part');
            $table->unsignedBigInteger('id_component');
            $table->foreign('id_component')->references('id_component')->on('components');
            $table->integer('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parts_components');
    }
}
