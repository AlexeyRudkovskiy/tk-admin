<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 08.08.18
 * Time: 00:13
 */

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->json('items');
            $table->string('location', 32);
            $table->string('tag')->nullable();
            $table->boolean('without_categories')->default(true);

            $table->integer('order')->default(100);

            $table->timestamps();
        });

        Schema::create('menuables', function (Blueprint $table) {
            $table->integer('menu_id', false , true);
            $table->morphs('menuable');

            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menuables');
        Schema::dropIfExists('menus');
    }
}

