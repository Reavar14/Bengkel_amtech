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
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'estimation_minutes')) {
                $table->integer('estimation_minutes')->nullable()->after('time');
            }
            if (!Schema::hasColumn('bookings', 'estimated_finish_time')) {
                $table->timestamp('estimated_finish_time')->nullable()->after('estimation_minutes');
            }
            if (!Schema::hasColumn('bookings', 'actual_finish_time')) {
                $table->timestamp('actual_finish_time')->nullable()->after('estimated_finish_time');
            }
            if (!Schema::hasColumn('bookings', 'release_time')) {
                $table->timestamp('release_time')->nullable()->after('actual_finish_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'estimation_minutes',
                'estimated_finish_time',
                'actual_finish_time',
                'release_time',
            ]);
        });
    }
};
