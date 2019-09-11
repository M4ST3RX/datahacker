<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMakeTypeToFileTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_types', function (Blueprint $table) {
            $table->string('make_type')->nullable()->after('extension');
            $table->integer('admin_only')->default(0)->after('make_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('file_types', function (Blueprint $table) {
            $table->dropColumn(['make_type', 'admin_only']);
        });
    }
}
