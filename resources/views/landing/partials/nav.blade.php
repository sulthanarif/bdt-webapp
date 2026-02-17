  <nav data-navbar class="fixed top-0 w-full z-50 transition-all duration-300 bg-transparent py-6">
    <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
      <button type="button" data-scroll-top class="flex items-center gap-2">
        <img src="{{ asset('images/bdt-logo.png') }}" alt="Baca Di Tebet Logo" class="h-14" />
      </button>

      <div class="md:hidden">
        <button type="button" data-menu-toggle class="text-slate-800" aria-expanded="false" aria-label="Toggle menu">
          <i data-menu-icon="open" data-lucide="menu" class="w-7 h-7"></i>
          <i data-menu-icon="close" data-lucide="x" class="w-7 h-7 hidden"></i>
        </button>
      </div>
    </div>

    <div data-mobile-menu class="md:hidden absolute top-full left-0 w-full bg-white shadow-lg py-4 px-6 flex flex-col gap-4 border-t border-slate-100 hidden">
      <button type="button" data-nav-link data-tab-target="about" data-scroll="true" class="text-left text-slate-700 font-medium py-2 border-b border-slate-50">Tentang Kami</button>
      <button type="button" data-nav-link data-tab-target="facilities" data-scroll="true" class="text-left text-slate-700 font-medium py-2 border-b border-slate-50">Fasilitas</button>
      <button type="button" data-nav-link data-tab-target="pricing" data-scroll="true" class="text-left text-slate-700 font-medium py-2 border-b border-slate-50">Pendaftaran</button>
      <button type="button" data-nav-link data-tab-target="agenda" data-scroll="true" class="text-left text-slate-700 font-medium py-2 border-b border-slate-50">Agenda</button>
      <button type="button" data-nav-link data-tab-target="blog" data-scroll="true" class="text-left text-slate-700 font-medium py-2 border-b border-slate-50">Blog</button>
      <button type="button" data-nav-link data-tab-target="contact" data-scroll="true" class="text-left text-slate-700 font-medium py-2 border-b border-slate-50">Kontak</button>
      <button type="button" data-tab-target="pricing" data-scroll="true" class="bg-teal-700 text-white text-center py-3 rounded-lg font-medium w-full">Daftar Anggota</button>
    </div>
  </nav>
