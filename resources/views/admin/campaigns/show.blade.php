@extends('admin.layouts.app')

@section('title', 'Detail Campaign & Penggunaan')
@section('page_title', 'Detail Campaign')

@section('content')
  <div class="space-y-6">
    <div class="flex items-center gap-4">
      <a href="{{ route('admin.campaigns.index') }}" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-50">
        <i data-lucide="arrow-left" class="h-5 w-5"></i>
      </a>
      <div>
        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Pemasaran</p>
        <h2 class="text-2xl font-semibold text-slate-900">Detail Campaign</h2>
      </div>
    </div>

    <!-- Campaign Header Info -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
          <h3 class="text-xl font-bold text-slate-900">{{ $campaign->name }}</h3>
          @if($campaign->code)
            <div class="mt-2 inline-flex items-center gap-1 rounded bg-slate-100 px-3 py-1 font-mono text-sm font-medium text-slate-700">
              <i data-lucide="ticket" class="h-4 w-4"></i>
              {{ $campaign->code }}
            </div>
          @else
            <div class="mt-2 inline-flex items-center gap-1 rounded bg-teal-50 px-3 py-1 text-sm font-medium text-teal-700">
              <i data-lucide="zap" class="h-4 w-4"></i>
              Promo Otomatis
            </div>
          @endif
        </div>

        <div class="flex gap-4">
          <div class="text-right">
            <p class="text-xs text-slate-500 mb-1">Total Digunakan</p>
            <p class="text-2xl font-bold text-slate-900">{{ $campaign->current_uses }} <span class="text-sm font-normal text-slate-500">kali</span></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Usage History Table -->
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="border-b border-slate-200 px-6 py-4">
        <h4 class="font-semibold text-slate-900">Riwayat Penggunaan Promo</h4>
      </div>
      
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
          <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
            <tr>
              <th scope="col" class="px-6 py-4 font-semibold">Tanggal & Invoice</th>
              <th scope="col" class="px-6 py-4 font-semibold">Pelanggan</th>
              <th scope="col" class="px-6 py-4 font-semibold">Kategori Transaksi</th>
              <th scope="col" class="px-6 py-4 font-semibold text-right">Potongan yang Didapat</th>
              <th scope="col" class="px-6 py-4 font-semibold text-center">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200 bg-white">
            @forelse ($transactions as $transaction)
              <tr class="hover:bg-slate-50">
                <td class="px-6 py-4">
                  <div class="font-medium text-slate-900">{{ $transaction->created_at->format('d M Y, H:i') }}</div>
                  <a href="{{ route('admin.transactions.show', $transaction) }}" class="text-xs text-teal-600 hover:underline">
                    {{ $transaction->invoice_id }}
                  </a>
                </td>
                <td class="px-6 py-4">
                  <div class="font-medium text-slate-800">{{ $transaction->customer_name }}</div>
                  <div class="text-xs text-slate-500">{{ $transaction->customer_email ?? $transaction->customer_phone ?? '-' }}</div>
                </td>
                <td class="px-6 py-4">
                  @php
                      $categoryHtml = '';
                      $itemName = '-';
                      
                      if ($transaction->membershipItems->isNotEmpty()) {
                          $item = $transaction->membershipItems->first();
                          $itemName = $item->memberType ? $item->memberType->name : 'Paket Membership';
                          
                          if ($transaction->is_renewal === true) {
                              $categoryHtml = '<span class="inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-800 mb-1">Perpanjangan Member</span>';
                          } else {
                              $categoryHtml = '<span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800 mb-1">Member Baru</span>';
                          }
                      } elseif ($transaction->visitItems->isNotEmpty()) {
                          $item = $transaction->visitItems->first();
                          $itemName = $item->memberType ? $item->memberType->name : 'Tiket Harian';
                          $categoryHtml = '<span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800 mb-1">Tiket Kunjungan</span>';
                      } elseif ($transaction->eventRegistrations->isNotEmpty()) {
                          $item = $transaction->eventRegistrations->first();
                          $itemName = $item->event ? $item->event->title : 'Event';
                          $categoryHtml = '<span class="inline-flex rounded-full bg-purple-100 px-2.5 py-1 text-xs font-semibold text-purple-800 mb-1">Pendaftaran Event</span>';
                      } else {
                          $categoryHtml = '<span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-800 mb-1">Lainnya</span>';
                      }
                  @endphp
                  
                  {!! $categoryHtml !!}
                  <div class="text-xs text-slate-500 font-medium truncate max-w-[150px]" title="{{ $itemName }}">
                      {{ $itemName }}
                  </div>
                </td>
                <td class="px-6 py-4 text-right">
                  @if ($transaction->duration_bonus > 0)
                     <span class="font-semibold text-teal-600">+{{ $transaction->duration_bonus }} Hari</span>
                     <div class="text-xs text-slate-500 mt-1">Ekstra Durasi</div>
                  @elseif ($transaction->discount_amount > 0)
                     <span class="font-semibold text-rose-600">-Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
                     <div class="text-xs text-slate-500 mt-1">Potongan Harga</div>
                  @else
                     <span class="text-slate-400">-</span>
                  @endif
                </td>
                <td class="px-6 py-4 text-center">
                  @if ($transaction->status === 'paid')
                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">Berhasil</span>
                  @elseif ($transaction->status === 'expired')
                    <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-800">Kedaluwarsa</span>
                  @else
                    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">Pending</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                  Belum ada transaksi yang menggunakan promo ini.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if ($transactions->hasPages())
        <div class="border-t border-slate-200 px-6 py-4">
          {{ $transactions->links() }}
        </div>
      @endif
    </div>
  </div>
@endsection
