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

            if (!Schema::hasColumn('bookings', 'mechanic_id')) {
                $table->foreignId('mechanic_id')
                      ->nullable()
                      ->constrained('mechanics')
                      ->nullOnDelete()
                      ->after('email');
            }

            if (!Schema::hasColumn('bookings', 'status')) {
                $table->string('status')->default('pending');
            }

            if (!Schema::hasColumn('bookings', 'estimation')) {
                $table->string('estimation')->nullable();
            }

            if (!Schema::hasColumn('bookings', 'estimated_finish')) {
                $table->string('estimated_finish')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'mechanic_id')) {
                $table->dropConstrainedForeignId('mechanic_id');
            }

            $table->dropColumn([
                'status',
                'estimation',
                'estimated_finish',
            ]);
        });
    }
};