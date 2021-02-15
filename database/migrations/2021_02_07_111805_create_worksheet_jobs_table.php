<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksheetJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worksheet_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('worksheet_id');
            $table->integer('object_id');
            $table->integer('operator_id');
            $table->integer('started')->default(0);
            $table->integer('completed')->default(0);
            $table->timestamps();
            
            $table->foreign('worksheet_id')->references('id')->on('worksheets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('worksheet_jobs');
    }
}
