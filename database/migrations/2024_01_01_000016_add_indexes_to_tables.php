<?php

namespace App\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Add composite and additional indexes for query performance optimization.
     */
    public function up(): void
    {
        // Vehicles: composite index for client + status lookups
        Schema::table('vehicles', function (Blueprint $table) {
            $table->index(['client_id', 'status'], 'vehicles_client_status_index');
        });

        // Service Orders: composite indexes for common filter combinations
        Schema::table('service_orders', function (Blueprint $table) {
            $table->index(['status', 'priority'], 'service_orders_status_priority_index');
            $table->index(['client_id', 'status'], 'service_orders_client_status_index');
            $table->index(['technician_id', 'status'], 'service_orders_technician_status_index');
            $table->index(['vehicle_id', 'status'], 'service_orders_vehicle_status_index');
            $table->index(['created_at', 'status'], 'service_orders_created_status_index');
        });

        // Quotations: composite indexes for client + status filtering
        Schema::table('quotations', function (Blueprint $table) {
            $table->index(['client_id', 'status'], 'quotations_client_status_index');
            $table->index(['status', 'created_at'], 'quotations_status_created_index');
        });

        // Sales: composite indexes for reporting and filtering
        Schema::table('sales', function (Blueprint $table) {
            $table->index(['client_id', 'status'], 'sales_client_status_index');
            $table->index(['status', 'created_at'], 'sales_status_created_index');
            $table->index(['payment_method', 'status'], 'sales_payment_status_index');
        });

        // Products: composite index for category + active products
        Schema::table('products', function (Blueprint $table) {
            $table->index(['category', 'is_active'], 'products_category_active_index');
            $table->index(['is_active', 'stock_quantity'], 'products_active_stock_index');
        });

        // Service Reports: composite index for order + technician lookups
        Schema::table('service_reports', function (Blueprint $table) {
            $table->index(['service_order_id', 'created_at'], 'service_reports_order_created_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropIndex(['client_id', 'status']);
        });

        Schema::table('service_orders', function (Blueprint $table) {
            $table->dropIndex('service_orders_status_priority_index');
            $table->dropIndex('service_orders_client_status_index');
            $table->dropIndex('service_orders_technician_status_index');
            $table->dropIndex('service_orders_vehicle_status_index');
            $table->dropIndex('service_orders_created_status_index');
        });

        Schema::table('quotations', function (Blueprint $table) {
            $table->dropIndex('quotations_client_status_index');
            $table->dropIndex('quotations_status_created_index');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex('sales_client_status_index');
            $table->dropIndex('sales_status_created_index');
            $table->dropIndex('sales_payment_status_index');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_category_active_index');
            $table->dropIndex('products_active_stock_index');
        });

        Schema::table('service_reports', function (Blueprint $table) {
            $table->dropIndex('service_reports_order_created_index');
        });
    }
};
