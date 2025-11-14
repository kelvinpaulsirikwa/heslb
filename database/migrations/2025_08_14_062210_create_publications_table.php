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
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('posted_by');
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');
            $table->string('file_name')->nullable();
            $table->string('file_path', 500)->nullable();
            $table->enum('file_type', ['PDF', 'DOC', 'DOCX', 'XLS', 'XLSX'])->default('PDF');
            $table->integer('file_size')->nullable()->comment('File size in KB');
            $table->text('description')->nullable();
            $table->date('publication_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('download_count')->default(0);
            $table->timestamps();
            
            $table->index('category_id');
            $table->index('is_active');
            $table->index('publication_date');

                // Foreign key constraint
            $table->foreign('posted_by')
                  ->references('id')
                  ->on('userstable')
                  ->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
