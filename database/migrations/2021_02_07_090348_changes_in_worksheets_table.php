<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangesInWorksheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('worksheets', function (Blueprint $table) {
            $table->dropColumn('acceptor_accepted');
            $table->date('customer_accepted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('worksheets', function (Blueprint $table) {
            $table->dropColumn('customer_accepted_at');
        });
    }
}
