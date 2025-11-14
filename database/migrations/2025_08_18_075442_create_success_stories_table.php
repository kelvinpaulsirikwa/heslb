<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('success_stories', function (Blueprint $table) {
            $table->id();
            
            // Personal Information
            $table->string('author');
            $table->string('email');
            $table->string('phone');
            
            // Academic Information
            $table->string('university');
            $table->string('reference_number')->nullable();
            $table->enum('category', [
                'tuition', 
                'meals', 
                'books', 
                'research', 
                'special_needs', 
                'postgraduate'
            ]);
            
            // Story Information
            $table->string('title');
            $table->longText('content');
            
            // Media Files (JSON to store multiple file paths)
            $table->json('images')->nullable();
            $table->string('video')->nullable();
            $table->json('documents')->nullable();
            
            // Permissions
            $table->boolean('allow_photos')->default(true);
            $table->boolean('allow_video')->default(true);
            $table->boolean('allow_contact')->default(false);
            
            // System Information
            $table->string('ip_address', 45);
            $table->text('user_agent');
            $table->string('browser')->nullable();
            $table->string('platform')->nullable();
            
            // Publication Status
            $table->enum('publication_status', ['pending', 'approved', 'rejected', 'draft'])
                  ->default('pending');
            $table->timestamp('published_at')->nullable();

            // Relation to users table
            $table->foreignId('approved_by')->nullable()->constrained('userstable')->nullOnDelete();
            
            $table->text('admin_notes')->nullable();
            
            // Engagement Metrics
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('likes')->default(0);
            $table->unsignedInteger('shares')->default(0);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['publication_status', 'published_at']);
            $table->index(['category']);
            $table->index(['email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('success_stories');
    }
};
