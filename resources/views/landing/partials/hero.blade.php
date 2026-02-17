  <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-[#F9F7F2]">
    <div class="absolute top-0 right-0 w-1/2 h-full bg-teal-50 opacity-60 skew-x-12 translate-x-20 z-0 hidden lg:block"></div>
    <div class="absolute -top-24 -left-24 w-72 h-72 rounded-full bg-teal-100/60 blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
      <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
        <div class="w-full lg:w-1/2">
          <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-teal-100 text-teal-800 rounded-full text-xs font-bold tracking-wider mb-6 uppercase">
            <i data-lucide="heart" class="w-3 h-3"></i>
            Ruang untuk Tumbuh Bersama
          </div>
          <h1 class="font-serif text-4xl md:text-5xl lg:text-7xl font-bold text-slate-900 leading-tight mb-6">
            Di Mana <span class="text-teal-700 italic">Waktu Berhenti</span> dan Pikiran Mulai
          </h1>
          <p class="text-lg text-slate-600 mb-8 leading-relaxed">
            Karena tidak semua yang berharga harus cepat. Di tengah hiruk-pikuk Jakarta, kami hadirkan ruang untuk
            <span class="font-semibold text-slate-800">berpikir tenang, membaca mendalam, dan bertumbuh bersama</span>.
            Lebih dari perpustakaan—ini adalah rumah kedua untuk jiwa pencari ilmu.
          </p>

          <div class="flex flex-col sm:flex-row gap-4">
            <button type="button" data-tab-target="pricing" data-scroll="true" class="px-8 py-4 bg-teal-700 text-white rounded-lg font-medium hover:bg-teal-800 transition-colors flex items-center justify-center gap-2 shadow-lg hover:shadow-xl hover:-translate-y-1 transform duration-200">
              Gabung Sekarang <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </button>
            <button type="button" data-tab-target="facilities" data-scroll="true" class="px-8 py-4 bg-white border border-slate-300 text-slate-800 rounded-lg font-medium hover:bg-slate-50 transition-colors flex items-center justify-center gap-2">
              <i data-lucide="eye" class="w-4 h-4 text-teal-600"></i> Lihat Ruangan
            </button>
          </div>
        </div>

        <div class="w-full lg:w-1/2 relative hidden md:block">
          <div class="relative rounded-2xl overflow-hidden shadow-2xl border-4 border-white transform rotate-2 hover:rotate-0 transition-transform duration-500">
            <img src="{{ asset('images/3_baca_di_tebet_web.png') }}" alt="Suasana Baca Di Tebet" class="w-full h-auto object-cover" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent pointer-events-none"></div>
          </div>
          <div class="absolute -z-10 top-10 -right-10 w-full h-full border-2 border-teal-200 rounded-2xl"></div>
        </div>
      </div>
    </div>
  </section>
