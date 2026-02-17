@extends('admin.layouts.app')

@section('title', 'FAQ')
@section('page_title', 'FAQ')

@section('content')
  <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <form method="GET" action="{{ route('admin.content.faqs.index') }}" class="flex w-full max-w-md items-center gap-2">
      <input type="text" name="q" value="{{ $search }}" placeholder="Cari FAQ" class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-700 focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
      <button type="submit" class="h-11 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-600 hover:bg-slate-50">Cari</button>
    </form>
    <a href="{{ route('admin.content.faqs.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-teal-700 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-teal-800">
      Tambah FAQ
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
          <th class="px-4 py-3">Pertanyaan</th>
          <th class="px-4 py-3">Jawaban</th>
          <th class="px-4 py-3">Status</th>
          <th class="px-4 py-3 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        @forelse ($faqs as $faq)
          <tr>
            <td class="px-4 py-3 text-slate-600">{{ $faq->order }}</td>
            <td class="px-4 py-3 font-semibold text-slate-800">{{ $faq->question }}</td>
            <td class="px-4 py-3 text-slate-600">{{ \Illuminate\Support\Str::limit($faq->answer, 90) }}</td>
            <td class="px-4 py-3">
              <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $faq->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                {{ $faq->is_active ? 'Aktif' : 'Nonaktif' }}
              </span>
            </td>
            <td class="px-4 py-3 text-right">
              <div class="inline-flex items-center gap-2">
                <a href="{{ route('admin.content.faqs.edit', $faq) }}" class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">Edit</a>
                <form method="POST" action="{{ route('admin.content.faqs.destroy', $faq) }}" onsubmit="return confirm('Hapus FAQ ini?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="rounded-lg border border-rose-200 px-3 py-1 text-xs font-semibold text-rose-600 hover:bg-rose-50">Hapus</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-4 py-6 text-center text-slate-500">Belum ada FAQ.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if ($faqs->hasPages())
    <div class="mt-6">
      {{ $faqs->links() }}
    </div>
  @endif
@endsection
