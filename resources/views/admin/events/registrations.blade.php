@extends('admin.layouts.app')

@section('title', 'Peserta Event')
@section('page_title', 'Peserta Event')

@section('content')
  <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div>
      <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Event</p>
      <h2 class="text-2xl font-semibold text-slate-900">{{ $event->title }}</h2>
      @if ($event->subtitle)
        <p class="text-sm text-slate-500">{{ $event->subtitle }}</p>
      @endif
    </div>
    <form method="GET" action="{{ route('admin.events.registrations', $event) }}" class="flex w-full max-w-md items-center gap-2">
      <input type="text" name="q" value="{{ $search }}" placeholder="Cari peserta" class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-700 focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
      <button type="submit" class="h-11 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-600 hover:bg-slate-50">Cari</button>
    </form>
  </div>

  <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white">
    <table class="min-w-full text-sm">
      <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
        <tr>
          <th class="px-4 py-3">Nama</th>
          <th class="px-4 py-3">Kontak</th>
          <th class="px-4 py-3">Member</th>
          <th class="px-4 py-3">Jawaban</th>
          <th class="px-4 py-3">Harga</th>
          <th class="px-4 py-3">Tanggal</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        @forelse ($registrations as $registration)
          <tr>
            <td class="px-4 py-3">
              <div class="font-semibold text-slate-800">{{ $registration->name }}</div>
              @if ($registration->member_identifier)
                <div class="text-xs text-slate-500">ID/Email Member: {{ $registration->member_identifier }}</div>
              @endif
            </td>
            <td class="px-4 py-3 text-slate-600">
              <div>{{ $registration->email ?? '-' }}</div>
              <div>{{ $registration->phone ?? '-' }}</div>
            </td>
            <td class="px-4 py-3">
              <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $registration->is_member ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                {{ $registration->is_member ? 'Member' : 'Umum' }}
              </span>
            </td>
            <td class="px-4 py-3 text-slate-600">
              @if (!empty($registration->answers))
                <div class="space-y-1 text-xs">
                  @foreach ($registration->answers as $key => $value)
                    @php
                      $label = $fieldLabels[$key] ?? $key;
                      $displayValue = is_array($value) ? implode(', ', $value) : $value;
                    @endphp
                    <div><span class="font-semibold text-slate-700">{{ $label }}:</span> {{ $displayValue }}</div>
                  @endforeach
                </div>
              @else
                -
              @endif
            </td>
            <td class="px-4 py-3 text-slate-600">
              {{ $registration->event_price !== null ? 'Rp ' . number_format($registration->event_price, 0, ',', '.') : '-' }}
            </td>
            <td class="px-4 py-3 text-slate-600">
              {{ $registration->created_at?->format('d M Y H:i') }}
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="px-4 py-6 text-center text-slate-500">Belum ada peserta.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if ($registrations->hasPages())
    <div class="mt-6">
      {{ $registrations->links() }}
    </div>
  @endif

  <div class="mt-6">
    <a href="{{ route('admin.events.index') }}" class="rounded-xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">Kembali</a>
  </div>
@endsection
