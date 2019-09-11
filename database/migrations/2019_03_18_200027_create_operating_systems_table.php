<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperatingSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operating_systems', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('background_color')->nullable();
            $table->longText('background_image')->nullable();
            $table->string('menu_color')->nullable();
            $table->string('menu_selected_color')->nullable();
            $table->string('start_menu_color')->nullable();
            $table->longText('menu_image')->nullable();
            $table->string('terminal_color')->nullable();
            $table->string('window_color')->nullable();
            $table->string('tab_color')->nullable();
            $table->string('tab_border')->nullable();
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
        Schema::dropIfExists('operating_systems');
    }
}
