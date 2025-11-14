<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideopodcastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Videopodcast', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('posted_by');
            $table->dateTime('date_posted');
            $table->string('link');
            $table->timestamps();

            // Assuming 'posted_by' references the id on 'Userstable' table
            $table->foreign('posted_by')->references('id')->on('userstable')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Videopodcast');
    }
}
