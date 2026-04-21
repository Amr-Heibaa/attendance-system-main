<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->date('birth_date')->nullable()->after('national_id');
            $table->string('insurance_number')->nullable()->after('birth_date');
            $table->string('education_qualification')->nullable()->after('insurance_number');
            $table->text('address')->nullable()->after('education_qualification');
            $table->string('emergency_phone')->nullable()->after('phone');
            $table->string('cost_center')->nullable()->after('emergency_phone');
            $table->string('work_location')->nullable()->after('cost_center');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'birth_date',
                'insurance_number',
                'education_qualification',
                'address',
                'emergency_phone',
                'cost_center',
                'work_location',
            ]);
        });
    }
};