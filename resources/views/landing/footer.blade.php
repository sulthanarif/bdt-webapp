<footer class="bg-white border-t border-slate-200 pt-16 pb-8">
  <div class="max-w-7xl mx-auto px-6">
    <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12">
      <div class="flex items-center gap-2">
        <img src="{{ asset('images/bdt-logo.png') }}" alt="Baca Di Tebet Logo" class="h-14" />
      </div>
      <div class="flex gap-6 text-sm text-slate-500 font-medium">
        <a href="{{ route('privacy') }}" class="hover:text-teal-700">{{ $content['footer']['privacy_label'] ?? 'Privasi' }}</a>
        <a href="{{ route('terms') }}" class="hover:text-teal-700">{{ $content['footer']['terms_label'] ?? 'Syarat dan Ketentuan' }}</a>
        <a href="{{ route('faq') }}" class="hover:text-teal-700">{{ $content['footer']['faq_label'] ?? 'FAQ' }}</a>
      </div>
    </div>
    <div class="text-center text-slate-400 text-sm">
      &copy; <span id="copyright-year"></span> Baca Di Tebet. All rights reserved.
    </div>
  </div>
</footer>

