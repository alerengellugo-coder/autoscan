<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public $withinTransaction = false;

    /**
     * Run the migrations.
     *
     * Fixes column mismatch between model fillable and actual DB schema:
     * - Adds missing columns: description, discount_type, rejected_at, terms_and_conditions
     * - Renames tax_percentage to tax_rate to match model fillable
     */
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            // Rename tax_percentage -> tax_rate (model uses tax_rate)
            if (Schema::hasColumn('quotations', 'tax_percentage') && !Schema::hasColumn('quotations', 'tax_rate')) {
                DB::statement('ALTER TABLE quotations RENAME COLUMN tax_percentage TO tax_rate');
            }

            // Add description column (used by model and convertToSale)
            if (!Schema::hasColumn('quotations', 'description')) {
                $table->text('description')->nullable()->after('service_order_id');
            }

            // Add discount_type column (used by store and convertToSale)
            if (!Schema::hasColumn('quotations', 'discount_type')) {
                $table->string('discount_type')->default('percentage')->after('discount');
            }

            // Add rejected_at column (used by reject() method)
            if (!Schema::hasColumn('quotations', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('approved_at');
            }

            // Add terms_and_conditions column (in model fillable)
            if (!Schema::hasColumn('quotations', 'terms_and_conditions')) {
                $table->text('terms_and_conditions')->nullable()->after('notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'discount_type',
                'rejected_at',
                'terms_and_conditions',
            ]);

            // Rename back
            DB::statement('ALTER TABLE quotations RENAME COLUMN tax_rate TO tax_percentage');
        });
    }
};
