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
        Schema::create('executive_directors', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 255);
            $table->string('imagepath')->nullable();
            $table->year('start_year');
            $table->year('end_year')->nullable();
            $table->text('term_description')->nullable();
            $table->enum('status', ['Active', 'Former'])->default('Active');
            $table->string('posted_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('executive_directors');
    }
};
