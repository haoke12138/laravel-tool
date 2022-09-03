<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfoDisplayTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_display_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->default('')->comment('标题');
            $table->string('subtitle')->default('')->comment('副标题');
            $table->string('link')->nullable()->comment('链接');
            $table->string('image')->nullable()->comment('图片');
            $table->string('type')->nullable()->comment('类型');
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
        Schema::dropIfExists('info_display_type');
    }
}
