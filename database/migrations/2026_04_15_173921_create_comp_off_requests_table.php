<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comp_off_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')->constrained()->onDelete('cascade');

            $table->date('worked_on_date');
            $table->date('requested_off_date');

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
        Schema::dropIfExists('comp_off_requests');
    }
};