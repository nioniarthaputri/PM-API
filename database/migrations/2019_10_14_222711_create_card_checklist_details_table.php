<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardChecklistDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_checklist_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('checklist_id')->unsigned();
            $table->string('list');
            $table->integer('checked');
            $table->integer('checked_by');
            $table->integer('created_by');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('checklist_id')->references('id')->on('card_checklists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_checklist_details');
    }
}
