<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksheetVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worksheet_vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('worksheet_id');
            $table->integer('customer_id');
            $table->date('date_of_acceptance');
            $table->string('license_plate');
            $table->string('engine_variant');
            $table->string('car_brand');
            $table->string('car_model');
            $table->string('engine_displacement');
            $table->date('revision_due_date');
            $table->string('fuel_level');
            $table->string('mileage');
            $table->timestamps();

            $table->foreign('worksheet_id')->references('id')->on('worksheets')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('worksheet_vehicles');
    }
}
