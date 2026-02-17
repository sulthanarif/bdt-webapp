@extends('admin.layouts.app')

@section('title', 'Agenda & Event')
@section('page_title', 'Agenda & Event')

@section('content')
  <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <form method="GET" action="{{ route('admin.events.index') }}" class="flex w-full max-w-md items-center gap-2">
      <input type="text" name="q" value="{{ $search }}" placeholder="Cari event" class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-700 focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
      <button type="submit" class="h-11 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-600 hover:bg-slate-50">Cari</button>
    </form>
    <a href="{{ route('admin.events.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-teal-700 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-teal-800">
      Tambah Event
    </a>
  </div>

  @if (session('status'))
    <div class="mt-6 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
      {{ session('status') }}
    </div>
  @endif

  <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white">
    <table class="min-w-full text-sm">
      <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
        <tr>
          <th class="px-4 py-3">Urutan</th>
          <th class="px-4 py-3">Judul</th>
          <th class="px-4 py-3">Kategori</th>
          <th class="px-4 py-3">Tanggal</th>
          <th class="px-4 py-3">Peserta</th>
          <th class="px-4 py-3">Status</th>
          <th class="px-4 py-3 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        @forelse ($events as $event)
          <tr>
            <td class="px-4 py-3 text-slate-600">{{ $event->order }}</td>
            <td class="px-4 py-3">
              <div class="font-semibold text-slate-800">{{ $event->title }}</div>
              @if ($event->subtitle)
                <div class="text-xs text-slate-500">{{ $event->subtitle }}</div>
              @endif
            </td>
            <td class="px-4 py-3 text-slate-600">{{ $event->category_label ?? '-' }}</td>
            <td class="px-4 py-3 text-slate-600">
              @if ($event->starts_at || $event->ends_at)
                @php
                  $startLabel = $event->starts_at ? $event->starts_at->format('d M Y H:i') : null;
                  $endLabel = $event->ends_at ? $event->ends_at->format('H:i') : null;
                @endphp
                {{ $startLabel ? ($endLabel ? $startLabel . ' - ' . $endLabel : $startLabel) : '-' }}
              @else
                -
              @endif
            </td>
            <td class="px-4 py-3 text-slate-600">
              <a href="{{ route('admin.events.registrations', $event) }}" class="text-teal-700 font-semibold hover:underline">
                {{ $event->registrations_count ?? 0 }} peserta
              </a>
            </td>
            <td class="px-4 py-3">
              <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $event->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                {{ $event->is_active ? 'Aktif' : 'Nonaktif' }}
              </span>
              @if ($event->is_finished)
                <span class="ml-2 inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-slate-200 text-slate-600">Selesai</span>
              @endif
            </td>
            <td class="px-4 py-3 text-right">
              <div class="inline-flex items-center gap-2">
                <a href="{{ route('admin.events.registrations', $event) }}" class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">Peserta</a>
                <a href="{{ route('admin.events.edit', $event) }}" class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">Edit</a>
                <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Hapus event ini?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="rounded-lg border border-rose-200 px-3 py-1 text-xs font-semibold text-rose-600 hover:bg-rose-50">Hapus</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="px-4 py-6 text-center text-slate-500">Belum ada event.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if ($events->hasPages())
    <div class="mt-6">
      {{ $events->links() }}
    </div>
  @endif
@endsection
