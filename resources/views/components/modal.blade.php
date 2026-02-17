@props([
    'id' => 'modal',
    'title' => '',
    'size' => 'default', // default, lg, xl
    'showHeader' => true,
    'closeable' => true,
])

@php
  $sizeClasses = [
    'default' => 'md:max-w-3xl',
    'lg' => 'md:max-w-4xl',
    'xl' => 'md:max-w-5xl',
  ];
  $modalSize = $sizeClasses[$size] ?? $sizeClasses['default'];
@endphp

<div {{ $attributes->merge(['class' => 'fixed inset-0 z-50 hidden']) }} data-modal="{{ $id }}">
  {{-- Backdrop --}}
  <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @if($closeable) data-modal-close="{{ $id }}" @endif></div>
  
  {{-- Modal Container --}}
  <div class="relative mx-auto h-full w-full md:my-8 md:h-auto md:w-[92%] {{ $modalSize }}">
    <div class="flex h-full flex-col bg-white md:max-h-[85vh] md:rounded-3xl md:border md:border-slate-200 md:shadow-2xl" data-modal-dialog="{{ $id }}">
      
      @if($showHeader)
        {{-- Header --}}
        <div class="sticky top-0 z-10 flex items-start justify-between gap-4 border-b border-slate-100 bg-white/95 px-6 py-5 backdrop-blur md:px-8 md:py-6">
          <div class="pr-6">
            @if($title)
              <h3 class="font-serif text-2xl font-bold text-slate-900" data-modal-title="{{ $id }}">{{ $title }}</h3>
            @else
              {{ $header ?? '' }}
            @endif
          </div>
          
          @if($closeable)
            <button type="button" data-modal-close="{{ $id }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:bg-slate-50 transition-colors" aria-label="Close">
              <span class="sr-only">Tutup</span>
              <span class="text-2xl leading-none">×</span>
            </button>
          @endif
        </div>
      @endif

      {{-- Body --}}
      <div class="flex-1 overflow-y-auto px-6 pb-8 pt-5 md:px-8">
        {{ $slot }}
      </div>

      {{-- Footer --}}
      @isset($footer)
        <div class="sticky bottom-0 border-t border-slate-100 bg-white/95 px-6 py-4 backdrop-blur md:px-8">
          {{ $footer }}
        </div>
      @endisset
    </div>
  </div>
</div>
