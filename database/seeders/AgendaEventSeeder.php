<?php

namespace Database\Seeders;

use App\Models\AgendaEvent;
use Illuminate\Database\Seeder;

class AgendaEventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title' => 'LIBERATING THE BODY POLITIC',
                'subtitle' => 'Decolonizing Minds, Movements, and Motherlands',
                'category_label' => 'BINCANG PUBLIK',
                'category_style' => 'teal',
                'status_label' => 'Event selesai',
                'image_url' => 'images/liberating-the-body-poltics.jpg',
                'image_alt' => 'Poster Liberating The Body Politic',
                'date_label' => 'Kamis, 15 Januari 2026 - 15.30 - 18.00 WIB',
                'location_label' => 'Baca Di Tebet (Jl. Tebet Barat Dalam Raya No. 29)',
                'description' => 'Diskusi publik internasional ini mempertemukan beragam perspektif lintas wilayah dan pengalaman untuk merefleksikan dekolonisasi. Menghadirkan Kanti W. Janis, Zayaan Shaafiu, Fanda Puspitasari, dan Christophe Dorigne-Thomson.',
                'cta_label' => 'Pendaftaran Ditutup',
                'cta_url' => 'https://bit.ly/BDT1501',
                'cta_style' => 'teal',
                'price' => null,
                'order' => 1,
                'is_active' => true,
                'is_finished' => true,
            ],
            [
                'title' => 'INSPIRASI KARTINI & KESETARAAN',
                'subtitle' => 'Karya Prof. Dr. Wardiman Djojonegoro',
                'category_label' => 'BEDAH BUKU',
                'category_style' => 'slate',
                'status_label' => 'Event selesai',
                'image_url' => 'images/inspirasi-kartini-dan%20kesetaraan-gender.jpg',
                'image_alt' => 'Poster Inspirasi Kartini',
                'date_label' => 'Jumat, 16 Januari 2026 - 14.00 - 17.00 WIB',
                'location_label' => 'Baca Di Tebet (Perpustakaan & Ruang Temu)',
                'description' => 'Menyelami Kartini sebagai pemikir yang relevan hingga hari ini. Bersama Kanti W. Janis, Stebby Julionatan, serta pembacaan surat oleh Mia Ismi dan Rizqika Arrum B.',
                'cta_label' => 'Pendaftaran Ditutup',
                'cta_url' => 'https://bit.ly/Kartini160126',
                'cta_style' => 'slate',
                'price' => null,
                'order' => 2,
                'is_active' => true,
                'is_finished' => true,
            ],
        ];

        foreach ($events as $payload) {
            AgendaEvent::query()->updateOrCreate(
                ['title' => $payload['title']],
                $payload
            );
        }
    }
}
