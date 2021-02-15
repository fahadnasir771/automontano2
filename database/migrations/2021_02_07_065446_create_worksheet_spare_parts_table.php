<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksheetSparePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worksheet_spare_parts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('worksheet_id');
            $table->string('title');
            $table->integer('available');
            $table->date('delivery_date')->nullable();
            $table->integer('notify_acceptor')->default(0);
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
        Schema::dropIfExists('worksheet_spare_parts');
    }
}
