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
                if (!Schema::hasColumn('bookings', 'status')) {
                    $table->string('status')->default('pending');
                }

                if (!Schema::hasColumn('bookings', 'estimation')) {
                    $table->string('estimation')->nullable();
                }
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

                if (Schema::hasColumn('bookings', 'estimation')) {
                    $table->dropColumn('estimation');
                }

                if (Schema::hasColumn('bookings', 'mechanic_notes')) {
                    $table->dropColumn('mechanic_notes');
                }
            });
        }

    };
