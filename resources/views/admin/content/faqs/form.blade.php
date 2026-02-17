@extends('admin.layouts.app')

@section('title', $mode === 'edit' ? 'Edit FAQ' : 'Tambah FAQ')
@section('page_title', $mode === 'edit' ? 'Edit FAQ' : 'Tambah FAQ')

@section('content')
  <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    <p class="text-sm text-slate-500">Gunakan teks biasa. HTML tag tidak diizinkan. Untuk bullet list, awali baris dengan tanda "- ".</p>

    <form method="POST" action="{{ $mode === 'edit' ? route('admin.content.faqs.update', $faq) : route('admin.content.faqs.store') }}" class="mt-6 space-y-6">
      @csrf
      @if ($mode === 'edit')
        @method('PUT')
      @endif

      <div>
        <label class="text-sm font-semibold text-slate-700">Pertanyaan</label>
        <input type="text" name="question" value="{{ old('question', $faq->question) }}" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
        @error('question')
          <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="text-sm font-semibold text-slate-700">Jawaban</label>
        <textarea name="answer" rows="8" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm leading-relaxed focus:border-teal-500 focus:ring-2 focus:ring-teal-200">{{ old('answer', $faq->answer) }}</textarea>
        @error('answer')
          <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
        @enderror
      </div>

      <div class="grid gap-4 md:grid-cols-2">
        <div>
          <label class="text-sm font-semibold text-slate-700">Urutan</label>
          <input type="number" name="order" value="{{ old('order', $faq->order) }}" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('order')
            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>
        <div class="flex items-center gap-3">
          <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $faq->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-teal-600">
          <label for="is_active" class="text-sm font-semibold text-slate-700">Aktif</label>
        </div>
      </div>

      <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.content.faqs.index') }}" class="rounded-xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">Kembali</a>
        <button type="submit" class="rounded-xl bg-teal-700 px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-teal-800">Simpan</button>
      </div>
    </form>
  </div>
@endsection
