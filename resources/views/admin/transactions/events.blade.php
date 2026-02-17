@extends('admin.layouts.app')

@section('title', 'Registrasi Event')
@section('page_title', 'Registrasi Event')

@section('content')
  <div class="space-y-6">
    <div>
      <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Transaksi</p>
      <h2 class="text-2xl font-semibold text-slate-900">Registrasi Event</h2>
    </div>

    <form method="GET" class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
      <div class="relative w-full sm:max-w-md">
        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
            <circle cx="11" cy="11" r="7"></circle>
            <path d="M20 20l-3.5-3.5"></path>
          </svg>
        </span>
        <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari peserta atau judul event" class="h-11 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-4 text-sm text-slate-700 focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
      </div>
      <div class="flex flex-wrap items-center gap-3">
        <div class="flex items-center gap-2">
          <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="h-11 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-700 focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          <span class="text-xs text-slate-400">s/d</span>
          <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="h-11 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-700 focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
        </div>
        <button type="submit" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
          Filter
        </button>
      </div>
    </form>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-400">
          <tr>
            <th class="px-4 py-3">Event</th>
            <th class="px-4 py-3">Peserta</th>
            <th class="px-4 py-3">Kontak</th>
            <th class="px-4 py-3">Member</th>
            <th class="px-4 py-3">Harga</th>
            <th class="px-4 py-3">Tanggal</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          @forelse ($registrations as $registration)
            <tr class="text-slate-600">
              <td class="px-4 py-3">
                <div class="font-semibold text-slate-900">{{ $registration->event?->title ?? '-' }}</div>
                <div class="text-xs text-slate-400">{{ $registration->event?->subtitle ?? '-' }}</div>
              </td>
              <td class="px-4 py-3">
                <div class="font-semibold text-slate-900">{{ $registration->name }}</div>
                <div class="text-xs text-slate-400">{{ $registration->member_identifier ?? '-' }}</div>
              </td>
              <td class="px-4 py-3">
                <div>{{ $registration->email ?? '-' }}</div>
                <div>{{ $registration->phone ?? '-' }}</div>
              </td>
              <td class="px-4 py-3">
                <span class="rounded-full {{ $registration->is_member ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }} px-3 py-1 text-xs font-semibold">
                  {{ $registration->is_member ? 'Member' : 'Umum' }}
                </span>
              </td>
              <td class="px-4 py-3">
                {{ $registration->event_price !== null ? 'Rp ' . number_format($registration->event_price, 0, ',', '.') : '-' }}
              </td>
              <td class="px-4 py-3">
                {{ $registration->created_at?->format('d M Y H:i') ?? '-' }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">Belum ada registrasi event.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($registrations->hasPages())
      <div>{{ $registrations->links() }}</div>
    @endif
  </div>
@endsection
