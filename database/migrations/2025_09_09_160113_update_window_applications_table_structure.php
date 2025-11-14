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
        Schema::table('windowapplications', function (Blueprint $table) {
            // Remove the old window field (rounds)
            $table->dropColumn('window');
            
            // Add new fields for the updated structure
            $table->string('window_name')->after('program_type'); // Single window name
            $table->string('extension_type')->nullable()->after('window_name'); // Extension type (1st Extension, 2nd Extension, etc.)
            $table->boolean('is_active')->default(true)->after('extension_type'); // Whether this window is currently active
            $table->integer('max_applications')->nullable()->after('is_active'); // Maximum number of applications allowed
            $table->text('requirements')->nullable()->after('max_applications'); // Application requirements
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('windowapplications', function (Blueprint $table) {
            // Restore the old window field
            $table->string('window')->after('program_type');
            
            // Remove the new fields
            $table->dropColumn([
                'window_name',
                'extension_type', 
                'is_active',
                'max_applications',
                'requirements'
            ]);
        });
    }
};
