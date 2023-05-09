<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->default('')->comment('标题');
            $table->text('desc')->comment('简介')->nullable();
            $table->string('source')->default('')->comment('来源');
            $table->string('author')->default('')->comment('作者');
            $table->longText('content')->nullable()->comment('正文');
            $table->integer('category_id')->default(0)->comment('文章分类');
            $table->string('article_type')->default('news')->comment('文章类型 默认为news');
            $table->integer('type')->default(0)->comment('是否跳转外部文章 0 否 1 是');
            $table->text('link')->comment('外部文章链接')->nullable();
            $table->text('thumbnail')->nullable()->comment('缩略图');
            $table->text('attachment')->nullable()->comment('附件');
            $table->string('file_type')->nullable()->comment('附件文件类型');
            $table->dateTime('published_at')->nullable()->comment('发布时间');
            $table->integer('enable')->default(0)->comment('发布状态 0 未发布 1 已发布');
            $table->integer('visited')->default(0)->comment('访问量');
            $table->integer('order')->default(100)->comment('排序');
            $table->float('width')->default(0)->comment('缩略图宽度');
            $table->float('height')->default(0)->comment('缩略图高度');
            $table->longText('tdk')->nullable()->comment('TDK');
            $table->dateTime('deleted_at')->nullable()->comment('软删除时间');

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
        Schema::dropIfExists('article');
    }
}
