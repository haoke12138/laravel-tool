<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfoDisplayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_displays', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->default('')->comment('标题');
            $table->string('subtitle')->nullable()->comment('副标题');
            $table->text('icon')->nullable()->comment('icon图');
            $table->text('desc')->nullable()->comment('简介');
            $table->text('link')->nullable()->comment('外链');
            $table->string('files')->nullable()->comment('文件');
            $table->longText('other_info')->nullable()->comment('其他信息');
            $table->json('json_info')->nullable()->comment('json信息');
            $table->integer('category_id')->default(0)->comment('分类id');
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
        Schema::dropIfExists('info_displays');
    }
}
