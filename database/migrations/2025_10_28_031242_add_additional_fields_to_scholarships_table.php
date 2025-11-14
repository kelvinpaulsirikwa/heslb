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
        Schema::table('scholarships', function (Blueprint $table) {
            $table->string('donor_organization')->nullable()->after('title');
            $table->date('application_deadline')->nullable()->after('donor_organization');
            $table->text('eligible_applicants')->nullable()->after('application_deadline');
            $table->text('fields_of_study')->nullable()->after('eligible_applicants');
            $table->json('level_of_study')->nullable()->after('fields_of_study');
            $table->text('benefits_summary')->nullable()->after('level_of_study');
            $table->string('external_link')->nullable()->after('benefits_summary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scholarships', function (Blueprint $table) {
            $table->dropColumn([
                'donor_organization',
                'application_deadline',
                'eligible_applicants',
                'fields_of_study',
                'level_of_study',
                'benefits_summary',
                'external_link',
            ]);
        });
    }
};
