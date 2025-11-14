<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            
            // Personal Info
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('gender')->nullable();
 
            // Contact Purpose
            $table->string('contact_type');

            // Message Details
            $table->text('message');
            $table->date('date_of_incident')->nullable();
            $table->string('location')->nullable();
            $table->string('image')->nullable();

            // Consent
            $table->boolean('consent')->default(false);

            // Status & Delete flags
            $table->enum('status', ['seen', 'not seen'])->default('not seen');
            $table->boolean('published')->default(false);
            $table->integer('views')->default(0);
            $table->enum('delete', ['yes', 'no'])->default('no');

            // Foreign keys
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('userstable')->nullOnDelete();

            $table->unsignedBigInteger('seen_by')->nullable();
            $table->foreign('seen_by')->references('id')->on('userstable')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
