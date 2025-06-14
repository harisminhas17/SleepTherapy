<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Basic',
                'description' => 'Essential sleep tracking and basic features',
                'price' => 9.99,
                'duration' => 30, // 30 days
                'is_active' => 1,
                'key_features' => 'Essential sleep tracking and basic features',
            ],
            [
                'name' => 'Pro',
                'description' => 'Advanced sleep analytics and premium features',
                'price' => 19.99,
                'duration' => 30, // 30 days
                'is_active' => 1,
                'key_features' => 'Advanced sleep analytics and premium features',
            ],
            [
                'name' => 'Premium',
                'description' => 'Complete sleep solution with all features and priority support',
                'price' => 29.99,
                'duration' => 30, // 30 days
                'is_active' => 1,
                'key_features' => 'Complete sleep solution with all features and priority support',
            ],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}
