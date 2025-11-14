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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('acronym_name')->nullable();
            $table->string('link')->nullable();
            $table->string('image_path')->nullable();
            $table->unsignedBigInteger('posted_by');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('posted_by')->references('id')->on('userstable')->onDelete('cascade');
            
            // Index for better performance
            $table->index('posted_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partners');
    }
};