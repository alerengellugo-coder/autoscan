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
     * Fixes column mismatch between model fillable and actual DB schema for
     * quotations AND sales tables. Also makes quotation_items.name and
     * sale_items.name nullable so item creation doesn't require a name.
     */
    public function up(): void
    {
        // ─── quotations table ───
        Schema::table('quotations', function (Blueprint $table) {
            if (Schema::hasColumn('quotations', 'tax_percentage') && !Schema::hasColumn('quotations', 'tax_rate')) {
                DB::statement('ALTER TABLE quotations RENAME COLUMN tax_percentage TO tax_rate');
            }
            if (!Schema::hasColumn('quotations', 'description')) {
                $table->text('description')->nullable()->after('service_order_id');
            }
            if (!Schema::hasColumn('quotations', 'discount_type')) {
                $table->string('discount_type')->default('percentage')->after('discount');
            }
            if (!Schema::hasColumn('quotations', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('approved_at');
            }
            if (!Schema::hasColumn('quotations', 'terms_and_conditions')) {
                $table->text('terms_and_conditions')->nullable()->after('notes');
            }
        });

        // ─── sales table ───
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'tax_rate')) {
                $table->decimal('tax_rate', 5, 3)->default(16)->after('subtotal');
            }
            if (!Schema::hasColumn('sales', 'discount_type')) {
                $table->string('discount_type')->default('percentage')->after('discount');
            }
            if (!Schema::hasColumn('sales', 'description')) {
                $table->text('description')->nullable()->after('quotation_id');
            }
            if (!Schema::hasColumn('sales', 'notes')) {
                $table->text('notes')->nullable()->after('paid_at');
            }
        });

        // ─── quotation_items: make name nullable ───
        if (Schema::hasColumn('quotation_items', 'name')) {
            DB::statement('ALTER TABLE quotation_items ALTER COLUMN name DROP NOT NULL');
            DB::statement("ALTER TABLE quotation_items ALTER COLUMN name SET DEFAULT ''");
        }

        // ─── sale_items: make name nullable ───
        if (Schema::hasColumn('sale_items', 'name')) {
            DB::statement('ALTER TABLE sale_items ALTER COLUMN name DROP NOT NULL');
            DB::statement("ALTER TABLE sale_items ALTER COLUMN name SET DEFAULT ''");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['tax_rate', 'discount_type', 'description', 'notes']);
        });

        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'discount_type',
                'rejected_at',
                'terms_and_conditions',
            ]);
            DB::statement('ALTER TABLE quotations RENAME COLUMN tax_rate TO tax_percentage');
        });

        DB::statement('ALTER TABLE quotation_items ALTER COLUMN name SET NOT NULL');
        DB::statement('ALTER TABLE sale_items ALTER COLUMN name SET NOT NULL');
    }
};
