<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('taasisevent', function (Blueprint $table) {
            $table->unique('name_of_event', 'taasisevent_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taasisevent', function (Blueprint $table) {
            $table->dropUnique('taasisevent_name_unique');
        });
    }
};
