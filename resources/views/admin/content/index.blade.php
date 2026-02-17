@extends('admin.layouts.app')

@section('title', 'CMS Konten')
@section('page_title', 'CMS Konten')

@section('content')
  @if (session('status'))
    <div class="mb-6 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
      {{ session('status') }}
    </div>
  @endif

  <div class="grid gap-6 lg:grid-cols-4">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <h3 class="text-lg font-semibold text-slate-900">Landing Page</h3>
      <p class="mt-2 text-sm text-slate-600">Kelola semua teks landing page (tanpa HTML).</p>
      <a href="{{ route('admin.content.landing.edit') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-teal-700 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-teal-800">
        Edit Landing
      </a>
    </div>
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <h3 class="text-lg font-semibold text-slate-900">Halaman Privasi</h3>
      <p class="mt-2 text-sm text-slate-600">Edit isi halaman privasi (teks saja, tanpa HTML).</p>
      <a href="{{ route('admin.content.pages.edit', 'privacy') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-teal-700 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-teal-800">
        Edit Privasi
      </a>
    </div>
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <h3 class="text-lg font-semibold text-slate-900">Syarat & Ketentuan</h3>
      <p class="mt-2 text-sm text-slate-600">Edit isi halaman syarat dan ketentuan (teks saja).</p>
      <a href="{{ route('admin.content.pages.edit', 'terms') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-teal-700 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-teal-800">
        Edit Ketentuan
      </a>
    </div>
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <h3 class="text-lg font-semibold text-slate-900">FAQ</h3>
      <p class="mt-2 text-sm text-slate-600">Kelola daftar pertanyaan dan jawaban.</p>
      <a href="{{ route('admin.content.faqs.index') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-teal-700 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-teal-800">
        Kelola FAQ
      </a>
    </div>
  </div>
@endsection
