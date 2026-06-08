<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SampleSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates default application settings used throughout the system.
     */
    public function run(): void
    {
        $settings = [
            [
                'key'   => 'workshop_name',
                'value' => 'AutoScan Taller Electromecánico',
                'group' => 'general',
            ],
            [
                'key'   => 'workshop_address',
                'value' => 'Av. Principal #123',
                'group' => 'general',
            ],
            [
                'key'   => 'workshop_phone',
                'value' => '+58 412-1234567',
                'group' => 'general',
            ],
            [
                'key'   => 'workshop_email',
                'value' => 'info@autoscan.com',
                'group' => 'general',
            ],
            [
                'key'   => 'tax_percentage',
                'value' => '16',
                'group' => 'billing',
            ],
            [
                'key'   => 'currency',
                'value' => 'USD',
                'group' => 'billing',
            ],
            [
                'key'   => 'default_priority',
                'value' => 'normal',
                'group' => 'orders',
            ],
            [
                'key'   => 'auto_notify_clients',
                'value' => '1',
                'group' => 'notifications',
            ],
            [
                'key'   => 'low_stock_alert',
                'value' => '1',
                'group' => 'inventory',
            ],
            [
                'key'   => 'quotation_validity_days',
                'value' => '15',
                'group' => 'billing',
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'group' => $setting['group'],
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('✅ Default settings seeded successfully (' . count($settings) . ' settings).');
    }
}
