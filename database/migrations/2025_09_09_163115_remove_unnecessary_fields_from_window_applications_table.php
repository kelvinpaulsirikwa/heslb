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
            // Remove unnecessary fields
            $table->dropColumn([
                'window_name',
                'is_active',
                'max_applications',
                'requirements'
            ]);
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
            // Restore the removed fields
            $table->string('window_name')->after('program_type');
            $table->boolean('is_active')->default(true)->after('extension_type');
            $table->integer('max_applications')->nullable()->after('is_active');
            $table->text('requirements')->nullable()->after('max_applications');
        });
    }
};
