@extends('admin.layouts.app')

@php
  $mode = $mode ?? 'onsite';
  $showAddButton = $showAddButton ?? false;
@endphp

@section('title', $mode === 'online' ? 'Kunjungan Tiket Harian (Online)' : 'Kunjungan di Tempat')
@section('page_title', $mode === 'online' ? 'Kunjungan Tiket Harian (Online)' : 'Kunjungan di Tempat')

@section('content')
  <div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
      <div>
        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Membership</p>
      <h2 class="text-2xl font-semibold text-slate-900">
        {{ $mode === 'online' ? 'Transaksi Tiket Harian (Online)' : 'Transaksi Kunjungan di Tempat' }}
      </h2>
      </div>
      @if ($showAddButton)
        <a href="{{ route('admin.membership.visits.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-teal-700 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-teal-800">
          Tambah Kunjungan
        </a>
      @endif
    </div>

    @if (session('status'))
      <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ session('status') }}
      </div>
    @endif

    <form method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-center">
      <div class="relative w-full sm:max-w-md">
        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
            <circle cx="11" cy="11" r="7"></circle>
            <path d="M20 20l-3.5-3.5"></path>
          </svg>
        </span>
        <input type="search" name="q" value="{{ $search }}" placeholder="Cari nama, kontak, atau invoice" class="h-11 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-4 text-sm text-slate-700 focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
      </div>
      <button type="submit" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
        Filter
      </button>
    </form>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-400">
          <tr>
            <th class="px-4 py-3">Pengunjung</th>
            <th class="px-4 py-3">Kontak</th>
            <th class="px-4 py-3">Paket</th>
            <th class="px-4 py-3">Gender &amp; Qty</th>
            <th class="px-4 py-3">Invoice</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Tanggal</th>
            <th class="px-4 py-3 text-right">Total</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          @forelse ($transactions as $trx)
            @php
                 $paketName = '-';
                 $genderQty = '-';
                 $visitDate = $trx->created_at->format('d M Y');
                 
                 if ($trx->visitItems->isNotEmpty()) {
                     $item = $trx->visitItems->first();
                     $paketName = $item->memberType ? $item->memberType->name : 'Tiket Harian';
                     $genderQty = ucfirst($item->gender ?? '-') . ' • ' . $item->qty;
                     $visitDate = $item->visit_date ? \Carbon\Carbon::parse($item->visit_date)->format('d M Y') : $visitDate;
                 } elseif ($trx->membershipItems->isNotEmpty()) {
                     $item = $trx->membershipItems->first();
                     $paketName = $item->memberType ? $item->memberType->name : 'Membership';
                     $genderQty = 'Member • ' . $item->qty;
                 } elseif ($trx->eventRegistrations->isNotEmpty()) {
                     $item = $trx->eventRegistrations->first();
                     $paketName = $item->event ? $item->event->title : 'Event';
                     $genderQty = '- • ' . $item->qty;
                 }
            @endphp
            <tr class="text-slate-600">
              <td class="px-4 py-3">
                <p class="font-semibold text-slate-900">{{ $trx->customer_name ?? '-' }}</p>
                <p class="text-xs text-slate-400">{{ $trx->customer_email ?? '-' }}</p>
              </td>
              <td class="px-4 py-3">
                {{ $trx->customer_phone ?? '-' }}
              </td>
              <td class="px-4 py-3 font-medium">
                {{ $paketName }}
              </td>
              <td class="px-4 py-3">
                {{ $genderQty }}
              </td>
              <td class="px-4 py-3">
                  <a href="{{ route('admin.transactions.show', $trx) }}" class="text-teal-600 hover:underline font-medium">
                    {{ $trx->invoice_id }}
                  </a>
              </td>
              <td class="px-4 py-3">
                @if ($trx->status === 'paid')
                  <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Paid</span>
                @else
                  <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Pending</span>
                @endif
              </td>
              <td class="px-4 py-3">
                {{ $visitDate }}
              </td>
              <td class="px-4 py-3 text-right font-semibold text-slate-800">
                Rp {{ number_format($trx->amount_total ?? 0, 0, ',', '.') }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-4 py-10 text-center text-sm text-slate-500">
                {{ $mode === 'online'
                  ? 'Belum ada transaksi tiket harian online.'
                  : 'Belum ada transaksi di loket. Tambahkan data baru untuk mencatat penjualan di tempat.' }}
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div>
      {{ $transactions->links() }}
    </div>
  </div>
@endsection
