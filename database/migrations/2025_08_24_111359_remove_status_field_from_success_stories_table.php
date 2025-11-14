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
        // Drop the publication_status column (which actually exists in the table)
        if (Schema::hasColumn('success_stories', 'publication_status')) {
            Schema::table('success_stories', function (Blueprint $table) {
                $table->dropColumn('publication_status');
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
        // Restore the publication_status column
        if (!Schema::hasColumn('success_stories', 'publication_status')) {
            Schema::table('success_stories', function (Blueprint $table) {
                $table->enum('publication_status', ['pending', 'approved', 'rejected', 'draft'])
                      ->default('pending');
            });
        }
    }
};
