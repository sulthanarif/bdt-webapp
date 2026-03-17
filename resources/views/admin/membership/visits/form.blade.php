@extends('admin.layouts.app')

@section('title', 'Tambah Kunjungan di Tempat')
@section('page_title', 'Tambah Kunjungan di Tempat')

@section('content')
  <div class="max-w-4xl space-y-6">
    <div>
      <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Membership</p>
      <h2 class="text-2xl font-semibold text-slate-900">Input Kunjungan di Tempat</h2>
      <p class="text-sm text-slate-500">Catat transaksi onsite: tiket harian atau pendaftaran member.</p>
    </div>

    @if ($errors->any())
      <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
        Lengkapi data yang masih kurang.
      </div>
    @endif

    <form method="POST" action="{{ route('admin.membership.visits.store') }}" class="space-y-6">
      @csrf

      <div class="grid gap-6 md:grid-cols-2">
        <div class="space-y-2 md:col-span-2">
          <label class="text-sm font-semibold text-slate-700">Jenis Transaksi Onsite</label>
          <div class="flex flex-wrap gap-3">
            <label class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700">
              <input type="radio" name="visit_kind" value="daily" {{ old('visit_kind', 'daily') === 'daily' ? 'checked' : '' }} class="h-4 w-4 text-teal-600">
              Tiket Harian
            </label>
            <label class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700">
              <input type="radio" name="visit_kind" value="member" {{ old('visit_kind') === 'member' ? 'checked' : '' }} class="h-4 w-4 text-teal-600">
              Pendaftaran Member
            </label>
          </div>
          @error('visit_kind')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2 md:col-span-2">
          <label class="text-sm font-semibold text-slate-700">Pilih Paket Kunjungan</label>
          <select name="daily_type_id" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200" data-daily-field>
            <option value="">Pilih paket</option>
            @foreach ($dailyTypes as $type)
              <option value="{{ $type->id }}" {{ (int) old('daily_type_id') === $type->id ? 'selected' : '' }}>
                {{ $type->name }} - Rp {{ number_format($type->pricing, 0, ',', '.') }}
              </option>
            @endforeach
          </select>
          @error('daily_type_id')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2 md:col-span-2 hidden" data-member-field>
          <label class="text-sm font-semibold text-slate-700">Pilih Paket Membership</label>
          <select name="member_type_id" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            <option value="">Pilih paket</option>
            @foreach ($memberTypes as $type)
              <option value="{{ $type->id }}" data-duration="{{ $type->duration_days }}" data-is-student="{{ $type->is_student ? '1' : '0' }}" {{ (int) old('member_type_id') === $type->id ? 'selected' : '' }}>
                {{ $type->name }} - Rp {{ number_format($type->pricing, 0, ',', '.') }}
              </option>
            @endforeach
          </select>
          @error('member_type_id')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">Nama Pengunjung</label>
          <input type="text" name="customer_name" value="{{ old('customer_name') }}" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('customer_name')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">Email</label>
          <input type="email" name="customer_email" value="{{ old('customer_email') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('customer_email')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">Nomor Kontak</label>
          <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('customer_phone')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">Tanggal Kunjungan</label>
          <input type="date" name="visit_date" value="{{ old('visit_date', now()->format('Y-m-d')) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('visit_date')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">Jenis Kelamin</label>
          <select name="gender" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            <option value="">Pilih</option>
            <option value="laki-laki" {{ old('gender') === 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
            <option value="perempuan" {{ old('gender') === 'perempuan' ? 'selected' : '' }}>Perempuan</option>
            <option value="lainnya" {{ old('gender') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
          </select>
          @error('gender')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">Jumlah Orang</label>
          <input type="number" name="qty" min="1" value="{{ old('qty', 1) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200" data-daily-field>
          @error('qty')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2 md:col-span-2 hidden" data-member-field>
          <label class="text-sm font-semibold text-slate-700">NIK</label>
          <input type="text" name="nik" value="{{ old('nik') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('nik')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2 hidden" data-member-field>
          <label class="text-sm font-semibold text-slate-700">Tanggal Lahir</label>
          <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('birth_date')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2 hidden" data-member-field>
          <label class="text-sm font-semibold text-slate-700">Domisili</label>
          <input type="text" name="domicile" value="{{ old('domicile') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('domicile')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2 hidden" data-member-field data-student-field>
          <label class="text-sm font-semibold text-slate-700">NIM (Pelajar)</label>
          <input type="text" name="nim" value="{{ old('nim') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('nim')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2 hidden md:col-span-2" data-member-field data-student-field>
          <label class="text-sm font-semibold text-slate-700">Kampus</label>
          <input type="text" name="university" value="{{ old('university') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('university')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2 hidden" data-member-field>
          <label class="text-sm font-semibold text-slate-700">Masa Berlaku</label>
          <input type="date" name="expired_at" value="{{ old('expired_at') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('expired_at')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <label class="inline-flex items-center gap-2 text-sm text-slate-600 hidden md:col-span-2" data-member-field data-student-verify>
          <input type="checkbox" name="is_verified_student" value="1" {{ old('is_verified_student') ? 'checked' : '' }} class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
          Mahasiswa sudah diverifikasi
        </label>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Pembayaran</p>
            <h3 class="text-lg font-semibold text-slate-900">Detail pembayaran di loket</h3>
          </div>
          <span class="text-xs text-slate-400">Invoice dibuat otomatis</span>
        </div>

        <div class="mt-6 grid gap-6 md:grid-cols-2">
          <!-- Status transaksi disembunyikan dan otomatis 'paid' di backend -->

          <div class="space-y-2 md:col-span-2">
            <label class="text-sm font-semibold text-slate-700">Gunakan Promo / Campaign (Opsional)</label>
            <select name="campaign_id" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
              <option value="">-- Tanpa Promo --</option>
              @foreach($campaigns as $camp)
                <option value="{{ $camp->id }}" 
                        data-target-type="{{ $camp->target_type }}" 
                        data-target-items="{{ json_encode($camp->target_items) }}"
                        {{ (int) old('campaign_id') === $camp->id ? 'selected' : '' }}>
                  {{ $camp->name }} {{ $camp->code ? '('.$camp->code.')' : '' }}
                </option>
              @endforeach
            </select>
            @error('campaign_id')
              <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">Metode Pembayaran</label>
            <select name="payment_method" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200" required>
              <option value="">Pilih Metode Pembayaran</option>
              <option value="Cash" {{ old('payment_method') === 'Cash' ? 'selected' : '' }}>Cash</option>
              <option value="QRIS" {{ old('payment_method') === 'QRIS' ? 'selected' : '' }}>QRIS</option>
              <option value="Transfer Bank" {{ old('payment_method') === 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
              <option value="Debit/Credit Card" {{ old('payment_method') === 'Debit/Credit Card' ? 'selected' : '' }}>Debit / Credit Card</option>
              <option value="E-Wallet" {{ old('payment_method') === 'E-Wallet' ? 'selected' : '' }}>E-Wallet (GoPay/OVO/Dana)</option>
              <option value="Hadiah / Gratis" {{ old('payment_method') === 'Hadiah / Gratis' ? 'selected' : '' }}>Hadiah / Gratis</option>
            </select>
            @error('payment_method')
              <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">Tanggal Bayar</label>
            <input type="date" name="paid_at" value="{{ old('paid_at', now()->format('Y-m-d')) }}" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            @error('paid_at')
              <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">Nama Pengirim / Pemilik Rekening</label>
            <input type="text" name="payment_sender_name" value="{{ old('payment_sender_name') }}" placeholder="Contoh: Budi Santoso" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            @error('payment_sender_name')
              <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">No. Rekening / Resi Pembayaran</label>
            <input type="text" name="payment_reference" value="{{ old('payment_reference') }}" placeholder="Contoh: BCA 1234567890 / 08123xxx" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            @error('payment_reference')
              <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <button type="submit" class="rounded-xl bg-teal-700 px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-teal-800">
          Simpan Kunjungan
        </button>
        <a href="{{ route('admin.membership.visits.index') }}" class="rounded-xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">
          Batal
        </a>
      </div>
    </form>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const kindRadios = Array.from(document.querySelectorAll('input[name="visit_kind"]'));
      const dailyFields = Array.from(document.querySelectorAll('[data-daily-field]'));
      const memberFields = Array.from(document.querySelectorAll('[data-member-field]'));
      const memberType = document.querySelector('select[name="member_type_id"]');
      const studentFields = Array.from(document.querySelectorAll('[data-student-field]'));
      const studentVerify = document.querySelector('[data-student-verify]');
      const expiredInput = document.querySelector('input[name="expired_at"]');

      const setExpiryFromType = () => {
        if (!memberType || !expiredInput) return;
        const option = memberType.selectedOptions[0];
        if (!option || !option.dataset.duration) {
            expiredInput.value = '';
            return;
        }
        const duration = Number(option.dataset.duration || 0);
        if (!duration) return;
        const today = new Date();
        today.setDate(today.getDate() + duration);
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        expiredInput.value = `${year}-${month}-${day}`;
      };

      const setStudentVisibility = (isStudent) => {
        studentFields.forEach((field) => {
          field.classList.toggle('hidden', !isStudent);
          field.querySelectorAll('input').forEach((input) => {
            input.required = isStudent;
          });
        });
        if (studentVerify) {
          studentVerify.classList.toggle('hidden', !isStudent);
          const checkbox = studentVerify.querySelector('input[type="checkbox"]');
          if (checkbox) {
            checkbox.disabled = !isStudent;
            if (!isStudent) {
              checkbox.checked = false;
            }
          }
        }
      };

      const setKindVisibility = (kind) => {
        const isMember = kind === 'member';
        dailyFields.forEach((field) => field.classList.toggle('hidden', isMember));
        memberFields.forEach((field) => field.classList.toggle('hidden', !isMember));
        if (!isMember) {
          setStudentVisibility(false);
        } else if (memberType) {
          const option = memberType.selectedOptions[0];
          setStudentVisibility(option?.dataset?.isStudent === '1');
        }
        
        // Memaksa reset dan filter ulang campaign dropdown tiap ganti tipe visit
        updateCampaignOptions();
      };

      kindRadios.forEach((radio) => {
        radio.addEventListener('change', () => setKindVisibility(radio.value));
      });

      const dailyType = document.querySelector('select[name="daily_type_id"]');
      const campaignSelect = document.querySelector('select[name="campaign_id"]');
      const campaignOptions = Array.from(document.querySelectorAll('select[name="campaign_id"] option:not([value=""])'));

      const updateCampaignOptions = () => {
        if (!campaignSelect) return;
        
        const kind = document.querySelector('input[name="visit_kind"]:checked')?.value;
        const targetType = kind === 'daily' ? 'ticket' : 'membership';
        const activeSelect = kind === 'daily' ? dailyType : memberType;
        const itemId = activeSelect?.value;

        campaignOptions.forEach(opt => {
          let isValid = true;
          const campTargetType = opt.dataset.targetType;
          
          if (campTargetType !== 'any' && campTargetType !== targetType) {
            isValid = false;
          }

          if (isValid && campTargetType !== 'any' && opt.dataset.targetItems && opt.dataset.targetItems !== 'null') {
            try {
              const items = JSON.parse(opt.dataset.targetItems);
              if (items && Object.keys(items).length > 0) {
                if (!itemId || !(itemId in items)) {
                  isValid = false;
                }
              }
            } catch (e) {}
          }

          opt.style.display = isValid ? 'block' : 'none';
          
          if (!isValid && opt.selected) {
            campaignSelect.value = '';
          }
        });
      };

      if (dailyType) dailyType.addEventListener('change', updateCampaignOptions);

      if (memberType) {
        memberType.addEventListener('change', () => {
          const option = memberType.selectedOptions[0];
          setStudentVisibility(option?.dataset?.isStudent === '1');
          setExpiryFromType();
          updateCampaignOptions();
        });
      }

      const initialKind = kindRadios.find((radio) => radio.checked)?.value || 'daily';
      setKindVisibility(initialKind);
      updateCampaignOptions();
    });
  </script>
@endsection
