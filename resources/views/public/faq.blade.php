@extends('layouts.public')

@section('title', ($faqMeta['title'] ?? 'FAQ') . ' - Baca Di Tebet')

@section('content')
  <section class="bg-[#F9F7F2] py-16">
    <div class="max-w-4xl mx-auto px-6">
      <a href="{{ route('landing') }}" class="text-sm font-semibold text-teal-700 hover:underline">Kembali ke beranda</a>
      <h1 class="mt-4 font-serif text-3xl md:text-4xl font-bold text-slate-900">{{ $faqMeta['title'] ?? 'FAQ' }}</h1>
      <p class="mt-3 text-slate-600">{{ $faqMeta['description'] ?? 'Pertanyaan yang sering ditanyakan seputar Baca Di Tebet.' }}</p>

      <div class="mt-8 space-y-4">
        @forelse ($faqs as $faq)
          <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">{{ $faq->question }}</h3>
            <p class="mt-3 text-sm text-slate-600 leading-relaxed">{!! nl2br(e($faq->answer)) !!}</p>
          </div>
        @empty
          <div class="rounded-2xl border border-slate-200 bg-white p-6 text-sm text-slate-500">Belum ada FAQ tersedia.</div>
        @endforelse
      </div>
    </div>
  </section>
@endsection
