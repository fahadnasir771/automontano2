<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksheetObjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worksheet_objects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('min_time');
            $table->string('max_time');
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
        Schema::dropIfExists('worksheet_objects');
        Schema::dropIfExists('user_worksheet_object');
    }
}
