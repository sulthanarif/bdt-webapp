@extends('layouts.public')

@section('title', 'Baca Di Tebet - Perpustakaan & Ruang Kreatif')

@section('content')
  <div id="top"></div>

  @include('landing.partials.nav')
  @include('landing.partials.hero')

  <main id="main-content" class="max-w-7xl mx-auto px-6 py-12 md:py-24">
    @include('landing.partials.tabs')

    <div class="min-h-[500px]">
      @include('landing.sections.about')
      @include('landing.sections.facilities')
      @include('landing.sections.pricing')
      @include('landing.sections.agenda')
      @include('landing.sections.blog')
      @include('landing.sections.contact')
    </div>
  </main>

  @include('landing.partials.footer')
  @include('landing.partials.floating-whatsapp')
  @include('landing.modals.membership')
  @include('landing.modals.payment')
  @include('landing.partials.membership-options')
@endsection
