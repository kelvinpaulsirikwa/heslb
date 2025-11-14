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
        // Check if the column doesn't exist before adding it
        if (!Schema::hasColumn('success_stories', 'publication_status')) {
            Schema::table('success_stories', function (Blueprint $table) {
                // Add publication_status column back
                $table->enum('publication_status', ['pending', 'approved', 'rejected', 'draft'])
                      ->default('pending')
                      ->after('platform');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Check if the column exists before dropping it
        if (Schema::hasColumn('success_stories', 'publication_status')) {
            Schema::table('success_stories', function (Blueprint $table) {
                // Drop the publication_status column
                $table->dropColumn('publication_status');
            });
        }
    }
};
