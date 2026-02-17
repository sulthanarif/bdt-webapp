@extends('admin.layouts.app')

@section('title', 'Members')
@section('page_title', 'Members')

@section('content')
  <div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
      <div>
        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Membership</p>
        <h2 class="text-2xl font-semibold text-slate-900">Data Members</h2>
      </div>
      <a href="{{ route('admin.membership.members.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-teal-700 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-teal-800">
        Tambah Member
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
        <input type="search" name="q" value="{{ $search }}" placeholder="Cari nama, email, nomor kontak" class="h-11 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-4 text-sm text-slate-700 focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
      </div>
      <button type="submit" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
        Filter
      </button>
    </form>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-400">
          <tr>
            <th class="px-4 py-3">Member</th>
            <th class="px-4 py-3">Kontak</th>
            <th class="px-4 py-3">Paket</th>
            <th class="px-4 py-3">Invoice</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Expired</th>
            <th class="px-4 py-3 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          @forelse ($members as $member)
            <tr class="text-slate-600">
              <td class="px-4 py-3">
                <p class="font-semibold text-slate-900">{{ $member->name }}</p>
                <p class="text-xs text-slate-400">{{ $member->email ?? '-' }}</p>
              </td>
              <td class="px-4 py-3">
                {{ $member->phone ?? '-' }}
              </td>
              <td class="px-4 py-3">
                {{ $member->memberType->name ?? 'Belum dipilih' }}
              </td>
              <td class="px-4 py-3">
                {{ $member->latestTransaction?->invoice_id ?? '-' }}
              </td>
              <td class="px-4 py-3">
                @if ($member->status === 'banned')
                  <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">Banned</span>
                @else
                  <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Aktif</span>
                @endif
              </td>
              <td class="px-4 py-3">
                {{ $member->expired_at?->format('d M Y') ?? '-' }}
              </td>
              <td class="px-4 py-3 text-right">
                <a href="{{ route('admin.membership.members.edit', $member) }}" class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                  Edit
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-4 py-10 text-center text-sm text-slate-500">
                Belum ada data member. Tambahkan member atau tunggu pendaftaran masuk.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div>
      {{ $members->links() }}
    </div>
  </div>
@endsection
