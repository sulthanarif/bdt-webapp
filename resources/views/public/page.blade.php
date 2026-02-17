@extends('layouts.public')

@section('title', $page->title . ' - Baca Di Tebet')

@section('content')
  <section class="bg-[#F9F7F2] py-16">
    <div class="max-w-4xl mx-auto px-6">
      <a href="{{ route('landing') }}" class="text-sm font-semibold text-teal-700 hover:underline">Kembali ke beranda</a>
      <h1 class="mt-4 font-serif text-3xl md:text-4xl font-bold text-slate-900">{{ $page->title }}</h1>
      <div class="mt-6 space-y-4 text-slate-700 leading-relaxed">
        {!! nl2br(e($page->body)) !!}
      </div>
    </div>
  </section>
@endsection
