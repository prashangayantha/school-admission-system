<?php
// database/migrations/2024_10_01_000004_create_notifications_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            // Additional fields for our notification system
            $table->string('title');
            $table->text('message');
            $table->enum('status', ['sent', 'failed', 'pending'])->default('sent');
            $table->string('channel')->default('database'); // email, sms, database
            $table->json('metadata')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};