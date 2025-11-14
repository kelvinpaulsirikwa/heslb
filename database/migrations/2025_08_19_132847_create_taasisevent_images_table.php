<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taasisevent_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('taasisevent_id');
            $table->unsignedBigInteger('posted_by');
            $table->string('image_link');
            $table->text('description')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('taasisevent_id')
                  ->references('id')
                  ->on('taasisevent')
                  ->onDelete('cascade');

            $table->foreign('posted_by')
                  ->references('id')
                  ->on('userstable')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taasisevent_images');
    }
};
