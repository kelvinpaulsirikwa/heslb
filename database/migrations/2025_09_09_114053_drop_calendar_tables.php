<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropCalendarTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Drop event_reminders table first (has foreign key to calendar_events)
        Schema::dropIfExists('event_reminders');
        
        // Drop calendar_events table
        Schema::dropIfExists('calendar_events');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Recreate calendar_events table
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime')->nullable();
            $table->boolean('all_day')->default(false);
            $table->string('recurrence_rule')->nullable();
            $table->string('timezone')->nullable();
            $table->integer('reminder_minutes')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();

            // Foreign key constraint linking user_id to userstable table
            $table->foreign('user_id')->references('id')->on('userstable')->onDelete('cascade');
        });

        // Recreate event_reminders table
        Schema::create('event_reminders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->dateTime('remind_at');
            $table->string('email');
            $table->timestamps();

            // Foreign key linking to calendar_events table
            $table->foreign('event_id')->references('id')->on('calendar_events')->onDelete('cascade');
        });
    }
}