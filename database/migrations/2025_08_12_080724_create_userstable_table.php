<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserstableTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('userstable', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('telephone')->nullable();
            $table->integer('nida')->nullable();
            $table->enum('role', ['user', 'admin'])->default('user'); // Role enum
            $table->enum('status', ['active', 'blocked',])->default('active'); // Status enum
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('userstable');
    }
}
