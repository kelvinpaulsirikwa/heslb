<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWindowApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('windowapplications', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('userstable')->onDelete('cascade');

            $table->string('program_type');
            $table->string('window');
            $table->string('academic_year');
            $table->date('submitted_at')->nullable();
            $table->date('starting_date')->nullable();
            $table->date('ending_date')->nullable();

            $table->longText('description')->nullable(); // new column

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('windowapplications');
    }
}
