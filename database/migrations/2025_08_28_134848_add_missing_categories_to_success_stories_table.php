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
        // First, we need to drop the existing enum and recreate it with the new values
        // MySQL doesn't support adding values to existing enums directly
        
        // Get the current enum values
        $enumValues = DB::select("SHOW COLUMNS FROM success_stories WHERE Field = 'category'")[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $enumValues, $matches);
        $currentValues = explode("','", $matches[1]);
        
        // Add the new values
        $newValues = array_merge($currentValues, ['special_faculty', 'field_work']);
        
        // Create the new enum string
        $newEnumString = "enum('" . implode("','", $newValues) . "')";
        
        // Modify the column
        DB::statement("ALTER TABLE success_stories MODIFY COLUMN category $newEnumString");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove the added values from the enum
        $enumValues = DB::select("SHOW COLUMNS FROM success_stories WHERE Field = 'category'")[0]->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $enumValues, $matches);
        $currentValues = explode("','", $matches[1]);
        
        // Remove the new values
        $newValues = array_diff($currentValues, ['special_faculty', 'field_work']);
        
        // Create the new enum string
        $newEnumString = "enum('" . implode("','", $newValues) . "')";
        
        // Modify the column
        DB::statement("ALTER TABLE success_stories MODIFY COLUMN category $newEnumString");
    }
};
