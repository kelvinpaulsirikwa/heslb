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
        Schema::create('story_action_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_id')->constrained('success_stories')->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('userstable')->onDelete('cascade');
            $table->string('action'); // approve, reject, draft, edit, etc.
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->text('notes')->nullable();
            $table->json('changes')->nullable(); // Store what was changed
            $table->timestamps();
            
            $table->index(['story_id', 'created_at']);
            $table->index(['admin_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('story_action_history');
    }
};
