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
        Schema::table('exams', function (Blueprint $table) {
            $table->dateTime('time_start')->after('class_id')->default(now());
            $table->dateTime('time_end')->after('time_start')->default(now()->addHours(2));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            if (Schema::hasColumn('exams', 'time_start', 'time_end')) {
                $table->dropColumn('time_start', 'time_end');
            }
        });
    }
};
