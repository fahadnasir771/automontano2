<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobTimerSparePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_timer_spare_parts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('job_timer_id');

            $table->string('title');
            $table->string('quantity');
            $table->text('dexcription');
            $table->text('time_required');

            $table->timestamps();

            $table->foreign('job_timer_id')->references('id')->on('job_timers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_timer_spare_parts');
    }
}
