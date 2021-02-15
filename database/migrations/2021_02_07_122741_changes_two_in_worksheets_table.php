<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangesTwoInWorksheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('worksheets', function (Blueprint $table) {
            $table->dropColumn('customer_accepted_at');
            $table->date('customer_accept_at')->nullable()->after('is_task');
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
            //
        });
    }
}
