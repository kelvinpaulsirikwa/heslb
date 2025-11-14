<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Use raw SQL for MySQL compatibility
        DB::statement('ALTER TABLE success_stories CHANGE reference_number form_four_index_number VARCHAR(255)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Use raw SQL for MySQL compatibility
        DB::statement('ALTER TABLE success_stories CHANGE form_four_index_number reference_number VARCHAR(255)');
    }
};
