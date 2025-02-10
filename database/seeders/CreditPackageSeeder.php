<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CreditPackage;  // Add this import

class CreditPackageSeeder extends Seeder
{
    public function run()
    {
        CreditPackage::create([
            'name' => 'Starter Pack',
            'description' => 'Perfect for beginners',
            'price' => 9.99,
            'credits' => 100,
            'features' => [
                '100 generation credits',
                'Up to 60 second duration',
                '30-day validity'
            ],
            'is_active' => true
        ]);

        CreditPackage::create([
            'name' => 'Pro Pack',
            'description' => 'For serious creators',
            'price' => 19.99,
            'credits' => 250,
            'features' => [
                '250 generation credits',
                'Up to 120 second duration',
                '60-day validity'
            ],
            'is_active' => true
        ]);

        CreditPackage::create([
            'name' => 'Ultimate Pack',
            'description' => 'Best value for professionals',
            'price' => 49.99,
            'credits' => 1000,
            'features' => [
                '1000 generation credits',
                'Unlimited duration',
                '90-day validity'
            ],
            'is_active' => true
        ]);
    }
}
