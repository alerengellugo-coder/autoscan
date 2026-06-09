<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->constrained('service_orders')->cascadeOnDelete();
            $table->foreignId('technician_id')->constrained('users')->cascadeOnDelete();
            $table->date('report_date');
            $table->string('title')->nullable();
            $table->text('description');
            $table->text('work_performed')->nullable();
            $table->string('previous_status')->nullable();
            $table->string('new_status')->nullable();
            $table->text('findings')->nullable();
            $table->text('recommendations')->nullable();
            $table->json('parts_used')->nullable();
            $table->decimal('labor_hours', 5, 2)->nullable();
            $table->json('images')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('service_order_id');
            $table->index('technician_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_reports');
    }
};
