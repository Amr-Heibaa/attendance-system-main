<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_policies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['late', 'absence', 'early_leave']);
            $table->integer('minutes_from')->nullable();
            $table->integer('minutes_to')->nullable();
            $table->enum('penalty_type', ['fixed', 'percent', 'warning']);
            $table->decimal('penalty_value', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_policies');
    }
};