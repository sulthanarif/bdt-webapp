<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            [
                'label' => 'Total Member',
                'value' => '0',
                'delta' => '0%',
                'accent' => 'teal',
            ],
            [
                'label' => 'Member Aktif',
                'value' => '0',
                'delta' => '0%',
                'accent' => 'emerald',
            ],
            [
                'label' => 'Transaksi Hari Ini',
                'value' => '0',
                'delta' => '0%',
                'accent' => 'sky',
            ],
            [
                'label' => 'Pendapatan Hari Ini',
                'value' => 'Rp 0',
                'delta' => '0%',
                'accent' => 'amber',
            ],
            [
                'label' => 'Event Aktif',
                'value' => '0',
                'delta' => '0%',
                'accent' => 'violet',
            ],
            [
                'label' => 'Tiket Terpakai',
                'value' => '0',
                'delta' => '0%',
                'accent' => 'rose',
            ],
        ];

        $systemStatus = [
            [
                'label' => 'Koneksi SLiMS',
                'status' => 'Belum dikonfigurasi',
                'tone' => 'neutral',
            ],
            [
                'label' => 'Xendit Webhook',
                'status' => 'Menunggu callback',
                'tone' => 'warning',
            ],
            [
                'label' => 'Brevo Email',
                'status' => 'Belum terhubung',
                'tone' => 'neutral',
            ],
        ];

        $recentTransactions = [
            [
                'invoice' => 'INV-0001',
                'customer' => 'Guest Checkout',
                'type' => 'Kunjungan Harian',
                'status' => 'Menunggu',
                'amount' => 'Rp 0',
            ],
            [
                'invoice' => 'INV-0002',
                'customer' => 'Member Baru',
                'type' => 'Membership Tahunan',
                'status' => 'Menunggu',
                'amount' => 'Rp 0',
            ],
        ];

        $upcomingEvents = [
            [
                'title' => 'Belum ada event aktif',
                'date' => 'Tambah event baru',
                'status' => 'Draft',
            ],
        ];

        return view('admin.dashboard', compact(
            'stats',
            'systemStatus',
            'recentTransactions',
            'upcomingEvents'
        ));
    }
}
