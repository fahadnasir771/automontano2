<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobTimersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_timers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('worksheet_job_id');
            $table->string('html_id');
            
            $table->integer('started')->default(0);
            $table->integer('in_progress')->default(0);
            $table->integer('finished')->default(0);

            $table->text('min_at')->nullable();
            $table->text('max_at')->nullable();

            $table->text('started_at')->nullable();
            $table->text('finished_at')->nullable();

            $table->integer('paused')->default(0);
            $table->integer('need-spare-parts')->default(0);

            $table->integer('job_passed')->default(0);
            $table->integer('user_id')->nullable();

            $table->integer('stop_work')->default(0);
            $table->text('stop_work_justificaton')->nullable();

            

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
        Schema::dropIfExists('job_timers');
    }
}
