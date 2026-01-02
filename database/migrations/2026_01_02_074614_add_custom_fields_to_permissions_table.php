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
        // Add custom fields to existing Spatie permissions table if they don't exist
        if (Schema::hasTable('permissions')) {
        Schema::table('permissions', function (Blueprint $table) {
                if (!Schema::hasColumn('permissions', 'display_name')) {
                    $table->string('display_name')->nullable()->after('name');
                }
                if (!Schema::hasColumn('permissions', 'description')) {
                    $table->text('description')->nullable()->after('display_name');
                }
                if (!Schema::hasColumn('permissions', 'category')) {
                    $table->string('category')->nullable()->after('description');
                    $table->index('category');
                }
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
        if (Schema::hasTable('permissions')) {
        Schema::table('permissions', function (Blueprint $table) {
                if (Schema::hasColumn('permissions', 'category')) {
                    $table->dropIndex(['category']);
                    $table->dropColumn('category');
                }
                if (Schema::hasColumn('permissions', 'description')) {
                    $table->dropColumn('description');
                }
                if (Schema::hasColumn('permissions', 'display_name')) {
                    $table->dropColumn('display_name');
                }
        });
        }
    }
};
