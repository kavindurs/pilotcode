<?php
// Run: php artisan make:migration add_genie_business_fields_to_payments_table

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Check if columns exist before adding them
            if (!Schema::hasColumn('payments', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }

            if (!Schema::hasColumn('payments', 'property_id')) {
                $table->unsignedBigInteger('property_id')->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('payments', 'payment_method')) {
                $table->string('payment_method')->default('genie_business')->after('status');
            }

            if (!Schema::hasColumn('payments', 'genie_customer_id')) {
                $table->string('genie_customer_id')->nullable()->after('payment_method');
            }

            if (!Schema::hasColumn('payments', 'genie_transaction_id')) {
                $table->string('genie_transaction_id')->nullable()->after('genie_customer_id');
            }

            if (!Schema::hasColumn('payments', 'payment_url')) {
                $table->text('payment_url')->nullable()->after('genie_transaction_id');
            }

            if (!Schema::hasColumn('payments', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('payment_url');
            }

            if (!Schema::hasColumn('payments', 'customer_email')) {
                $table->string('customer_email')->nullable()->after('customer_name');
            }

            if (!Schema::hasColumn('payments', 'tokenize')) {
                $table->boolean('tokenize')->default(false)->after('customer_email');
            }

            if (!Schema::hasColumn('payments', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('tokenize');
            }
        });

        // Add indexes separately to avoid conflicts
        try {
            DB::statement('CREATE INDEX payments_user_id_status_index ON payments (user_id, status)');
        } catch (\Exception $e) {
            // Index might already exist
        }

        try {
            DB::statement('CREATE INDEX payments_genie_transaction_id_index ON payments (genie_transaction_id)');
        } catch (\Exception $e) {
            // Index might already exist
        }

        try {
            DB::statement('CREATE INDEX payments_business_email_index ON payments (business_email)');
        } catch (\Exception $e) {
            // Index might already exist
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Drop indexes first
            try {
                $table->dropIndex(['user_id', 'status']);
            } catch (\Exception $e) {
                // Index might not exist
            }

            try {
                $table->dropIndex(['genie_transaction_id']);
            } catch (\Exception $e) {
                // Index might not exist
            }

            try {
                $table->dropIndex(['business_email']);
            } catch (\Exception $e) {
                // Index might not exist
            }

            // Drop columns if they exist
            $columnsToRemove = [
                'user_id',
                'property_id',
                'payment_method',
                'genie_customer_id',
                'genie_transaction_id',
                'payment_url',
                'customer_name',
                'customer_email',
                'tokenize',
                'completed_at'
            ];

            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('payments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};




/*
php artisan tinker
<?php
use Illuminate\Support\Facades\DB;

// Add the new columns manually
DB::statement("ALTER TABLE payments ADD COLUMN user_id BIGINT UNSIGNED NULL AFTER id");
DB::statement("ALTER TABLE payments ADD COLUMN property_id BIGINT UNSIGNED NULL AFTER user_id");
DB::statement("ALTER TABLE payments ADD COLUMN payment_method VARCHAR(255) DEFAULT 'genie_business' AFTER status");
DB::statement("ALTER TABLE payments ADD COLUMN genie_customer_id VARCHAR(255) NULL AFTER payment_method");
DB::statement("ALTER TABLE payments ADD COLUMN genie_transaction_id VARCHAR(255) NULL AFTER genie_customer_id");
DB::statement("ALTER TABLE payments ADD COLUMN payment_url TEXT NULL AFTER genie_transaction_id");
DB::statement("ALTER TABLE payments ADD COLUMN customer_name VARCHAR(255) NULL AFTER payment_url");
DB::statement("ALTER TABLE payments ADD COLUMN customer_email VARCHAR(255) NULL AFTER customer_name");
DB::statement("ALTER TABLE payments ADD COLUMN tokenize BOOLEAN DEFAULT FALSE AFTER customer_email");
DB::statement("ALTER TABLE payments ADD COLUMN completed_at TIMESTAMP NULL AFTER tokenize");

// Add indexes
DB::statement("CREATE INDEX payments_user_id_status_index ON payments (user_id, status)");
DB::statement("CREATE INDEX payments_genie_transaction_id_index ON payments (genie_transaction_id)");
DB::statement("CREATE INDEX payments_business_email_index ON payments (business_email)");

exit;

*/
