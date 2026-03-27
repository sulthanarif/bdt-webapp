<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMember = \App\Models\Member::count();
        $activeMember = \App\Models\Member::where('status', 'active')->where('expired_at', '>=', now())->count();
        $todayTransactions = \App\Models\Transaction::whereDate('created_at', today())->count();
        $todayRevenue = \App\Models\Transaction::where('status', 'paid')->whereDate('paid_at', today())->sum('amount_total');
        $activeEvent = \App\Models\AgendaEvent::where('is_active', true)->whereDate('ends_at', '>=', today())->count();
        $ticketsUsed = \App\Models\TransactionVisit::whereDate('visit_date', today())->sum('qty');

        $stats = [
            [
                'label' => 'Total Member',
                'value' => number_format($totalMember, 0, ',', '.'),
                'delta' => 'Real-time',
                'accent' => 'teal',
            ],
            [
                'label' => 'Member Aktif',
                'value' => number_format($activeMember, 0, ',', '.'),
                'delta' => 'Hari ini',
                'accent' => 'emerald',
            ],
            [
                'label' => 'Transaksi Hari Ini',
                'value' => number_format($todayTransactions, 0, ',', '.'),
                'delta' => 'Hari ini',
                'accent' => 'sky',
            ],
            [
                'label' => 'Pendapatan Hari Ini',
                'value' => 'Rp ' . number_format($todayRevenue, 0, ',', '.'),
                'delta' => 'Hari ini',
                'accent' => 'amber',
            ],
            [
                'label' => 'Event Aktif',
                'value' => number_format($activeEvent, 0, ',', '.'),
                'delta' => 'Saat ini',
                'accent' => 'violet',
            ],
            [
                'label' => 'Tiket Terpakai',
                'value' => number_format($ticketsUsed, 0, ',', '.'),
                'delta' => 'Hari ini',
                'accent' => 'rose',
            ],
        ];

        $xenditStatus = config('services.xendit.webhook_token') ? 'Aktif' : 'Menunggu callback';
        $xenditTone = config('services.xendit.webhook_token') ? 'success' : 'warning';

        $systemStatus = [
            [
                'label' => 'Koneksi SLiMS',
                'status' => 'Belum dikonfigurasi',
                'tone' => 'neutral',
            ],
            [
                'label' => 'Xendit Webhook',
                'status' => $xenditStatus,
                'tone' => $xenditTone,
            ],
            [
                'label' => 'Brevo Email',
                'status' => 'Belum terhubung',
                'tone' => 'neutral',
            ],
        ];

        $recentTransactions = \App\Models\Transaction::with(['membershipItems.memberType', 'visitItems.memberType', 'eventRegistrations.event'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($t) {
                $type = 'Transaksi Umum';
                if ($t->membershipItems->isNotEmpty()) {
                    $type = 'Membership: ' . $t->membershipItems->first()->memberType->name;
                } elseif ($t->visitItems->isNotEmpty()) {
                    $type = 'Kunjungan: ' . $t->visitItems->first()->memberType->name;
                } elseif ($t->eventRegistrations->isNotEmpty()) {
                    $type = 'Event: ' . $t->eventRegistrations->first()->event->title;
                }

                $statusLabel = $t->status === 'paid' ? 'Selesai' : 'Menunggu';
                return [
                    'invoice' => $t->invoice_id,
                    'customer' => $t->customer_name ?: 'Guest',
                    'type' => $type,
                    'status' => $statusLabel,
                    'amount' => 'Rp ' . number_format($t->amount_total, 0, ',', '.'),
                ];
            });

        $upcomingEvents = \App\Models\AgendaEvent::whereDate('starts_at', '>=', today())
            ->where('is_active', true)
            ->orderBy('starts_at', 'asc')
            ->take(3)
            ->get()
            ->map(function ($e) {
                return [
                    'title' => $e->title,
                    'date' => \Carbon\Carbon::parse($e->starts_at)->format('d M Y, H:i'),
                    'status' => 'Mendatang',
                ];
            });

        if ($upcomingEvents->isEmpty()) {
            $upcomingEvents = collect([[
                'title' => 'Belum ada event aktif',
                'date' => 'Tambah event baru',
                'status' => 'Draft',
            ]]);
        }

        $chartData = collect();
        $maxRevenue = 1;
        if (\App\Models\Transaction::where('status', 'paid')->exists()) {
            $revenueByDay = \App\Models\Transaction::where('status', 'paid')
                ->whereDate('paid_at', '>=', now()->subDays(29))
                ->selectRaw('DATE(paid_at) as date, SUM(amount_total) as total')
                ->groupBy('date')
                ->pluck('total', 'date');

            for ($i = 29; $i >= 0; $i--) {
                $dateString = now()->subDays($i)->format('Y-m-d');
                $total = $revenueByDay->get($dateString, 0);
                $chartData->push(['date' => $dateString, 'total' => $total]);
                if ($total > $maxRevenue) {
                    $maxRevenue = $total;
                }
            }
        } else {
            for ($i = 29; $i >= 0; $i--) {
                $chartData->push(['date' => now()->subDays($i)->format('Y-m-d'), 'total' => 0]);
            }
        }

        return view('admin.dashboard', compact(
            'stats',
            'systemStatus',
            'recentTransactions',
            'upcomingEvents',
            'chartData',
            'maxRevenue'
        ));
    }
}
