<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->string('link_name');
            $table->text('link');
            $table->unsignedBigInteger('posted_by');
            $table->boolean('is_file')->default(false);  // <-- Added column here
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('posted_by')
                  ->references('id')
                  ->on('userstable')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
