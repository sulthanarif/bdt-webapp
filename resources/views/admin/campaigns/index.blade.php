@extends('admin.layouts.app')

@section('title', 'Kelola Campaign & Voucher')
@section('page_title', 'Daftar Campaign')

@section('content')
  <div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Pemasaran</p>
        <h2 class="text-2xl font-semibold text-slate-900">Campaign & Voucher</h2>
      </div>
      <div class="flex items-center gap-3">
        <form method="GET" action="{{ route('admin.campaigns.index') }}" class="relative w-full sm:w-64">
          <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama atau kode..." class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
        </form>
        <a href="{{ route('admin.campaigns.create') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-teal-800">
          <i data-lucide="plus" class="h-4 w-4"></i>
          Tambah
        </a>
      </div>
    </div>

    @if (session('status'))
      <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ session('status') }}
      </div>
    @endif

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
          <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
            <tr>
              <th scope="col" class="px-6 py-4 font-semibold">Campaign</th>
              <th scope="col" class="px-6 py-4 font-semibold">Tipe & Nilai Diskon</th>
              <th scope="col" class="px-6 py-4 font-semibold">Periode & Kuota</th>
              <th scope="col" class="px-6 py-4 font-semibold text-center">Status</th>
              <th scope="col" class="px-6 py-4 font-semibold text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200 bg-white">
            @forelse ($campaigns as $campaign)
              <tr class="hover:bg-slate-50">
                <td class="px-6 py-4">
                  <div class="font-semibold text-slate-900">{{ $campaign->name }}</div>
                  @if($campaign->code)
                    <div class="mt-1 inline-flex items-center gap-1 rounded bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600 font-mono">
                      {{ $campaign->code }}
                    </div>
                  @else
                    <div class="mt-1 text-xs text-slate-500">Promo Otomatis</div>
                  @endif
                </td>
                <td class="px-6 py-4">
                  <div class="font-medium text-slate-800">
                    @if ($campaign->discount_type === 'duration')
                      <span class="text-teal-700">Ekstra Durasi</span>
                      @if($campaign->discount_value)
                        <span class="text-sm font-semibold text-teal-600">+{{ $campaign->discount_value }} hari</span>
                      @else
                        <span class="text-xs text-slate-400 italic">(per plan)</span>
                      @endif
                    @elseif ($campaign->discount_value !== null)
                      @if ($campaign->discount_type === 'percentage')
                        {{ $campaign->discount_value }}%
                      @else
                        Rp {{ number_format($campaign->discount_value, 0, ',', '.') }}
                      @endif
                    @else
                      <span class="text-slate-400 italic">Per Plan</span>
                    @endif
                  </div>
                  <div class="text-xs text-slate-500 capitalize">{{ $campaign->discount_type }}</div>
                </td>
                <td class="px-6 py-4 text-xs">
                  @if($campaign->valid_from || $campaign->valid_until)
                    <div class="text-slate-700">
                      {{ $campaign->valid_from ? $campaign->valid_from->format('d/m/Y H:i') : 'Tanpa batas' }} - 
                      {{ $campaign->valid_until ? $campaign->valid_until->format('d/m/Y H:i') : 'Tanpa batas' }}
                    </div>
                  @else
                    <div class="text-slate-500">Kapan saja</div>
                  @endif
                  
                  <div class="mt-1 text-slate-600">
                    Terpakai: <span class="font-semibold">{{ $campaign->current_uses }}</span>
                    @if($campaign->max_uses)
                       / {{ $campaign->max_uses }}
                    @endif
                  </div>
                </td>
                <td class="px-6 py-4 text-center">
                  @if ($campaign->is_active && $campaign->isValid())
                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">
                      Aktif
                    </span>
                  @else
                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                      Nonaktif
                    </span>
                  @endif
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('admin.campaigns.show', $campaign) }}" class="rounded p-2 text-slate-400 transition-colors hover:bg-slate-100 hover:text-teal-600" title="Lihat Detail">
                      <i data-lucide="eye" class="h-4 w-4"></i>
                    </a>
                    <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="rounded p-2 text-slate-400 transition-colors hover:bg-slate-100 hover:text-teal-600" title="Edit">
                      <i data-lucide="edit-3" class="h-4 w-4"></i>
                    </a>
                    <form action="{{ route('admin.campaigns.destroy', $campaign) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus campaign ini?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="rounded p-2 text-slate-400 transition-colors hover:bg-slate-100 hover:text-rose-600" title="Hapus">
                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                  Belum ada data campaign atau voucher.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if ($campaigns->hasPages())
        <div class="border-t border-slate-200 px-6 py-4">
          {{ $campaigns->links() }}
        </div>
      @endif
    </div>
  </div>
@endsection
