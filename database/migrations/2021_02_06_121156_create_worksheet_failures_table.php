<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksheetFailuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worksheet_failures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('worksheet_id');
            $table->string('failure_title');
            $table->string('failure_quotation');
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
        Schema::dropIfExists('worksheet_failures');
    }
}
