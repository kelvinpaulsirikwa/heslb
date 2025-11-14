<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taasisevent', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('posted_by');
            $table->string('name_of_event');
            $table->string('description');
            $table->timestamps();

            // foreign key to userstable
            $table->foreign('posted_by')
                  ->references('id')
                  ->on('userstable')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taasisevent');
    }
};
