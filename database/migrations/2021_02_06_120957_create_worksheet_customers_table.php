<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksheetCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worksheet_customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('worksheet_id');
            $table->string('full_name');
            $table->string('surname');
            $table->string('city')->nullable();
            $table->string('street')->nullable();
            $table->string('fiscal_code')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('phone')->nullable();
            $table->string('cell_phone');
            $table->string('email')->nullable();
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
        Schema::dropIfExists('worksheet_customers');
    }
}
