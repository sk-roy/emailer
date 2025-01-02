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
        Schema::create('mail-logs', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->string('email');
            $table->text('message')->nullable();
            $table->longText('attachment')->nullable();
            $table->string('attachment_filename')->nullable();
            $table->enum('status', ['sent', 'failed', 'in-queue'])->default('in-queue');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('mail-logs');
    }
};
