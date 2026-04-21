<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('employee_code')->unique();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('shift_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->string('job_title')->nullable();
            $table->string('phone')->nullable();
            $table->string('national_id')->nullable();
            $table->date('hire_date');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamps();

            $table->foreign('manager_id')
                  ->references('id')
                  ->on('employees')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};