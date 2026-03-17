@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Ringkasan Operasional')

@section('content')
  <div class="flex flex-col gap-8">
    <section class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
      @foreach ($stats as $stat)
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
          <div class="flex items-start justify-between gap-4">
            <div>
              <p class="text-xs uppercase tracking-[0.2em] text-slate-400">{{ $stat['label'] }}</p>
              <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $stat['value'] }}</p>
              <div class="mt-3 inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-500">
                <span class="h-1.5 w-1.5 rounded-full bg-teal-500"></span>
                Perbandingan 7 hari: {{ $stat['delta'] }}
              </div>
            </div>
            @switch($stat['label'])
              @case('Total Member')
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-teal-100 text-teal-700">
                  <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                  </svg>
                </div>
                @break
              @case('Member Aktif')
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                  <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M9 12l2 2 4-4"></path>
                    <circle cx="12" cy="12" r="9"></circle>
                  </svg>
                </div>
                @break
              @case('Transaksi Hari Ini')
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-100 text-sky-700">
                  <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="4" y="4" width="16" height="16" rx="3"></rect>
                    <path d="M8 9h8M8 13h5"></path>
                  </svg>
                </div>
                @break
              @case('Pendapatan Hari Ini')
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                  <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M12 2v20"></path>
                    <path d="M17 7c0-2.8-10-2.8-10 0s10 2.8 10 5.6-10 2.8-10 5.6"></path>
                  </svg>
                </div>
                @break
              @case('Event Aktif')
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-100 text-violet-700">
                  <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M3 5h18"></path>
                    <path d="M8 3v4M16 3v4"></path>
                    <rect x="3" y="7" width="18" height="14" rx="2"></rect>
                  </svg>
                </div>
                @break
              @default
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-100 text-rose-700">
                  <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M12 3v18"></path>
                    <path d="M5 8h14"></path>
                    <path d="M7 21h10"></path>
                  </svg>
                </div>
            @endswitch
          </div>
        </div>
      @endforeach
    </section>

    <section class="grid gap-6 lg:grid-cols-3">
      <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Ringkasan Pendapatan</p>
            <h3 class="text-xl font-semibold text-slate-900">Grafik transaksi 30 hari</h3>
          </div>
          <button class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
            Export CSV
          </button>
        </div>
        <div class="mt-6 flex items-end gap-1 h-40">
          @foreach ($chartData as $data)
            @php
              $heightRatio = $maxRevenue > 0 ? ($data['total'] / $maxRevenue) : 0;
              $heightPx = max(4, round($heightRatio * 160));
            @endphp
            <div 
              class="flex-1 rounded-t bg-teal-100 hover:bg-teal-500 transition-colors" 
              style="height: {{ $heightPx }}px"
              title="{{ \Carbon\Carbon::parse($data['date'])->format('d M Y') }}: Rp {{ number_format($data['total'], 0, ',', '.') }}">
            </div>
          @endforeach
        </div>
        <div class="mt-4 flex items-center justify-between text-xs text-slate-500">
          <span>Awal bulan</span>
          <span>Akhir bulan</span>
        </div>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">System Health</p>
        <h3 class="text-xl font-semibold text-slate-900">Status Integrasi</h3>
        <div class="mt-6 space-y-4">
          @foreach ($systemStatus as $item)
            <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
              <div>
                <p class="text-sm font-semibold text-slate-700">{{ $item['label'] }}</p>
                <p class="text-xs text-slate-500">{{ $item['status'] }}</p>
              </div>
              @switch($item['tone'])
                @case('warning')
                  <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Perlu Dicek</span>
                  @break
                @case('success')
                  <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Aktif</span>
                  @break
                @default
                  <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-600">Standby</span>
              @endswitch
            </div>
          @endforeach
        </div>
      </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-3">
      <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Aktivitas Terbaru</p>
            <h3 class="text-xl font-semibold text-slate-900">Transaksi terakhir</h3>
          </div>
          <button class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
            Lihat Semua
          </button>
        </div>
        <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200">
          <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-400">
              <tr>
                <th class="px-4 py-3">Invoice</th>
                <th class="px-4 py-3">Customer</th>
                <th class="px-4 py-3">Tipe</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-right">Nominal</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
              @foreach ($recentTransactions as $transaction)
                <tr class="text-slate-600">
                  <td class="px-4 py-3 font-semibold text-slate-800">{{ $transaction['invoice'] }}</td>
                  <td class="px-4 py-3">{{ $transaction['customer'] }}</td>
                  <td class="px-4 py-3">{{ $transaction['type'] }}</td>
                  <td class="px-4 py-3">
                    @if ($transaction['status'] === 'Menunggu')
                      <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Menunggu</span>
                    @else
                      <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Selesai</span>
                    @endif
                  </td>
                  <td class="px-4 py-3 text-right font-semibold text-slate-800">{{ $transaction['amount'] }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Event Mendatang</p>
        <h3 class="text-xl font-semibold text-slate-900">Agenda Terdekat</h3>
        <div class="mt-6 space-y-4">
          @foreach ($upcomingEvents as $event)
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
              <p class="text-sm font-semibold text-slate-800">{{ $event['title'] }}</p>
              <p class="text-xs text-slate-500 mt-2">{{ $event['date'] }}</p>
              <div class="mt-3 inline-flex items-center gap-2 rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-600">
                {{ $event['status'] }}
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  </div>
@endsection
