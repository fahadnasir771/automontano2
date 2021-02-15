<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worksheets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('customer_accepted')->default(0);
            $table->integer('acceptor_accepted')->default(0);
            $table->timestamp('work_started')->nullable();
            $table->integer('work_actually_started')->default(0);
            $table->timestamp('delivery_date')->nullable();
            $table->integer('days_required');
            $table->string('voucher_type');
            $table->integer('customer_id');
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
        Schema::dropIfExists('worksheets');
    }
}
