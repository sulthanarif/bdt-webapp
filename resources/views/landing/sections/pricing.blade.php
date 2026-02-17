      <section data-tab-panel="pricing" class="animate-fade-in hidden">
        <div class="text-center mb-12">
          <h2 class="font-serif text-3xl font-bold text-slate-900 mb-4">Pilihan Keanggotaan</h2>
          <p class="text-slate-600 max-w-2xl mx-auto">
            Pilih paket yang sesuai dengan kebutuhan membaca Anda. Dari kunjungan harian hingga akses tahunan penuh.
          </p>
        </div>

        <div class="flex flex-wrap justify-center gap-6">
          @forelse ($memberTypes as $memberType)
            @php
              $isDaily = $memberType->is_daily || \Illuminate\Support\Str::contains(\Illuminate\Support\Str::lower($memberType->name), 'harian');
              $isFeatured = $memberType->is_featured;
              $badge = $memberType->label;
              $accent = $memberType->accent_color ?: '#14b8a6';
              $isFullColor = $memberType->is_full_color ?? false;
              $isLightAccent = $memberType->accent_is_light ?? false;
              $cardClasses = $isFeatured
                ? 'bg-slate-900 text-white shadow-xl hover:shadow-2xl'
                : 'bg-white text-slate-800 border border-slate-200 shadow-sm hover:shadow-lg';
              $subtitle = $memberType->subtitle
                ?? ($memberType->is_student ? 'Pelajar dan mahasiswa' : 'Untuk semua kalangan');
              $dividerColor = $isFeatured ? 'bg-slate-700' : 'bg-slate-100';

              $badgeStyle = 'background-color: ' . $accent . '; color: #fff;';
              $accentStyle = 'color: ' . $accent . ';';
              $fullTextColor = $isLightAccent ? '#0f172a' : '#ffffff';
              $fullMutedColor = $isLightAccent ? 'rgba(15,23,42,0.7)' : 'rgba(255,255,255,0.7)';
              $fullDividerColor = $isLightAccent ? 'rgba(15,23,42,0.16)' : 'rgba(255,255,255,0.2)';
              $fullCardStyle = $isFullColor ? 'background-color: ' . $accent . ';' : '';
              $fullTextStyle = $isFullColor ? 'color: ' . $fullTextColor . ';' : '';
              $fullMutedStyle = $isFullColor ? 'color: ' . $fullMutedColor . ';' : '';
              $fullDividerStyle = $isFullColor ? 'background-color: ' . $fullDividerColor . ';' : '';
              $buttonStyle = $isFullColor
                ? ($isLightAccent ? 'background-color: rgba(15,23,42,0.12); color: #0f172a;' : 'background-color: rgba(255,255,255,0.92); color: ' . $accent . ';')
                : 'background-color: ' . $accent . '; color: #fff;';
            @endphp
            <div class="relative w-full md:w-[300px] rounded-2xl p-6 transition-all duration-300 flex flex-col {{ $cardClasses }} {{ $isFullColor ? 'border-transparent' : '' }} hover:-translate-y-1" style="{{ $fullCardStyle }}">
              @if ($badge)
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-md" style="{{ $badgeStyle }}">
                  {{ $badge }}
                </div>
              @endif

              <div class="text-center mb-6 pt-2">
                <h3 class="text-lg font-bold mb-1 {{ $isFeatured ? 'text-white' : 'text-slate-800' }}" style="{{ $fullTextStyle }}">{{ $memberType->name }}</h3>
                <p class="text-xs {{ $isFeatured ? 'text-slate-400' : 'text-slate-500' }} mb-4" style="{{ $fullMutedStyle }}">{{ $subtitle }}</p>
                <div class="flex justify-center items-end gap-1">
                  <span class="text-sm font-medium opacity-60" style="{{ $fullMutedStyle }}">Rp</span>
                  <span class="text-4xl font-bold" style="{{ $isFullColor ? $fullTextStyle : $accentStyle }}">{{ $memberType->display_price }}</span>
                  <span class="text-lg font-bold" style="{{ $isFullColor ? $fullTextStyle : $accentStyle }}">k</span>
                  <span class="text-sm opacity-60 mb-1" style="{{ $fullMutedStyle }}">/{{ $memberType->display_period }}</span>
                </div>
              </div>

              <div class="h-px w-full mb-6 {{ $dividerColor }}" style="{{ $fullDividerStyle }}"></div>

              <ul class="space-y-3 mb-8 flex-1">
                @forelse ($memberType->benefits as $benefit)
                  @php
                    $isIncluded = $benefit->is_included;
                    $icon = $isIncluded ? 'check' : 'x-circle';
                  @endphp
                  <li class="flex items-start gap-3 text-sm {{ $isIncluded ? '' : 'opacity-40' }}">
                    <i data-lucide="{{ $icon }}" class="w-[18px] h-[18px] shrink-0" style="{{ $isFullColor ? $fullTextStyle : $accentStyle }}"></i>
                    <span class="{{ $isIncluded ? '' : 'line-through' }}">{{ $benefit->label }}</span>
                  </li>
                @empty
                  <li class="text-sm text-slate-500">Benefit belum diatur.</li>
                @endforelse
              </ul>

              <button type="button" data-membership-open data-membership-id="{{ $memberType->id }}" data-membership-name="{{ $memberType->name }}" data-membership-student="{{ $memberType->is_student ? '1' : '0' }}" data-membership-kind="{{ $isDaily ? 'ticket' : 'member' }}" class="w-full py-3 rounded-xl font-bold transition-colors text-center block" style="{{ $buttonStyle }}">
                Daftar Sekarang
              </button>
            </div>

            @if ($loop->first && $showVisitTicket)
              <div class="relative w-full md:w-[300px] rounded-2xl p-6 transition-all duration-300 flex flex-col bg-white text-slate-800 border border-slate-200 shadow-sm hover:shadow-lg hover:-translate-y-1">
                <div class="text-center mb-6 pt-2">
                  <h3 class="text-lg font-bold mb-1 text-slate-800">{{ $visitTicket['name'] }}</h3>
                  <p class="text-xs text-slate-500 mb-4">{{ $visitTicket['subtitle'] }}</p>
                  <div class="flex justify-center items-end gap-1">
                    <span class="text-sm font-medium opacity-60">Rp</span>
                    <span class="text-4xl font-bold text-teal-700">{{ $visitTicket['price'] }}</span>
                    <span class="text-lg font-bold text-teal-700">k</span>
                    <span class="text-sm opacity-60 mb-1">/{{ $visitTicket['period'] }}</span>
                  </div>
                </div>

                <div class="h-px w-full mb-6 bg-slate-100"></div>

                <ul class="space-y-3 mb-8 flex-1">
                  @foreach ($visitTicket['benefits'] as $benefit)
                    <li class="flex items-start gap-3 text-sm {{ $benefit['is_included'] ? '' : 'opacity-40' }}">
                      <i data-lucide="{{ $benefit['is_included'] ? 'check' : 'x-circle' }}" class="w-[18px] h-[18px] text-teal-600 shrink-0"></i>
                      <span class="{{ $benefit['is_included'] ? '' : 'line-through' }}">{{ $benefit['label'] }}</span>
                    </li>
                  @endforeach
                </ul>

                <a href="{{ $visitTicket['cta_link'] }}" class="w-full py-3 rounded-xl font-bold transition-colors bg-teal-50 hover:bg-teal-100 text-teal-800 text-center block">
                  {{ $visitTicket['cta_text'] }}
                </a>
              </div>
            @endif
          @empty
            @if ($showVisitTicket)
              <div class="relative w-full md:w-[300px] rounded-2xl p-6 transition-all duration-300 flex flex-col bg-white text-slate-800 border border-slate-200 shadow-sm hover:shadow-lg hover:-translate-y-1">
                <div class="text-center mb-6 pt-2">
                  <h3 class="text-lg font-bold mb-1 text-slate-800">{{ $visitTicket['name'] }}</h3>
                  <p class="text-xs text-slate-500 mb-4">{{ $visitTicket['subtitle'] }}</p>
                  <div class="flex justify-center items-end gap-1">
                    <span class="text-sm font-medium opacity-60">Rp</span>
                    <span class="text-4xl font-bold text-teal-700">{{ $visitTicket['price'] }}</span>
                    <span class="text-lg font-bold text-teal-700">k</span>
                    <span class="text-sm opacity-60 mb-1">/{{ $visitTicket['period'] }}</span>
                  </div>
                </div>

                <div class="h-px w-full mb-6 bg-slate-100"></div>

                <ul class="space-y-3 mb-8 flex-1">
                  @foreach ($visitTicket['benefits'] as $benefit)
                    <li class="flex items-start gap-3 text-sm {{ $benefit['is_included'] ? '' : 'opacity-40' }}">
                      <i data-lucide="{{ $benefit['is_included'] ? 'check' : 'x-circle' }}" class="w-[18px] h-[18px] text-teal-600 shrink-0"></i>
                      <span class="{{ $benefit['is_included'] ? '' : 'line-through' }}">{{ $benefit['label'] }}</span>
                    </li>
                  @endforeach
                </ul>

                <a href="{{ $visitTicket['cta_link'] }}" class="w-full py-3 rounded-xl font-bold transition-colors bg-teal-50 hover:bg-teal-100 text-teal-800 text-center block">
                  {{ $visitTicket['cta_text'] }}
                </a>
              </div>
            @endif
          @endforelse
        </div>

        @if (session('register_status'))
          <div class="mt-8 rounded-2xl border border-emerald-200 bg-emerald-50 px-6 py-4 text-sm text-emerald-700">
            {{ session('register_status') }}
          </div>
        @endif

        @php
          $selectedMemberTypeId = (int) old('member_type_id', $selectedMembershipId ?? 0);
          $shouldOpenModal = $selectedMemberTypeId > 0 || $errors->any();
          $selectedMemberType = $memberTypes->firstWhere('id', $selectedMemberTypeId);
          $selectedIsTicket = $selectedMemberType
            ? ($selectedMemberType->is_daily || \Illuminate\Support\Str::contains(\Illuminate\Support\Str::lower($selectedMemberType->name), 'harian'))
            : false;
          $isStudentSelected = $selectedIsTicket ? false : ($selectedMemberType?->is_student ?? false);
          $modalTitlePrefix = $selectedIsTicket ? 'Form Pembelian Tiket' : 'Form Pendaftaran Anggota';
          $modalTitle = $selectedMemberType
            ? $modalTitlePrefix . ' - ' . $selectedMemberType->name
            : $modalTitlePrefix;
        @endphp

        <div class="mt-16">
          <div class="text-center mb-8">
            <h3 class="font-serif text-2xl font-bold text-slate-900">Pertanyaan Umum</h3>
            <p class="text-slate-600">Jawaban singkat untuk pertanyaan yang sering muncul.</p>
          </div>

          <div class="space-y-4">
            <div class="border border-slate-200 rounded-xl bg-white overflow-hidden" data-accordion-item>
              <button type="button" data-accordion-header aria-expanded="false" class="w-full flex items-center justify-between gap-4 px-6 py-4 text-left hover:bg-slate-50 transition-colors">
                <h4 class="font-semibold text-slate-900 flex-1">Siapa saja yang bisa bergabung di BDT?</h4>
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-teal-50 text-teal-700 shrink-0">
                  <i data-accordion-icon data-lucide="plus" class="w-4 h-4 transition-transform duration-200"></i>
                </span>
              </button>
              <div data-accordion-content class="px-6 pb-6 text-slate-600 text-sm leading-relaxed max-h-0 overflow-hidden transition-all duration-300 text-justify">
                <ul class="space-y-2 list-disc pl-5">
                  <li><strong>Minimal usia 12 tahun</strong> untuk menjadi anggota BDT.</li>
                  <li><strong>Pelajar:</strong> siswa SMP dan SMA yang masih aktif bersekolah.</li>
                  <li><strong>Mahasiswa:</strong> mahasiswa aktif hingga tingkat S1.</li>
                  <li><strong>Umum:</strong> siapa saja selain pelajar dan mahasiswa S1.</li>
                  <li>Pembayaran dilakukan sesuai paket langganan yang dipilih.</li>
                </ul>
              </div>
            </div>

            <div class="border border-slate-200 rounded-xl bg-white overflow-hidden" data-accordion-item>
              <button type="button" data-accordion-header aria-expanded="false" class="w-full flex items-center justify-between gap-4 px-6 py-4 text-left hover:bg-slate-50 transition-colors">
                <h4 class="font-semibold text-slate-900 flex-1">Apa saja fasilitas ruang yang tersedia?</h4>
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-teal-50 text-teal-700 shrink-0">
                  <i data-accordion-icon data-lucide="plus" class="w-4 h-4 transition-transform duration-200"></i>
                </span>
              </button>
              <div data-accordion-content class="px-6 pb-6 text-slate-600 text-sm leading-relaxed max-h-0 overflow-hidden transition-all duration-300 text-justify">
                <ul class="space-y-2 list-disc pl-5">
                  <li><strong>Ruang RBBJ (Roy B. B. Janis) / Ruang Temu:</strong> ruang santai yang memperbolehkan makanan dan minuman. Harap kembalikan buku ke troli setelah selesai membaca.</li>
                  <li><strong>Ruang Pikir dan Ruang Baca:</strong> silent room khusus untuk fokus belajar. Makanan, minuman, dan komunikasi (telepon atau zoom) tidak diperbolehkan.</li>
                  <li><strong>Ruang Karya:</strong> ruang eksklusif yang dapat diakses dengan perjanjian terlebih dahulu.</li>
                </ul>
              </div>
            </div>

            <div class="border border-slate-200 rounded-xl bg-white overflow-hidden" data-accordion-item>
              <button type="button" data-accordion-header aria-expanded="false" class="w-full flex items-center justify-between gap-4 px-6 py-4 text-left hover:bg-slate-50 transition-colors">
                <h4 class="font-semibold text-slate-900 flex-1">Bagaimana cara mendaftar dan masuk ke BDT?</h4>
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-teal-50 text-teal-700 shrink-0">
                  <i data-accordion-icon data-lucide="plus" class="w-4 h-4 transition-transform duration-200"></i>
                </span>
              </button>
              <div data-accordion-content class="px-6 pb-6 text-slate-600 text-sm leading-relaxed max-h-0 overflow-hidden transition-all duration-300 text-justify">
                <p class="font-semibold mb-2">Mudah banget. Cukup 3 langkah:</p>
                <ul class="space-y-2 list-disc pl-5">
                  <li><strong>Pilih paket:</strong> tentukan paket yang sesuai kebutuhan Anda.</li>
                  <li><strong>Isi formulir:</strong> lengkapi data pendaftaran dengan benar.</li>
                  <li><strong>Bayar:</strong> lakukan pembayaran sesuai paket yang dipilih.</li>
                </ul>
                <p class="mt-4 rounded-lg bg-slate-50 px-4 py-3 text-slate-700">Catatan: tiket harian hanya berlaku untuk hari yang sama dengan pemesanan.</p>
              </div>
            </div>

            <div class="border border-slate-200 rounded-xl bg-white overflow-hidden" data-accordion-item>
              <button type="button" data-accordion-header aria-expanded="false" class="w-full flex items-center justify-between gap-4 px-6 py-4 text-left hover:bg-slate-50 transition-colors">
                <h4 class="font-semibold text-slate-900 flex-1">Metode pembayaran apa saja yang tersedia?</h4>
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-teal-50 text-teal-700 shrink-0">
                  <i data-accordion-icon data-lucide="plus" class="w-4 h-4 transition-transform duration-200"></i>
                </span>
              </button>
              <div data-accordion-content class="px-6 pb-6 text-slate-600 text-sm leading-relaxed max-h-0 overflow-hidden transition-all duration-300 text-justify">
                <p class="font-semibold mb-2">Kami menyediakan berbagai metode pembayaran yang mudah:</p>
                <ul class="space-y-2 list-disc pl-5">
                  <li><strong>Transfer bank:</strong> melalui virtual account.</li>
                  <li><strong>E-wallet:</strong> GoPay, OVO, Dana, dan lainnya.</li>
                  <li><strong>Minimarket:</strong> Alfamart, Indomaret.</li>
                  <li><strong>Cash:</strong> langsung di lokasi (untuk tiket harian).</li>
                </ul>
                <p class="mt-4 font-semibold text-teal-700">Semua proses pembayaran aman dan terpercaya.</p>
              </div>
            </div>

            <div class="border border-slate-200 rounded-xl bg-white overflow-hidden" data-accordion-item>
              <button type="button" data-accordion-header aria-expanded="false" class="w-full flex items-center justify-between gap-4 px-6 py-4 text-left hover:bg-slate-50 transition-colors">
                <h4 class="font-semibold text-slate-900 flex-1">Bagaimana cara menuju BDT dengan transportasi umum?</h4>
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-teal-50 text-teal-700 shrink-0">
                  <i data-accordion-icon data-lucide="plus" class="w-4 h-4 transition-transform duration-200"></i>
                </span>
              </button>
              <div data-accordion-content class="px-6 pb-6 text-slate-600 text-sm leading-relaxed max-h-0 overflow-hidden transition-all duration-300 text-justify">
                <p class="font-semibold mb-2">Lokasi BDT sangat mudah dijangkau. Berikut rute terbaiknya:</p>
                <ul class="space-y-2 list-disc pl-5">
                  <li><strong>KRL Commuter Line:</strong> turun di Stasiun Cawang, lanjut dengan ojek online atau jalan kaki 15 menit.</li>
                  <li><strong>TransJakarta:</strong> turun di Halte Pancoran Tugu, lanjut dengan ojek online atau jalan kaki 20 menit.</li>
                  <li><strong>Kendaraan pribadi:</strong> alamat Jl. Tebet Barat Dalam Raya No. 29, Tebet, Jakarta Selatan.</li>
                </ul>
                <p class="mt-4 rounded-lg bg-emerald-50 px-4 py-3 text-emerald-700">Tips: gunakan Google Maps dengan kata kunci "Baca Di Tebet" untuk navigasi yang akurat.</p>
              </div>
            </div>

            <div class="border border-slate-200 rounded-xl bg-white overflow-hidden" data-accordion-item>
              <button type="button" data-accordion-header aria-expanded="false" class="w-full flex items-center justify-between gap-4 px-6 py-4 text-left hover:bg-slate-50 transition-colors">
                <h4 class="font-semibold text-slate-900 flex-1">Bagaimana proses menjadi anggota BDT?</h4>
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-teal-50 text-teal-700 shrink-0">
                  <i data-accordion-icon data-lucide="plus" class="w-4 h-4 transition-transform duration-200"></i>
                </span>
              </button>
              <div data-accordion-content class="px-6 pb-6 text-slate-600 text-sm leading-relaxed max-h-0 overflow-hidden transition-all duration-300 text-justify">
                <p class="font-semibold mb-2">Bergabung sebagai anggota memberikan banyak keuntungan.</p>
                <ul class="space-y-2 list-disc pl-5">
                  <li><strong>Pilih durasi:</strong> paket bulanan, 3 bulanan, atau tahunan.</li>
                  <li><strong>Lengkapi data:</strong> isi formulir pendaftaran dengan lengkap.</li>
                  <li><strong>Nikmati benefit:</strong> akses unlimited dan pinjam buku pulang.</li>
                  <li><strong>Fleksibilitas waktu:</strong> datang kapan saja sesuai keinginan.</li>
                </ul>
                <p class="mt-4 rounded-lg bg-teal-600 px-4 py-3 text-white text-center font-semibold">Anggota bisa datang kapan saja (kecuali hari libur) selama masa berlaku.</p>
              </div>
            </div>

            <div class="border border-slate-200 rounded-xl bg-white overflow-hidden" data-accordion-item>
              <button type="button" data-accordion-header aria-expanded="false" class="w-full flex items-center justify-between gap-4 px-6 py-4 text-left hover:bg-slate-50 transition-colors">
                <h4 class="font-semibold text-slate-900 flex-1">Bisakah membawa rombongan atau grup ke BDT?</h4>
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-teal-50 text-teal-700 shrink-0">
                  <i data-accordion-icon data-lucide="plus" class="w-4 h-4 transition-transform duration-200"></i>
                </span>
              </button>
              <div data-accordion-content class="px-6 pb-6 text-slate-600 text-sm leading-relaxed max-h-0 overflow-hidden transition-all duration-300 text-justify">
                <p class="font-semibold mb-2">Tentu saja. Kami menyambut kunjungan rombongan dengan paket khusus.</p>
                <ul class="space-y-2 list-disc pl-5">
                  <li><strong>Rombongan sekolah:</strong> ideal untuk field trip edukatif.</li>
                  <li><strong>Grup kantor:</strong> cocok untuk team building dan workshop.</li>
                  <li><strong>Keluarga besar:</strong> gathering sambil membaca bersama.</li>
                </ul>
                <div class="mt-4 rounded-lg bg-amber-50 px-4 py-3 text-amber-900">
                  <p class="font-semibold">Hubungi admin sekarang:</p>
                  <a href="#" data-link="whatsappGroup" class="text-teal-700 font-bold">Chat WhatsApp untuk konsultasi dan penawaran khusus rombongan.</a>
                </div>
              </div>
            </div>

            <div class="border border-slate-200 rounded-xl bg-white overflow-hidden" data-accordion-item>
              <button type="button" data-accordion-header aria-expanded="false" class="w-full flex items-center justify-between gap-4 px-6 py-4 text-left hover:bg-slate-50 transition-colors">
                <h4 class="font-semibold text-slate-900 flex-1">Bisakah mengadakan acara atau event di BDT?</h4>
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-teal-50 text-teal-700 shrink-0">
                  <i data-accordion-icon data-lucide="plus" class="w-4 h-4 transition-transform duration-200"></i>
                </span>
              </button>
              <div data-accordion-content class="px-6 pb-6 text-slate-600 text-sm leading-relaxed max-h-0 overflow-hidden transition-all duration-300 text-justify">
                <p class="font-semibold mb-2">BDT cocok untuk berbagai jenis acara.</p>
                <ul class="space-y-2 list-disc pl-5">
                  <li><strong>Book launch dan bedah buku:</strong> suasana mendukung diskusi.</li>
                  <li><strong>Workshop dan seminar:</strong> pelatihan menulis, literasi, dan pengembangan diri.</li>
                  <li><strong>Book club meeting:</strong> pertemuan rutin komunitas pecinta buku.</li>
                  <li><strong>Pameran dan exhibition:</strong> showcase karya seni, foto, atau hasil karya kreatif.</li>
                  <li><strong>Study group:</strong> sesi belajar bersama dan diskusi akademik.</li>
                  <li><strong>Community gathering:</strong> acara komunitas dan networking.</li>
                </ul>
                <div class="mt-4 rounded-lg bg-emerald-50 px-4 py-3 text-emerald-700">
                  <p class="font-semibold">Fasilitas yang tersedia:</p>
                  <p>Ruang serbaguna, proyektor, sound system, WiFi cepat, dan suasana yang inspiratif.</p>
                </div>
                <div class="mt-4 rounded-lg bg-orange-50 px-4 py-3 text-orange-800">
                  <p class="font-semibold">Tertarik mengadakan acara?</p>
                  <a href="#" data-link="whatsappEvent" class="font-bold">Hubungi kami untuk diskusi konsep acara dan penawaran khusus.</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
