@extends('admin.layouts.app')

@section('title', 'Membership Types')
@section('page_title', 'Membership Types')

@section('content')
  <div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
      <div>
        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Membership</p>
        <h2 class="text-2xl font-semibold text-slate-900">Daftar Paket Membership</h2>
      </div>
      <a href="{{ route('admin.membership.types.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-teal-700 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-teal-800">
        Tambah Paket
      </a>
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
        <input type="search" name="q" value="{{ $search }}" placeholder="Cari nama paket atau label" class="h-11 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-4 text-sm text-slate-700 focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
      </div>
      <button type="submit" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
        Filter
      </button>
    </form>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-400">
          <tr>
            <th class="px-4 py-3">Paket</th>
            <th class="px-4 py-3">Harga</th>
            <th class="px-4 py-3">Durasi</th>
            <th class="px-4 py-3">Label</th>
            <th class="px-4 py-3">Warna</th>
            <th class="px-4 py-3 text-center">Benefit</th>
            <th class="px-4 py-3 text-center">Tipe</th>
            <th class="px-4 py-3 text-center">Status</th>
            <th class="px-4 py-3 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          @forelse ($memberTypes as $type)
            <tr class="text-slate-600">
              <td class="px-4 py-3">
                <p class="font-semibold text-slate-900">{{ $type->name }}</p>
                <p class="text-xs text-slate-400">Urutan {{ $type->order }}</p>
              </td>
              <td class="px-4 py-3 font-semibold text-slate-800">
                Rp {{ number_format($type->pricing, 0, ',', '.') }}
              </td>
              <td class="px-4 py-3">
                {{ $type->duration_days }} hari
              </td>
              <td class="px-4 py-3">
                @if ($type->label)
                  <span class="inline-flex items-center rounded-full bg-teal-100 px-3 py-1 text-xs font-semibold text-teal-700">
                    {{ $type->label }}
                  </span>
                @else
                  <span class="text-xs text-slate-400">-</span>
                @endif
              </td>
              <td class="px-4 py-3">
                @if ($type->accent_color)
                  <div class="flex items-center gap-2">
                    <span class="h-5 w-5 rounded-full border border-slate-200" style="background-color: {{ $type->accent_color }}"></span>
                    <span class="text-xs text-slate-500">{{ $type->accent_color }}</span>
                  </div>
                @else
                  <span class="text-xs text-slate-400">-</span>
                @endif
              </td>
              <td class="px-4 py-3 text-center">
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                  {{ $type->benefits_count }}
                </span>
              </td>
              <td class="px-4 py-3 text-center">
                @if ($type->is_daily)
                  <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Harian</span>
                @else
                  <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">Membership</span>
                @endif
              </td>
              <td class="px-4 py-3 text-center">
                @if ($type->is_active)
                  <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Aktif</span>
                @else
                  <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-600">Nonaktif</span>
                @endif
              </td>
              <td class="px-4 py-3 text-right">
                <div class="inline-flex items-center gap-2">
                  <a href="{{ route('admin.membership.types.edit', $type) }}" class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    Edit
                  </a>
                  <form method="POST" action="{{ route('admin.membership.types.destroy', $type) }}" onsubmit="return confirm('Hapus paket ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg border border-rose-200 px-3 py-1 text-xs font-semibold text-rose-600 hover:bg-rose-50">
                      Hapus
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="px-4 py-10 text-center text-sm text-slate-500">
                Belum ada paket membership. Tambahkan paket baru untuk ditampilkan di landing.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div>
      {{ $memberTypes->links() }}
    </div>
  </div>
@endsection
