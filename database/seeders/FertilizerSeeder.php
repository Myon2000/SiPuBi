<?php

namespace Database\Seeders;

use App\Models\Fertilizer;
use Illuminate\Database\Seeder;

class FertilizerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fertilizers = [
            [
                'name' => 'Urea',
                'description' => 'Pupuk Urea mengandung nitrogen tinggi (46%) yang baik untuk pertumbuhan vegetatif tanaman.',
                'price' => 15000,
                'current_stock' => 1000,
                'minimum_stock' => 200,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'NPK',
                'description' => 'Pupuk NPK mengandung unsur Nitrogen, Fosfor, dan Kalium yang lengkap untuk tanaman.',
                'price' => 12000,
                'current_stock' => 800,
                'minimum_stock' => 150,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Organik',
                'description' => 'Pupuk Organik dibuat dari bahan-bahan alami yang ramah lingkungan dan menyuburkan tanah.',
                'price' => 10000,
                'current_stock' => 1500,
                'minimum_stock' => 300,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Hapus data yang sudah ada jika diperlukan
        // Fertilizer::truncate();

        // Insert data baru
        foreach ($fertilizers as $fertilizer) {
            Fertilizer::firstOrCreate(
                ['name' => $fertilizer['name']],
                $fertilizer
            );
        }

        $this->command->info('Fertilizer data seeded successfully!');
    }
}