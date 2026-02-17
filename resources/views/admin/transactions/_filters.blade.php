<form method="GET" class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
  <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
    <div class="relative w-full sm:max-w-md">
      <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
          <circle cx="11" cy="11" r="7"></circle>
          <path d="M20 20l-3.5-3.5"></path>
        </svg>
      </span>
      <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari nama, kontak, atau invoice" class="h-11 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-4 text-sm text-slate-700 focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
    </div>
    @isset($filters['status'])
      <select name="status" class="h-11 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-700 focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
        <option value="">Semua Status</option>
        <option value="paid" {{ ($filters['status'] ?? '') === 'paid' ? 'selected' : '' }}>Paid</option>
        <option value="pending" {{ ($filters['status'] ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
      </select>
    @endisset
    @isset($filters['channel'])
      <select name="channel" class="h-11 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-700 focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
        <option value="">Semua Channel</option>
        <option value="online" {{ ($filters['channel'] ?? '') === 'online' ? 'selected' : '' }}>Online</option>
        <option value="onsite" {{ ($filters['channel'] ?? '') === 'onsite' ? 'selected' : '' }}>Onsite</option>
      </select>
    @endisset
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
