<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->longText('content'); // Long content
            $table->enum('category', [
                'habari kuu',
                'update news',
                'general news',
                'event news',
                'new news'
            ]);
            $table->dateTime('date_expire');
            $table->unsignedBigInteger('posted_by'); // Link to userstable
            $table->string('front_image', 255)->nullable(); // Front image
            $table->timestamps();

            $table->foreign('posted_by')
                ->references('id')
                ->on('userstable')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
