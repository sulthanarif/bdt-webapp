@php
  $navItems = $content['nav']['items'] ?? [];
  $iconMap = [
    'about' => 'book-open',
    'facilities' => 'wifi',
    'pricing' => 'credit-card',
    'agenda' => 'calendar',
    'blog' => 'message-circle',
    'contact' => 'map-pin',
  ];
@endphp

<div class="flex flex-wrap justify-center gap-2 mb-16">
  @foreach ($navItems as $index => $item)
    @php
      $target = $item['target'] ?? '';
      $label = $item['label'] ?? '';
      $icon = $iconMap[$target] ?? 'circle';
    @endphp
    <button type="button" data-tab-button data-tab-target="{{ $target }}" class="flex items-center gap-2 px-4 py-2 md:px-6 md:py-3 rounded-full text-xs md:text-sm font-bold transition-all duration-300 {{ $index === 0 ? 'bg-teal-700 text-white shadow-lg scale-105' : 'bg-white text-slate-500 hover:bg-slate-100 border border-slate-200' }}">
      <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
      <span>{{ $label }}</span>
    </button>
  @endforeach
</div>

