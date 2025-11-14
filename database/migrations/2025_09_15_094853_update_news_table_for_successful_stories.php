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
        Schema::table('news', function (Blueprint $table) {
            // Add additional file field for successful stories
            $table->string('additional_file', 255)->nullable()->after('front_image');
        });
        
        // Update the category enum to include new categories
        DB::statement("ALTER TABLE news MODIFY COLUMN category ENUM('breaking news', 'update news', 'general news', 'successful stories')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            // Remove the additional file field
            $table->dropColumn('additional_file');
        });
        
        // Revert the category enum to original values
        DB::statement("ALTER TABLE news MODIFY COLUMN category ENUM('habari kuu', 'update news', 'general news', 'event news', 'new news')");
    }
};
