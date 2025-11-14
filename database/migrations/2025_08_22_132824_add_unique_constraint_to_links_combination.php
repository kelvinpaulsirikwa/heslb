<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Added this import for DB facade

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First, clean up any duplicate web links (non-file links)
        DB::statement("
            DELETE l1 FROM links l1
            INNER JOIN links l2 
            WHERE l1.id > l2.id 
            AND l1.link_name = l2.link_name 
            AND l1.link = l2.link 
            AND l1.is_file = 0 
            AND l2.is_file = 0
        ");

        // Create unique constraint with proper key length for TEXT column
        // MySQL requires key length for TEXT columns in indexes
        DB::statement("
            ALTER TABLE links 
            ADD UNIQUE KEY links_name_url_unique (link_name, link(255))
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dropUnique('links_name_url_unique');
        });
    }
};
