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
        Schema::table('application_guidelines', function (Blueprint $table) {
            // Add version field for multiple versions of the same academic year
            $table->string('version')->nullable()->after('academic_year');
            
            // Add sort order field for custom ordering
            $table->integer('sort_order')->default(0)->after('is_current');
            
            // Add published date field
            $table->timestamp('published_at')->nullable()->after('sort_order');
            
            // Add expiry date field (optional)
            $table->timestamp('expires_at')->nullable()->after('published_at');
            
            // Add download count for analytics
            $table->unsignedInteger('download_count')->default(0)->after('expires_at');
            
            // Add tags for categorization
            $table->json('tags')->nullable()->after('download_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_guidelines', function (Blueprint $table) {
            $table->dropColumn([
                'version',
                'sort_order', 
                'published_at',
                'expires_at',
                'download_count',
                'tags'
            ]);
        });
    }
};
