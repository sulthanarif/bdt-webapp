@extends('admin.layouts.app')

@section('title', 'Transaksi Membership')
@section('page_title', 'Transaksi Membership')

@section('content')
  <div class="space-y-6">
    <div>
      <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Transaksi</p>
      <h2 class="text-2xl font-semibold text-slate-900">Membership</h2>
    </div>

    @include('admin.transactions._filters', ['filters' => $filters])

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-400">
          <tr>
            <th class="px-4 py-3">Invoice</th>
            <th class="px-4 py-3">Member</th>
            <th class="px-4 py-3">Paket</th>
            <th class="px-4 py-3">Qty</th>
            <th class="px-4 py-3">Channel</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Tanggal</th>
            <th class="px-4 py-3 text-right">Subtotal</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          @forelse ($items as $item)
            <tr class="text-slate-600">
              <td class="px-4 py-3 font-semibold text-slate-800">{{ $item->transaction?->invoice_id ?? '-' }}</td>
              <td class="px-4 py-3">
                <div class="font-semibold text-slate-900">{{ $item->member?->name ?? $item->transaction?->customer_name ?? '-' }}</div>
                <div class="text-xs text-slate-400">{{ $item->member?->email ?? $item->transaction?->customer_email ?? '-' }}</div>
              </td>
              <td class="px-4 py-3">{{ $item->memberType?->name ?? '-' }}</td>
              <td class="px-4 py-3">{{ $item->qty }}</td>
              <td class="px-4 py-3">
                <span class="rounded-full {{ ($item->transaction?->channel ?? 'online') === 'onsite' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }} px-3 py-1 text-xs font-semibold">
                  {{ ($item->transaction?->channel ?? 'online') === 'onsite' ? 'Onsite' : 'Online' }}
                </span>
              </td>
              <td class="px-4 py-3">
                <span class="rounded-full {{ ($item->transaction?->status ?? 'pending') === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }} px-3 py-1 text-xs font-semibold">
                  {{ ($item->transaction?->status ?? 'pending') === 'paid' ? 'Paid' : 'Pending' }}
                </span>
              </td>
              <td class="px-4 py-3">{{ $item->transaction?->created_at?->format('d M Y') ?? '-' }}</td>
              <td class="px-4 py-3 text-right font-semibold text-slate-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-4 py-8 text-center text-sm text-slate-500">Belum ada transaksi membership.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($items->hasPages())
      <div>{{ $items->links() }}</div>
    @endif
  </div>
@endsection
