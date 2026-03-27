<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MemberTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Kunjungan Harian',
                'label' => 'Sekali Datang',
                'pricing' => 35000,
                'duration_days' => 1,
                'is_daily' => true,
                'is_student' => false,
                'order' => 1,
            ],
            [
                'name' => 'Keanggotaan Bulanan',
                'label' => 'Standard',
                'pricing' => 100000,
                'duration_days' => 30,
                'is_daily' => false,
                'is_student' => false,
                'order' => 2,
            ],
            [
                'name' => 'Keanggotaan 3 Bulan',
                'label' => 'Favorit',
                'pricing' => 250000,
                'duration_days' => 90,
                'is_daily' => false,
                'is_student' => false,
                'order' => 3,
            ],
            [
                'name' => 'Keanggotaan 1 Tahun',
                'label' => 'Paling Hemat',
                'pricing' => 800000,
                'duration_days' => 365,
                'is_daily' => false,
                'is_student' => false,
                'order' => 4,
            ],
            [
                'name' => 'Keanggotaan 1 Tahun Pelajar',
                'label' => 'Khusus Pelajar',
                'pricing' => 500000,
                'duration_days' => 365,
                'is_daily' => false,
                'is_student' => true,
                'order' => 5,
            ]
        ];

        foreach ($types as $type) {
            $memberType = \App\Models\MemberType::create($type);
            
            // Add some benefits
            $benefits = ['Akses Ruang Baca', 'Free Wifi', 'Diskon Event'];
            if ($memberType->is_daily) {
                $benefits = ['Akses Ruang Baca (1 Hari)', 'Free Wifi'];
            }
            
            foreach ($benefits as $b) {
                \App\Models\MemberTypeBenefit::create([
                    'member_type_id' => $memberType->id,
                    'label' => $b,
                ]);
            }
        }
    }
}
