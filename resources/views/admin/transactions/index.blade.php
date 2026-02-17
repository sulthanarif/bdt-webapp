@extends('admin.layouts.app')

@section('title', 'Transaksi')
@section('page_title', 'Transaksi')

@section('content')
  <div class="space-y-6">
    <div>
      <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Transaksi</p>
      <h2 class="text-2xl font-semibold text-slate-900">Semua Transaksi</h2>
    </div>

    @include('admin.transactions._filters', ['filters' => $filters])

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-400">
          <tr>
            <th class="px-4 py-3">Invoice</th>
            <th class="px-4 py-3">Customer</th>
            <th class="px-4 py-3">Channel</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Tanggal</th>
            <th class="px-4 py-3 text-right">Total</th>
            <th class="px-4 py-3 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          @forelse ($transactions as $transaction)
            <tr class="text-slate-600">
              <td class="px-4 py-3 font-semibold text-slate-800">
                {{ $transaction->invoice_id ?? '-' }}
              </td>
              <td class="px-4 py-3">
                <p class="font-semibold text-slate-900">{{ $transaction->customer_name }}</p>
                <p class="text-xs text-slate-400">{{ $transaction->customer_email ?? '-' }}</p>
              </td>
              <td class="px-4 py-3">
                <span class="rounded-full {{ $transaction->channel === 'onsite' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }} px-3 py-1 text-xs font-semibold">
                  {{ $transaction->channel === 'onsite' ? 'Onsite' : 'Online' }}
                </span>
              </td>
              <td class="px-4 py-3">
                <span class="rounded-full {{ $transaction->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }} px-3 py-1 text-xs font-semibold">
                  {{ $transaction->status === 'paid' ? 'Paid' : 'Pending' }}
                </span>
              </td>
              <td class="px-4 py-3">
                {{ $transaction->created_at?->format('d M Y H:i') }}
              </td>
              <td class="px-4 py-3 text-right font-semibold text-slate-800">
                Rp {{ number_format($transaction->amount_total, 0, ',', '.') }}
              </td>
              <td class="px-4 py-3 text-right">
                <a href="{{ route('admin.transactions.show', $transaction) }}" class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">Detail</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-500">Belum ada transaksi.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($transactions->hasPages())
      <div>
        {{ $transactions->links() }}
      </div>
    @endif
  </div>
@endsection
