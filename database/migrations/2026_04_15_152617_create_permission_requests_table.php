<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permission_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');

            $table->date('date');
            $table->time('from_time');
            $table->time('to_time');
            $table->integer('minutes_count')->default(0);

            $table->enum('type', ['late_arrival', 'early_leave', 'temporary_exit'])->default('temporary_exit');

            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->foreignId('approved_by')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->dateTime('approved_at')->nullable();
            $table->text('manager_notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permission_requests');
    }
};