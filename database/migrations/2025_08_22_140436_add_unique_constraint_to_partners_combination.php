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
        // First, delete any duplicate combinations to avoid constraint violation
        DB::statement("
            DELETE p1 FROM partners p1
            INNER JOIN partners p2 
            WHERE p1.id > p2.id 
            AND p1.name = p2.name 
            AND COALESCE(p1.acronym_name, '') = COALESCE(p2.acronym_name, '')
            AND COALESCE(p1.link, '') = COALESCE(p2.link, '')
        ");

        Schema::table('partners', function (Blueprint $table) {
            $table->unique(['name', 'acronym_name', 'link'], 'partners_name_acronym_link_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->dropUnique('partners_name_acronym_link_unique');
        });
    }
};
