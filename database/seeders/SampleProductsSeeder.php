<?php

namespace Database\Seeders;

use App\Models\Enums\ProductCategory;
use App\Models\Product;
use Illuminate\Database\Seeder;

class SampleProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates ~15 sample products across various categories for the workshop.
     */
    public function run(): void
    {
        $products = [
            [
                'name'        => 'Aceite sintético 5W-30',
                'description' => 'Aceite de motor sintético de alta calidad. Recomendado para vehículos modernos. Proporciona protección superior en temperaturas extremas y reduce el desgaste del motor.',
                'sku'         => 'ACE-5W30-4L',
                'category'    => ProductCategory::Oil,
                'price'       => 28.50,
                'cost'        => 18.00,
                'stock'       => 25,
                'min_stock'   => 5,
                'unit'        => 'galón',
                'is_active'   => true,
            ],
            [
                'name'        => 'Filtro de aceite universal',
                'description' => 'Filtro de aceite de alta eficiencia compatible con la mayoría de los vehículos. Filtra impurezas y protege el motor.',
                'sku'         => 'FIL-ACE-UNI',
                'category'    => ProductCategory::Filter,
                'price'       => 12.00,
                'cost'        => 6.50,
                'stock'       => 40,
                'min_stock'   => 10,
                'unit'        => 'unidad',
                'is_active'   => true,
            ],
            [
                'name'        => 'Filtro de aire',
                'description' => 'Filtro de aire de alto rendimiento. Mejora la eficiencia del motor al garantizar un flujo de aire limpio.',
                'sku'         => 'FIL-AIR-STD',
                'category'    => ProductCategory::Filter,
                'price'       => 15.00,
                'cost'        => 8.00,
                'stock'       => 35,
                'min_stock'   => 8,
                'unit'        => 'unidad',
                'is_active'   => true,
            ],
            [
                'name'        => 'Pastillas de freno cerámicas',
                'description' => 'Set de pastillas de freno cerámicas para eje delantero. Ofrecen mejor frenado, menor ruido y menor desgaste del disco.',
                'sku'         => 'FRE-PAD-CER',
                'category'    => ProductCategory::Brake,
                'price'       => 45.00,
                'cost'        => 25.00,
                'stock'       => 20,
                'min_stock'   => 5,
                'unit'        => 'juego',
                'is_active'   => true,
            ],
            [
                'name'        => 'Disco de freno ventilado',
                'description' => 'Disco de freno ventilado de alta calidad. Disipa mejor el calor y mejora el rendimiento de frenado.',
                'sku'         => 'FRE-DIS-VEN',
                'category'    => ProductCategory::Brake,
                'price'       => 55.00,
                'cost'        => 32.00,
                'stock'       => 15,
                'min_stock'   => 4,
                'unit'        => 'unidad',
                'is_active'   => true,
            ],
            [
                'name'        => 'Batería 12V 60Ah',
                'description' => 'Batería de plomo-ácido libre de mantenimiento. Ideal para la mayoría de vehículos compactos y medianos.',
                'sku'         => 'BAT-12V-60',
                'category'    => ProductCategory::Battery,
                'price'       => 85.00,
                'cost'        => 55.00,
                'stock'       => 10,
                'min_stock'   => 3,
                'unit'        => 'unidad',
                'is_active'   => true,
            ],
            [
                'name'        => 'Batería AGM 12V 70Ah',
                'description' => 'Batería AGM de ciclo profundo. Mayor durabilidad y resistencia a vibraciones. Ideal para vehículos con alto consumo eléctrico.',
                'sku'         => 'BAT-AGM-70',
                'category'    => ProductCategory::Battery,
                'price'       => 140.00,
                'cost'        => 90.00,
                'stock'       => 8,
                'min_stock'   => 2,
                'unit'        => 'unidad',
                'is_active'   => true,
            ],
            [
                'name'        => 'Escáner OBD2 profesional',
                'description' => 'Escáner de diagnóstico OBD2 de grado profesional. Lee y borra códigos de falla, muestra datos en tiempo real y es compatible con todos los protocolos.',
                'sku'         => 'SCN-OBD2-PRO',
                'category'    => ProductCategory::ScanTool,
                'price'       => 120.00,
                'cost'        => 75.00,
                'stock'       => 5,
                'min_stock'   => 2,
                'unit'        => 'unidad',
                'is_active'   => true,
            ],
            [
                'name'        => 'Multímetro digital',
                'description' => 'Multímetro digital de rango automático. Mide voltaje, corriente, resistencia y continuidad. Esencial para diagnóstico eléctrico automotriz.',
                'sku'         => 'ELE-MUL-DIG',
                'category'    => ProductCategory::Electrical,
                'price'       => 35.00,
                'cost'        => 18.00,
                'stock'       => 12,
                'min_stock'   => 3,
                'unit'        => 'unidad',
                'is_active'   => true,
            ],
            [
                'name'        => 'Cable de batería (juego)',
                'description' => 'Juego de cables de batería para arranque auxiliar. Cable de cobre con pinzas reforzadas. Longitud: 3 metros.',
                'sku'         => 'ELE-CAB-BAT',
                'category'    => ProductCategory::Electrical,
                'price'       => 22.00,
                'cost'        => 12.00,
                'stock'       => 18,
                'min_stock'   => 5,
                'unit'        => 'juego',
                'is_active'   => true,
            ],
            [
                'name'        => 'Kit de herramientas básico',
                'description' => 'Kit de 45 piezas que incluye llaves, destornilladores, alicates y socket. Ideal para trabajos básicos de mantenimiento automotriz.',
                'sku'         => 'ACC-KIT-45P',
                'category'    => ProductCategory::Accessory,
                'price'       => 65.00,
                'cost'        => 38.00,
                'stock'       => 8,
                'min_stock'   => 2,
                'unit'        => 'juego',
                'is_active'   => true,
            ],
            [
                'name'        => 'Coolant / Refrigerante verde',
                'description' => 'Refrigerante de motor verde concentrado. Protege contra la congelación y la corrosión. Mezcla recomendada 50/50 con agua destilada.',
                'sku'         => 'OTH-COL-GRN',
                'category'    => ProductCategory::Other,
                'price'       => 8.50,
                'cost'        => 4.00,
                'stock'       => 50,
                'min_stock'   => 15,
                'unit'        => 'galón',
                'is_active'   => true,
            ],
            [
                'name'        => 'Líquido de frenos DOT4',
                'description' => 'Líquido de frenos de alto rendimiento DOT4. Punto de ebullición elevado, compatible con la mayoría de los sistemas de frenos.',
                'sku'         => 'OTH-FRE-DOT4',
                'category'    => ProductCategory::Other,
                'price'       => 6.00,
                'cost'        => 3.00,
                'stock'       => 60,
                'min_stock'   => 15,
                'unit'        => 'litro',
                'is_active'   => true,
            ],
            [
                'name'        => 'Bujía de encendido iridium',
                'description' => 'Bujía de encendido con electrodo de iridium. Mayor vida útil, mejor combustión y arranque más rápido. Venta individual.',
                'sku'         => 'OTH-BUJ-IRD',
                'category'    => ProductCategory::Other,
                'price'       => 12.00,
                'cost'        => 7.00,
                'stock'       => 100,
                'min_stock'   => 25,
                'unit'        => 'unidad',
                'is_active'   => true,
            ],
            [
                'name'        => 'Correa de distribución',
                'description' => 'Correa de distribución de alta resistencia. Verificar compatibilidad con el modelo del vehículo antes de la instalación.',
                'sku'         => 'OTH-COR-DIST',
                'category'    => ProductCategory::Other,
                'price'       => 38.00,
                'cost'        => 20.00,
                'stock'       => 12,
                'min_stock'   => 3,
                'unit'        => 'unidad',
                'is_active'   => true,
            ],
        ];

        foreach ($products as $productData) {
            Product::firstOrCreate(
                ['sku' => $productData['sku']],
                $productData
            );
        }

        $this->command->info('✅ ' . count($products) . ' sample products seeded successfully.');
    }
}
