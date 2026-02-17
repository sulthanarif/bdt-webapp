      <section data-tab-panel="agenda" class="animate-fade-in hidden">
        <div class="text-center mb-12">
          <h2 class="font-serif text-3xl font-bold text-slate-900 mb-4">Agenda & Kegiatan</h2>
          <p class="text-slate-600 max-w-2xl mx-auto">
            Bergabunglah dalam diskusi, bedah buku, dan berbagai kegiatan inspiratif di Baca Di Tebet.
          </p>
        </div>

        <div class="flex flex-col gap-8 max-w-5xl mx-auto">
          @forelse ($agendaEvents as $event)
            @php
              $isFinished = ($event->ends_at && $event->ends_at->isPast()) || $event->is_finished;
              $statusLabel = 'Event selesai';
              $categoryStyle = $event->category_style ?? 'teal';
              $badgeClass = $categoryStyle === 'slate'
                ? 'bg-slate-100 text-slate-800'
                : 'bg-teal-100 text-teal-800';
              $iconClass = $categoryStyle === 'slate' ? 'text-slate-600' : 'text-teal-600';
              $ctaClass = 'bg-teal-700';
              $ctaLabel = $isFinished ? 'Pendaftaran Ditutup' : 'Daftar Sekarang';
              $ctaUrl = $event->use_internal_registration
                ? route('events.register', $event)
                : ($event->cta_url ?: '#');
              $ctaTarget = $event->use_internal_registration ? '_self' : '_blank';
              $ctaRel = $event->use_internal_registration ? '' : 'noreferrer';
              $ctaDisabled = $isFinished || (! $event->use_internal_registration && empty($event->cta_url));
              $imageSrc = $event->image_url
                ? $event->image_url
                : ($event->image_path ? asset('storage/' . $event->image_path) : asset('images/ruang_baca.jpeg'));
              $imageAlt = $event->image_alt ?: $event->title;
            @endphp

            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden transition-all duration-300 md:flex md:flex-row {{ $isFinished ? 'opacity-70 grayscale' : '' }} relative">
              @if ($isFinished)
                <div class="absolute top-6 right-6 z-20 rounded-full bg-slate-900/80 px-3 py-1 text-xs font-bold uppercase tracking-wide text-white">
                  {{ $statusLabel }}
                </div>
              @endif
              <!-- Image Side (Left on Desktop) -->
              <div class="relative w-full md:w-2/5 shrink-0 order-1 overflow-hidden">
                <!-- Mobile: Natural Height | Desktop: Absolute Fill -->
                <img src="{{ $imageSrc }}" alt="{{ $imageAlt }}" class="w-full h-auto object-cover md:absolute md:inset-0 md:h-full transition-transform duration-700 group-hover:scale-105">
                <!-- Gradient Overlay: Short fade on edges only -->
                <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-white to-transparent md:bg-gradient-to-l md:h-full md:w-24 md:top-0 md:bottom-0 md:right-0 md:left-auto pointer-events-none"></div>
              </div>

              <!-- Content Side (Right on Desktop) -->
              <div class="p-8 flex-1 flex flex-col justify-center relative z-10 order-2">
                <div class="mb-4">
                  @if ($event->category_label)
                    <span class="inline-block px-3 py-1 {{ $badgeClass }} rounded-full text-xs font-bold tracking-wider mb-2">{{ $event->category_label }}</span>
                  @endif
                  <h3 class="font-serif text-2xl md:text-3xl font-bold text-slate-900 leading-tight mb-1">{{ $event->title }}</h3>
                  @if ($event->subtitle)
                    <p class="{{ $categoryStyle === 'slate' ? 'text-slate-500' : 'text-teal-700' }} font-medium opacity-90">{{ $event->subtitle }}</p>
                  @endif
                </div>

                <div class="space-y-3 mb-6">
                  @if ($event->starts_at || $event->ends_at)
                    @php
                      $startLabel = $event->starts_at ? $event->starts_at->translatedFormat('l, d F Y H:i') : null;
                      $endLabel = $event->ends_at ? $event->ends_at->translatedFormat('H:i') : null;
                      $dateLabel = $startLabel ? ($endLabel ? $startLabel . ' - ' . $endLabel . ' WIB' : $startLabel . ' WIB') : null;
                    @endphp
                    @if ($dateLabel)
                      <div class="flex items-center gap-3 text-slate-600">
                        <i data-lucide="calendar" class="w-5 h-5 {{ $iconClass }}"></i>
                        <span class="font-semibold">{{ $dateLabel }}</span>
                      </div>
                    @endif
                  @endif
                  @if ($event->location_label)
                    <div class="flex items-start gap-3 text-slate-600">
                      <i data-lucide="map-pin" class="w-5 h-5 {{ $iconClass }} shrink-0 mt-0.5"></i>
                      <span>{{ $event->location_label }}</span>
                    </div>
                  @endif
                  <div class="flex items-center gap-3 text-slate-600">
                    <i data-lucide="ticket" class="w-5 h-5 {{ $iconClass }}"></i>
                    @if ($event->price && $event->price > 0)
                      <span class="font-semibold">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                    @else
                      <span class="font-semibold">Gratis</span>
                    @endif
                  </div>
                </div>

                @if ($event->description)
                  <p class="text-slate-600 text-sm leading-relaxed mb-6">
                    {{ $event->description }}
                  </p>
                @endif

                @if ($ctaLabel)
                  <a href="{{ $ctaUrl }}" target="{{ $ctaTarget }}" @if ($ctaRel) rel="{{ $ctaRel }}" @endif aria-disabled="{{ $ctaDisabled ? 'true' : 'false' }}" tabindex="{{ $ctaDisabled ? '-1' : '0' }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 {{ $ctaClass }} text-white rounded-lg font-bold w-full md:w-auto shadow-md {{ $ctaDisabled ? 'opacity-60 cursor-not-allowed pointer-events-none' : 'hover:opacity-90' }}">
                    {{ $ctaLabel }}
                    @if ($ctaDisabled)
                      <i data-lucide="lock" class="w-4 h-4"></i>
                    @endif
                  </a>
                @endif
              </div>
            </div>
          @empty
            <div class="rounded-xl border border-slate-200 bg-white px-6 py-4 text-sm text-slate-500">
              Belum ada agenda tersedia.
            </div>
          @endforelse
        </div>
      </section>
