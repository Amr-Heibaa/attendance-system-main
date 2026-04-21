<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->datetime('check_in')->nullable();
            $table->datetime('check_out')->nullable();
            $table->enum('status', ['present', 'absent', 'late', 'leave', 'holiday'])->default('present');
            $table->integer('late_minutes')->default(0);
            $table->integer('early_leave_minutes')->default(0);
            $table->integer('work_minutes')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};