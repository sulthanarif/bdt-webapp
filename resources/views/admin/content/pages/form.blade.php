@extends('admin.layouts.app')

@section('title', 'Edit ' . $pageTitle)
@section('page_title', 'Edit ' . $pageTitle)

@section('content')
  <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    @if (session('status'))
      <div class="mb-4 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ session('status') }}
      </div>
    @endif
    <div class="flex flex-col gap-2">
      <h2 class="text-lg font-semibold text-slate-900">{{ $pageTitle }}</h2>
      <p class="text-sm text-slate-500">Gunakan teks biasa. HTML tag tidak diizinkan.</p>
    </div>

    <form method="POST" action="{{ route('admin.content.pages.update', $page->slug) }}" class="mt-6 space-y-6">
      @csrf
      @method('PUT')

      <div>
        <label class="text-sm font-semibold text-slate-700">Judul</label>
        <input type="text" name="title" value="{{ old('title', $page->title) }}" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
        @error('title')
          <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="text-sm font-semibold text-slate-700">Isi Konten</label>
        <textarea name="body" rows="16" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm leading-relaxed focus:border-teal-500 focus:ring-2 focus:ring-teal-200">{{ old('body', $page->body) }}</textarea>
        @error('body')
          <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
        @enderror
      </div>

      <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.content.index') }}" class="rounded-xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">Kembali</a>
        <button type="submit" class="rounded-xl bg-teal-700 px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-teal-800">Simpan</button>
      </div>
    </form>
  </div>
@endsection
