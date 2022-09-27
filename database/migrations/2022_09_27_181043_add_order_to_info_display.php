<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderToInfoDisplay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('info_displays', function (Blueprint $table) {
            $table->integer( 'order')->after('type')->comment('排序')->default(100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('info_displays', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
