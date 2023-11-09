<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartRecordDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('part_record_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('part_record_id');
            $table->foreign('part_record_id')->references('id')->on('part_records');
            $table->string('part');
            $table->string('component');
            $table->integer('quantity');
            $table->integer('checked_quantity')->default(0);
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->softDeletes('deleted_at', 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('part_record_details');
    }
}
