<?php

namespace Database\Seeders;

use App\Models\ServiceOrder;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $client = User::where('email', 'carlos.garcia@email.com')->first();
        $vehicle = Vehicle::where('plate', 'ABC-123')->first();
        $tech = User::where('email', 'tech@autoscan.com')->first();

        if (! $client || ! $vehicle || ! $tech) {
            $this->command->warn('SampleDataSeeder: skipping, required users/vehicles not found.');
            return;
        }

        // ─── Service Orders ───
        $order1 = ServiceOrder::create([
            'vehicle_id'     => $vehicle->id,
            'client_id'      => $client->id,
            'technician_id'  => $tech->id,
            'service_type'   => 'diagnostic',
            'description'    => 'El vehículo presenta ruido en la suspensión delantera al pasar por baches.',
            'status'         => 'in_progress',
            'priority'       => 'normal',
            'estimated_cost' => 500.00,
        ]);

        $order2 = ServiceOrder::create([
            'vehicle_id'     => $vehicle->id,
            'client_id'      => $client->id,
            'technician_id'  => $tech->id,
            'service_type'   => 'maintenance',
            'description'    => 'Cambio de aceite y filtro de aire según kilometraje.',
            'status'         => 'pending',
            'priority'       => 'low',
            'estimated_cost' => 150.00,
        ]);

        // ─── Quotation (from order1) ───
        $quotation = Quotation::create([
            'client_id'    => $client->id,
            'vehicle_id'   => $vehicle->id,
            'service_order_id' => $order1->id,
            'description'  => 'Cotización para reparación de suspensión delantera.',
            'status'       => 'pending_client',
            'subtotal'     => 450.00,
            'tax_rate'     => 16.000,
            'tax'          => 72.00,
            'discount'     => 0,
            'discount_type'=> 'percentage',
            'total'        => 522.00,
            'valid_until'  => now()->addDays(15),
        ]);

        QuotationItem::create([
            'quotation_id' => $quotation->id,
            'item_type'    => 'product',
            'name'         => 'Amortiguador delantero Izq.',
            'description'  => 'Amortiguador Monroe para Toyota Corolla 2021',
            'quantity'     => 1,
            'unit_price'   => 180.00,
            'discount'     => 0,
            'total'        => 180.00,
        ]);

        QuotationItem::create([
            'quotation_id' => $quotation->id,
            'item_type'    => 'product',
            'name'         => 'Amortiguador delantero Der.',
            'description'  => 'Amortiguador Monroe para Toyota Corolla 2021',
            'quantity'     => 1,
            'unit_price'   => 180.00,
            'discount'     => 0,
            'total'        => 180.00,
        ]);

        QuotationItem::create([
            'quotation_id' => $quotation->id,
            'item_type'    => 'labor',
            'name'         => 'Mano de obra - Instalación suspensión',
            'description'  => 'Instalación de amortiguadores delanteros + alineación',
            'quantity'     => 1,
            'unit_price'   => 90.00,
            'discount'     => 0,
            'total'        => 90.00,
        ]);

        $this->command->info('Sample data seeded: 2 orders, 1 quotation with 3 items.');
    }
}
