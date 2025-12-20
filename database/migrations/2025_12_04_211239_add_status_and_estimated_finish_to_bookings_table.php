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
            $table->string('status')->default('pending'); // pending, proses, selesai, dibatalkan
            $table->string('estimated_finish')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('bookings', 'estimated_finish')) {
                $table->dropColumn('estimated_finish');
            }
        });
    }

};
