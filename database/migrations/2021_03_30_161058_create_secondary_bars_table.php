<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecondaryBarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secondary_bars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->text('left');
            $table->text('width');
            $table->text('color');
            $table->text('mode');
            $table->text('mode2');
            $table->text('main_bar_id');
            $table->text('objects_index');
            $table->text('status');
            $table->text('position');
            $table->text('text');
            $table->text('date');
            $table->text('month');
            $table->text('year');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('secondary_bars');
    }
}
