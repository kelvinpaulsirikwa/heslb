<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer')->nullable();
            $table->unsignedBigInteger('posted_by'); // foreign key to users table
            $table->enum('type', ['loan_application', 'loan_repayment'])->default('loan_application'); // enum column
            $table->enum('qnstype', ['popular_questions', 'general_questions'])->default('popular_questions'); // enum column
            $table->timestamps();

            $table->foreign('posted_by')->references('id')->on('userstable')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
