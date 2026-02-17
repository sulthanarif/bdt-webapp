    <div data-payment-modal class="fixed inset-0 z-[60] hidden">
      <div class="absolute inset-0 bg-slate-900/70" data-payment-close></div>
      <div class="relative mx-auto mt-24 w-[92%] max-w-md">
        <div class="max-h-[85vh] overflow-y-auto rounded-3xl border border-slate-200 bg-white p-6 shadow-2xl">
          <div class="flex items-center justify-between">
            <h4 class="font-semibold text-slate-900">Menunggu Konfirmasi</h4>
            <button type="button" data-payment-close class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:bg-slate-50">
              ×
            </button>
          </div>
          <p class="mt-3 text-sm text-slate-600">
            Silakan selesaikan pembayaran Anda. Setelah pembayaran berhasil, klik tombol konfirmasi di bawah.
          </p>
          <div class="mt-5 rounded-2xl border border-slate-200 bg-white px-4 py-4">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 mb-3">Rincian Pembayaran</p>
            <div class="space-y-2 text-sm text-slate-700">
              <div class="flex items-center justify-between">
                <span>Paket</span>
                <span class="font-semibold" data-payment-package>-</span>
              </div>
              <div class="flex items-center justify-between">
                <span>Jumlah</span>
                <span class="font-semibold" data-payment-qty>-</span>
              </div>
              <div class="flex items-center justify-between">
                <span>Metode</span>
                <span class="font-semibold" data-payment-method>-</span>
              </div>
              <div class="flex items-center justify-between text-base">
                <span>Total</span>
                <span class="font-semibold text-slate-900" data-payment-total>-</span>
              </div>
            </div>
          </div>
          <div class="mt-5 space-y-4">
            <div data-payment-panel="qris" class="rounded-2xl border border-emerald-100 bg-emerald-50/60 px-4 py-4">
              <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700 mb-3">QRIS Statik</p>
              <div class="flex flex-col gap-4 md:flex-row md:items-center">
                <div class="h-40 w-40 rounded-2xl border border-emerald-200 bg-white p-3 flex items-center justify-center">
                  <img src="{{ asset('images/qr-statis-qrisbdt.svg') }}" alt="QRIS BDT" class="h-full w-full object-contain">
                </div>
                <div>
                  <p class="text-sm font-semibold text-slate-800">BDT QRIS Static</p>
                  <p class="text-xs text-slate-500">Scan dengan aplikasi pembayaran apapun.</p>
                </div>
              </div>
            </div>
            <div data-payment-panel="bank_transfer" class="rounded-2xl border border-slate-200 bg-white px-4 py-4 hidden">
              <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 mb-3">Rekening Transfer</p>
              <div class="space-y-2 text-sm text-slate-700">
                <div class="flex items-center justify-between">
                  <span>Bank BCA</span>
                  <span class="font-semibold">1234567890</span>
                </div>
                <div class="flex items-center justify-between">
                  <span>Atas Nama</span>
                  <span class="font-semibold">Baca Di Tebet</span>
                </div>
              </div>
            </div>
          </div>
          <div class="mt-5 rounded-2xl border border-emerald-100 bg-emerald-50/60 px-4 py-4 text-center">
            <p class="text-xs uppercase tracking-[0.2em] text-emerald-700 mb-2">Sisa Waktu</p>
            <div data-payment-countdown class="text-3xl font-bold text-slate-900">10:00</div>
          </div>
          <button type="button" data-payment-submit class="mt-6 w-full rounded-2xl bg-teal-700 px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-teal-800">
            Saya sudah bayar
          </button>
        </div>
      </div>
    </div>
