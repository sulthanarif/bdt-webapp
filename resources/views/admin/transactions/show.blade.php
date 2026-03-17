@extends('admin.layouts.app')

@section('title', 'Detail Transaksi')
@section('page_title', 'Detail Transaksi')

@section('content')
  <div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
      <div>
        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Transaksi</p>
        <h2 class="text-2xl font-semibold text-slate-900">Invoice {{ $transaction->invoice_id ?? '-' }}</h2>
        <p class="text-sm text-slate-500">{{ $transaction->created_at?->format('d M Y H:i') }}</p>
      </div>
      <a href="{{ route('admin.transactions.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
        Kembali
      </a>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
      <div class="lg:col-span-2 space-y-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
          <h3 class="text-sm font-semibold text-slate-700">Customer</h3>
          <div class="mt-3 grid gap-4 md:grid-cols-2 text-sm text-slate-600">
            <div>
              <p class="text-xs uppercase text-slate-400">Nama</p>
              <p class="font-semibold text-slate-900">{{ $transaction->customer_name }}</p>
            </div>
            <div>
              <p class="text-xs uppercase text-slate-400">Email</p>
              <p>{{ $transaction->customer_email ?? '-' }}</p>
            </div>
            <div>
              <p class="text-xs uppercase text-slate-400">Kontak</p>
              <p>{{ $transaction->customer_phone ?? '-' }}</p>
            </div>
            <div>
              <p class="text-xs uppercase text-slate-400">Member</p>
              <p>{{ $transaction->member?->name ?? '-' }}</p>
            </div>
          </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6">
          <h3 class="text-sm font-semibold text-slate-700">Item Membership</h3>
          <div class="mt-4 overflow-hidden rounded-xl border border-slate-200">
            <table class="w-full text-sm">
              <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-400">
                <tr>
                  <th class="px-3 py-2">Paket</th>
                  <th class="px-3 py-2">Qty</th>
                  <th class="px-3 py-2 text-right">Subtotal</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                @forelse ($transaction->membershipItems as $item)
                  <tr>
                    <td class="px-3 py-2">{{ $item->memberType?->name ?? '-' }}</td>
                    <td class="px-3 py-2">{{ $item->qty }}</td>
                    <td class="px-3 py-2 text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="px-3 py-3 text-center text-slate-500">Tidak ada item membership.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6">
          <h3 class="text-sm font-semibold text-slate-700">Item Kunjungan</h3>
          <div class="mt-4 overflow-hidden rounded-xl border border-slate-200">
            <table class="w-full text-sm">
              <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-400">
                <tr>
                  <th class="px-3 py-2">Paket</th>
                  <th class="px-3 py-2">Tanggal</th>
                  <th class="px-3 py-2">Qty</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                @forelse ($transaction->visitItems as $item)
                  <tr>
                    <td class="px-3 py-2">{{ $item->memberType?->name ?? '-' }}</td>
                    <td class="px-3 py-2">{{ $item->visit_date?->format('d M Y') ?? '-' }}</td>
                    <td class="px-3 py-2">{{ $item->qty }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="px-3 py-3 text-center text-slate-500">Tidak ada item kunjungan.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6">
          <h3 class="text-sm font-semibold text-slate-700">Item Event</h3>
          <div class="mt-4 overflow-hidden rounded-xl border border-slate-200">
            <table class="w-full text-sm">
              <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-400">
                <tr>
                  <th class="px-3 py-2">Event</th>
                  <th class="px-3 py-2 text-right">Harga</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                @forelse ($transaction->eventRegistrations as $item)
                  <tr>
                    <td class="px-3 py-2">{{ $item->event?->title ?? '-' }}</td>
                    <td class="px-3 py-2 text-right">Rp {{ number_format($item->event?->price ?? 0, 0, ',', '.') }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="2" class="px-3 py-3 text-center text-slate-500">Tidak ada item event.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="space-y-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
          <h3 class="text-sm font-semibold text-slate-700">Ringkasan</h3>
          <div class="mt-3 space-y-2 text-sm text-slate-600">
            <div class="flex items-center justify-between">
              <span>Status</span>
              <span class="font-semibold">{{ $transaction->status === 'paid' ? 'Paid' : 'Pending' }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span>Channel</span>
              <span class="font-semibold">{{ $transaction->channel === 'onsite' ? 'Onsite' : 'Online' }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span>Metode</span>
              <span class="font-semibold">{{ $transaction->payment_method ?? '-' }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span>Total</span>
              <span class="font-semibold text-slate-900">Rp {{ number_format($transaction->amount_total, 0, ',', '.') }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span>Paid At</span>
              <span class="font-semibold">{{ $transaction->paid_at?->format('d M Y H:i') ?? '-' }}</span>
            </div>
            @if(isset($transaction->gateway_payload['invoice_url']))
            <div class="flex flex-col gap-1 mt-2 pt-2 border-t border-slate-100">
              <span>Link Pembayaran (Xendit)</span>
              <a href="{{ $transaction->gateway_payload['invoice_url'] }}" target="_blank" class="font-semibold text-blue-600 hover:underline break-all">{{ $transaction->gateway_payload['invoice_url'] }}</a>
            </div>
            @endif
          </div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
          <h3 class="text-sm font-semibold text-slate-700">Catatan</h3>
          <div class="mt-3 text-sm text-slate-600">
            <p>Invoice: {{ $transaction->invoice_id ?? '-' }}</p>
            <p>Dibuat oleh: {{ $transaction->createdBy?->name ?? '-' }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
