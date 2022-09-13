<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNavigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->default('');
            $table->string('slug')->default('');
            $table->text('image')->nullable();
            $table->text('mobile_image')->nullable();
            $table->longText('banner_info')->nullable();
            $table->integer('parent_id')->default('0');
            $table->integer('is_external_link')->default('0');
            $table->text('link')->nullable();
            $table->text('external_link')->nullable();
            $table->integer('order')->default('100');
            $table->json('tdk')->nullable();
            $table->integer('enable')->default('1');
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
        Schema::dropIfExists('navigations');
    }
}
