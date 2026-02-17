  @php
    $selectedMemberTypeId = $selectedMemberTypeId ?? (int) old('member_type_id', $selectedMembershipId ?? 0);
    $selectedMemberType = $selectedMemberType ?? $memberTypes->firstWhere('id', $selectedMemberTypeId);
    $selectedIsTicket = $selectedIsTicket ?? ($selectedMemberType
      ? ($selectedMemberType->is_daily || \Illuminate\Support\Str::contains(\Illuminate\Support\Str::lower($selectedMemberType->name), 'harian'))
      : false);
    $isStudentSelected = $isStudentSelected ?? ($selectedIsTicket ? false : ($selectedMemberType?->is_student ?? false));
    $modalTitlePrefix = $modalTitlePrefix ?? ($selectedIsTicket ? 'Form Pembelian Tiket' : 'Form Pendaftaran Anggota');
    $modalTitle = $modalTitle ?? ($selectedMemberType
      ? $modalTitlePrefix . ' - ' . $selectedMemberType->name
      : $modalTitlePrefix);
    $shouldOpenModal = $shouldOpenModal ?? ($selectedMemberTypeId > 0 || $errors->any());
    $modalClasses = $shouldOpenModal ? '' : 'hidden';

    $initialStep = 1;
    $detailFields = ['nik', 'birth_date', 'domicile', 'nim', 'university'];
    foreach ($detailFields as $field) {
      if ($errors->has($field)) {
        $initialStep = 2;
        break;
      }
    }
  @endphp
  <div data-membership-modal data-open="{{ $shouldOpenModal ? 'true' : 'false' }}" data-step="{{ $initialStep }}" class="fixed inset-0 z-50 {{ $modalClasses }}">
    <div class="absolute inset-0 bg-slate-900/60" data-membership-close></div>
    <div class="relative mx-auto h-full w-full md:my-8 md:h-auto md:w-[92%] md:max-w-3xl">
      <div class="flex h-full flex-col bg-white md:max-h-[85vh] md:rounded-3xl md:border md:border-slate-200 md:shadow-2xl" data-membership-dialog>
        <div class="sticky top-0 z-10 flex items-start justify-between gap-4 border-b border-slate-100 bg-white/95 px-6 py-5 backdrop-blur md:px-8 md:py-6">
          <div class="pr-6">
            <h3 class="font-serif text-2xl font-bold text-slate-900" data-membership-title>{{ $modalTitle }}</h3>
          </div>
          <button type="button" data-membership-close class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:bg-slate-50">
            <span class="sr-only">Tutup</span>
            ×
          </button>
        </div>

        <div class="flex-1 overflow-y-auto px-6 pb-8 pt-5 md:px-8">
          <div data-membership-stepper class="mt-2 hidden">
            <div class="relative">
              <div class="absolute left-6 right-6 top-5 h-px bg-slate-200"></div>
              <div data-stepper-grid class="grid grid-cols-3 gap-3 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 sm:text-xs">
                <div class="relative z-10 flex flex-col items-center gap-2 text-center">
                  <div data-step-indicator="1" class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-400 shadow-sm">
                    <i data-lucide="user" class="h-4 w-4"></i>
                  </div>
                  <span data-step-label="1">Data Utama</span>
                </div>
                <div class="relative z-10 flex flex-col items-center gap-2 text-center">
                  <div data-step-indicator="2" class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-400 shadow-sm">
                    <i data-lucide="id-card" class="h-4 w-4"></i>
                  </div>
                  <span data-step-label="2">Detail</span>
                </div>
                <div class="relative z-10 flex flex-col items-center gap-2 text-center">
                  <div data-step-indicator="3" class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-400 shadow-sm">
                    <i data-lucide="clipboard-check" class="h-4 w-4"></i>
                  </div>
                  <span data-step-label="3">Konfirmasi</span>
                </div>
                <div data-step-4 class="relative z-10 flex flex-col items-center gap-2 text-center hidden">
                  <div data-step-indicator="4" class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-400 shadow-sm">
                    <i data-lucide="credit-card" class="h-4 w-4"></i>
                  </div>
                  <span data-step-label="4">Pembayaran</span>
                </div>
              </div>
            </div>
          </div>

          <form method="POST" action="{{ route('public.membership.register') }}" class="mt-6 grid gap-6 md:grid-cols-2" data-membership-form>
          @csrf
          <input type="hidden" name="member_type_id" value="{{ $selectedMemberTypeId }}" data-membership-input>
          @error('member_type_id')
            <div class="md:col-span-2 text-xs text-rose-600">{{ $message }}</div>
          @enderror

          <div data-member-step="1">
            <label class="text-sm font-semibold text-slate-700">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            @error('name')
              <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          <div data-member-step="1">
            <label class="text-sm font-semibold text-slate-700">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            @error('email')
              <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          <div data-member-step="1">
            <label class="text-sm font-semibold text-slate-700">Nomor Kontak</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            @error('phone')
              <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          <div data-member-step="1">
            <label class="text-sm font-semibold text-slate-700">Jenis Kelamin</label>
            <select name="gender" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
              <option value="">Pilih</option>
              <option value="laki-laki" {{ old('gender') === 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
              <option value="perempuan" {{ old('gender') === 'perempuan' ? 'selected' : '' }}>Perempuan</option>
              <option value="lainnya" {{ old('gender') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
            @error('gender')
              <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Daily Ticket: Qty field in step 1 --}}
          <div data-member-step="1" data-daily-fields class="hidden">
            <label class="text-sm font-semibold text-slate-700">Jumlah Orang</label>
            <input type="number" name="qty" min="1" value="{{ old('qty', 1) }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            @error('qty')
              <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          <div data-member-step="2" data-member-fields class="md:col-span-2 hidden">
            <label class="text-sm font-semibold text-slate-700">NIK</label>
            <input type="text" name="nik" value="{{ old('nik') }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            @error('nik')
              <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          <div data-member-step="2" data-member-fields class="hidden">
            <label class="text-sm font-semibold text-slate-700">Tanggal Lahir</label>
            <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            @error('birth_date')
              <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          <div data-member-step="2" data-member-fields class="hidden">
            <label class="text-sm font-semibold text-slate-700">Domisili</label>
            <input type="text" name="domicile" value="{{ old('domicile') }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            @error('domicile')
              <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          <div data-member-step="2" data-student-fields class="{{ $isStudentSelected ? '' : 'hidden' }}">
            <label class="text-sm font-semibold text-slate-700">NIM (khusus pelajar)</label>
            <input type="text" name="nim" value="{{ old('nim') }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            @error('nim')
              <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          <div data-member-step="2" data-student-fields class="md:col-span-2 {{ $isStudentSelected ? '' : 'hidden' }}">
            <label class="text-sm font-semibold text-slate-700">Nama Kampus (khusus pelajar)</label>
            <input type="text" name="university" value="{{ old('university') }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            @error('university')
              <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Daily Ticket: Confirmation step 2 --}}
          <div data-member-step="2" data-daily-fields class="md:col-span-2 rounded-2xl border border-teal-100 bg-teal-50/50 px-6 py-5 hidden">
            <h4 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
              <i data-lucide="clipboard-check" class="w-5 h-5 text-teal-600"></i>
              Konfirmasi Data Pemesanan
            </h4>
            <div class="space-y-3 text-sm text-slate-700">
              <div class="flex justify-between">
                <span class="text-slate-600">Nama:</span>
                <span class="font-semibold" data-confirm-name>-</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-600">Email:</span>
                <span class="font-semibold" data-confirm-email>-</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-600">No. Kontak:</span>
                <span class="font-semibold" data-confirm-phone>-</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-600">Jenis Kelamin:</span>
                <span class="font-semibold" data-confirm-gender>-</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-600">Jumlah Orang:</span>
                <span class="font-semibold" data-confirm-qty>-</span>
              </div>
            </div>
            <p class="mt-4 text-xs text-slate-500 italic">Silakan periksa kembali data di atas sebelum melanjutkan ke pembayaran.</p>
          </div>

          {{-- Member: Confirmation step 3 (no forms) --}}
          <div data-member-step="3" data-member-fields class="md:col-span-2 rounded-2xl border border-teal-100 bg-teal-50/50 px-6 py-5 hidden">
            <h4 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
              <i data-lucide="clipboard-check" class="w-5 h-5 text-teal-600"></i>
              Konfirmasi Data Pendaftaran
            </h4>
            <div class="grid md:grid-cols-2 gap-4 text-sm text-slate-700">
              <div>
                <span class="text-slate-600 block mb-1">Nama:</span>
                <span class="font-semibold" data-confirm-member-name>-</span>
              </div>
              <div>
                <span class="text-slate-600 block mb-1">Email:</span>
                <span class="font-semibold" data-confirm-member-email>-</span>
              </div>
              <div>
                <span class="text-slate-600 block mb-1">No. Kontak:</span>
                <span class="font-semibold" data-confirm-member-phone>-</span>
              </div>
              <div>
                <span class="text-slate-600 block mb-1">Jenis Kelamin:</span>
                <span class="font-semibold" data-confirm-member-gender>-</span>
              </div>
              <div>
                <span class="text-slate-600 block mb-1">NIK:</span>
                <span class="font-semibold" data-confirm-member-nik>-</span>
              </div>
              <div>
                <span class="text-slate-600 block mb-1">Tanggal Lahir:</span>
                <span class="font-semibold" data-confirm-member-birthdate>-</span>
              </div>
              <div class="md:col-span-2">
                <span class="text-slate-600 block mb-1">Domisili:</span>
                <span class="font-semibold" data-confirm-member-domicile>-</span>
              </div>
              <div data-confirm-member-student class="hidden">
                <span class="text-slate-600 block mb-1">NIM:</span>
                <span class="font-semibold" data-confirm-member-nim>-</span>
              </div>
              <div data-confirm-member-student class="md:col-span-2 hidden">
                <span class="text-slate-600 block mb-1">Nama Kampus:</span>
                <span class="font-semibold" data-confirm-member-university>-</span>
              </div>
            </div>
            <p class="mt-4 text-xs text-slate-500 italic">Silakan periksa kembali data di atas sebelum melanjutkan ke pembayaran.</p>
          </div>

          {{-- Daily Ticket: Payment info step 3 --}}
          <div data-member-step="3" data-daily-fields class="md:col-span-2 rounded-2xl border border-slate-200 bg-slate-50 px-5 py-5 text-sm text-slate-700 hidden">
            <h4 class="text-sm font-bold text-slate-800 mb-4">Metode Pembayaran</h4>
            <div class="grid gap-4 md:grid-cols-2">
              <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                <input type="radio" name="payment_method" value="qris" class="mt-1 h-4 w-4 text-teal-600" data-payment-option checked>
                <span>
                  <span class="block text-sm font-semibold text-slate-800">QRIS</span>
                  <span class="block text-xs text-slate-500">Scan QR statis (instan)</span>
                </span>
              </label>
              <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                <input type="radio" name="payment_method" value="bank_transfer" class="mt-1 h-4 w-4 text-teal-600" data-payment-option>
                <span>
                  <span class="block text-sm font-semibold text-slate-800">Transfer Bank</span>
                  <span class="block text-xs text-slate-500">Manual via rekening</span>
                </span>
              </label>
            </div>
            <div class="mt-5">
              <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
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
            </div>
          </div>

          {{-- Member: Payment info step 4 --}}
          <div data-member-step="4" data-member-fields class="md:col-span-2 rounded-2xl border border-slate-200 bg-slate-50 px-5 py-5 text-sm text-slate-700 hidden">
            <h4 class="text-sm font-bold text-slate-800 mb-4">Metode Pembayaran</h4>
            <div class="grid gap-4 md:grid-cols-2">
              <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                <input type="radio" name="payment_method" value="qris" class="mt-1 h-4 w-4 text-teal-600" data-payment-option checked>
                <span>
                  <span class="block text-sm font-semibold text-slate-800">QRIS</span>
                  <span class="block text-xs text-slate-500">Scan QR statis (instan)</span>
                </span>
              </label>
              <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                <input type="radio" name="payment_method" value="bank_transfer" class="mt-1 h-4 w-4 text-teal-600" data-payment-option>
                <span>
                  <span class="block text-sm font-semibold text-slate-800">Transfer Bank</span>
                  <span class="block text-xs text-slate-500">Manual via rekening</span>
                </span>
              </label>
            </div>
            <div class="mt-5">
              <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
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
            </div>
          </div>

          <div class="md:col-span-2 flex flex-wrap items-center justify-between gap-4" data-member-actions>
            <div class="flex items-center gap-3">
              <button type="button" data-step-back class="rounded-2xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                Kembali
              </button>
              <button type="button" data-step-next class="rounded-2xl bg-teal-600 px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-teal-700">
                Lanjut
              </button>
            </div>
            <button type="button" data-submit-button data-payment-open class="inline-flex items-center gap-2 rounded-2xl bg-teal-700 px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-teal-800 hidden">
              Proses Pembayaran
            </button>
          </div>
        </form>
        </div>
      </div>
    </div>
