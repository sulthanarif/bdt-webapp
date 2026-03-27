@extends('admin.layouts.app')

@section('title', $campaign->exists ? 'Edit Campaign' : 'Tambah Campaign')
@section('page_title', $campaign->exists ? 'Edit Campaign' : 'Tambah Campaign')

@section('content')
  <div class="max-w-4xl space-y-6">
    <div class="flex items-center gap-4">
      <a href="{{ route('admin.campaigns.index') }}" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-50">
        <i data-lucide="arrow-left" class="h-5 w-5"></i>
      </a>
      <div>
        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Pemasaran</p>
        <h2 class="text-2xl font-semibold text-slate-900">{{ $campaign->exists ? 'Edit Campaign' : 'Campaign & Voucher Baru' }}</h2>
      </div>
    </div>

    @if ($errors->any())
      <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
        Mohon periksa kembali form Anda.
      </div>
    @endif

    <form id="campaign-form" action="{{ $campaign->exists ? route('admin.campaigns.update', $campaign) : route('admin.campaigns.store') }}" method="POST" class="space-y-6">
      @csrf
      @if ($campaign->exists)
        @method('PUT')
      @endif

      <input type="hidden" name="discount_type" id="actual_discount_type" value="{{ old('discount_type', $campaign->discount_type ?? 'percentage') }}">

      <!-- STEP 1: JENIS PROMO -->
      <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="font-serif text-lg font-semibold text-slate-900 mb-2">1. Jenis Promo</h3>
        <p class="text-sm text-slate-500 mb-6">Pilih bagaimana promo ini akan digunakan oleh pelanggan.</p>
        
        <div class="grid gap-4 md:grid-cols-2">
            <label class="cursor-pointer relative">
                <input type="radio" name="promo_method" value="auto" class="peer sr-only" {{ old('promo_method', $campaign->code ? 'voucher' : 'auto') === 'auto' ? 'checked' : '' }}>
                <div class="rounded-xl border-2 border-slate-200 p-5 peer-checked:border-teal-500 peer-checked:bg-teal-50 hover:bg-slate-50 transition-all">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-lucide="zap" class="w-5 h-5 text-teal-600"></i>
                        <h4 class="font-bold text-slate-900">Promo Otomatis</h4>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">Berlaku langsung tanpa perlu kode. Akan menampilkan harga coret/badge promo khusus.</p>
                </div>
            </label>

            <label class="cursor-pointer relative">
                <input type="radio" name="promo_method" value="voucher" class="peer sr-only" {{ old('promo_method', $campaign->code ? 'voucher' : 'auto') === 'voucher' ? 'checked' : '' }}>
                <div class="rounded-xl border-2 border-slate-200 p-5 peer-checked:border-teal-500 peer-checked:bg-teal-50 hover:bg-slate-50 transition-all">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-lucide="ticket" class="w-5 h-5 text-teal-600"></i>
                        <h4 class="font-bold text-slate-900">Kode Voucher</h4>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">Pelanggan harus memasukkan kode rahasia saat mendaftar/checkout di form.</p>
                </div>
            </label>
        </div>

        <div id="voucher_fields" class="mt-6 gap-6 grid md:grid-cols-2 pt-6 border-t border-slate-100 hidden">
            <div class="space-y-2">
                <label for="code" class="text-sm font-semibold text-slate-700">Buat Kode Kupon / Voucher</label>
                <input id="code" name="code" type="text" value="{{ old('code', $campaign->code) }}" placeholder="Contoh: BDTPROMO50" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm uppercase font-mono focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                @error('code')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div class="space-y-2">
                <label for="max_uses" class="text-sm font-semibold text-slate-700">Kuota Maksimal Penggunaan <span class="font-normal text-slate-400">(Opsional)</span></label>
                <input id="max_uses" name="max_uses" type="number" min="1" value="{{ old('max_uses', $campaign->max_uses) }}" placeholder="Kosongkan jika kuota tak terbatas" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                @error('max_uses')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>
      </div>

      <!-- STEP 2: BENTUK REWARD -->
      <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="font-serif text-lg font-semibold text-slate-900 mb-2">2. Bentuk Promo & Nilai</h3>
        <p class="text-sm text-slate-500 mb-6">Pilih jenis keuntungan yang didapatkan oleh pelanggan nantinya.</p>
        
        <div class="grid gap-4 md:grid-cols-2 mb-6">
            @php
                $isDuration = old('discount_type', $campaign->discount_type) === 'duration';
            @endphp
            <label class="cursor-pointer relative">
                <input type="radio" name="reward_type" value="discount" class="peer sr-only" {{ !$isDuration ? 'checked' : '' }}>
                <div class="rounded-xl border-2 border-slate-200 p-5 peer-checked:border-teal-500 peer-checked:bg-teal-50 hover:bg-slate-50 transition-all">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-lucide="tag" class="w-5 h-5 text-teal-600"></i>
                        <h4 class="font-bold text-slate-900">Potongan Harga</h4>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">Diskon berupa potongan Nominal Rupiah yang tetap atau Persentase harga produk.</p>
                </div>
            </label>

            <label class="cursor-pointer relative">
                <input type="radio" name="reward_type" value="duration" class="peer sr-only" {{ $isDuration ? 'checked' : '' }}>
                <div class="rounded-xl border-2 border-slate-200 p-5 peer-checked:border-teal-500 peer-checked:bg-teal-50 hover:bg-slate-50 transition-all">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-lucide="clock" class="w-5 h-5 text-teal-600"></i>
                        <h4 class="font-bold text-slate-900">Ekstra Durasi (Lebih Fleksibel)</h4>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">Tidak memotong harga, tapi memberikan bonus tambahan hari *khusus untuk paket Membership*.</p>
                </div>
            </label>
        </div>

        <div class="pt-6 border-t border-slate-100">
          <div id="discount_method_container" class="max-w-xs space-y-2">
            <label for="discount_method" class="text-sm font-semibold text-slate-700">Cara Hitung Potongan</label>
            <select id="discount_method" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
              <option value="percentage" {{ old('discount_type', $campaign->discount_type) === 'percentage' ? 'selected' : '' }}>Persentase Diskon (%)</option>
              <option value="fixed" {{ old('discount_type', $campaign->discount_type) === 'fixed' ? 'selected' : '' }}>Nominal Diskon Tetap (Rp)</option>
            </select>
            @error('discount_type')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
          </div>

          {{-- Global value — hanya muncul saat target_type = 'any' --}}
          @php $initTargetType = old('target_type', $campaign->target_type ?? 'any'); @endphp
          <div id="global_discount_value_container" class="max-w-xs space-y-2 mt-4"
               style="{{ $initTargetType !== 'any' ? 'display:none' : '' }}">
            <label for="discount_value" class="text-sm font-semibold text-slate-700">
              <span id="discount_value_label">Nilai Diskon</span>
              <span id="discount_value_unit" class="text-slate-400 font-normal text-xs"> (% dari harga)</span>
            </label>
            <div class="flex gap-2 items-stretch">
              <input id="discount_value" name="discount_value" type="number" min="1"
                     value="{{ old('discount_value', $campaign->discount_value) }}"
                     placeholder="Contoh: 20 untuk 20%, atau 50000 untuk Rp 50.000"
                     class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
              <select id="duration_unit_select" class="hidden w-28 rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                <option value="hari">Hari</option>
                <option value="bulan">Bulan</option>
              </select>
            </div>
            <p id="discount_value_helper" class="text-xs text-slate-500">Berlaku merata untuk semua paket & event dalam campaign ini.</p>
            @error('discount_value')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
          </div>
        </div>
      </div>

      <!-- STEP 3: INFORMASI UMUM -->
      <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="font-serif text-lg font-semibold text-slate-900 mb-2">3. Informasi Utama</h3>
        <p class="text-sm text-slate-500 mb-6">Nama dan pemberitahuan di dashboard.</p>
        
        <div class="grid gap-6 md:grid-cols-2">
          <div class="space-y-2 md:col-span-2">
            <label for="name" class="text-sm font-semibold text-slate-700">Nama Lengkap Promo / Campaign</label>
            <input id="name" name="name" type="text" value="{{ old('name', $campaign->name) }}" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            @error('name')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
          </div>

          <div class="space-y-2 md:col-span-2">
            <label for="description" class="text-sm font-semibold text-slate-700">Deskripsi Singkat <span class="font-normal text-slate-400">(Opsional)</span></label>
            <textarea id="description" name="description" rows="2" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">{{ old('description', $campaign->description) }}</textarea>
            @error('description')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
          </div>
        </div>
      </div>

      <!-- STEP 4: PERUNTUKAN -->
      <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="font-serif text-lg font-semibold text-slate-900 mb-2">4. Syarat Berlaku & Targeting</h3>
        <p class="text-sm text-slate-500 mb-6">Tentukan siapa yang berhak mendapatkan promo ini.</p>
        
        <div class="grid gap-6 md:grid-cols-2">
          <div class="space-y-2">
            <label for="target_type" class="text-sm font-semibold text-slate-700">Target Tersedia Untuk</label>
            <select id="target_type" name="target_type" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
              <option value="any" {{ old('target_type', $campaign->target_type) === 'any' ? 'selected' : '' }}>Semua Layanan (Membership & Event)</option>
              <option value="membership" {{ old('target_type', $campaign->target_type) === 'membership' ? 'selected' : '' }}>Hanya Paket Membership</option>
              <option id="opt_events" value="event" {{ old('target_type', $campaign->target_type) === 'event' ? 'selected' : '' }}>Hanya Tiket Event Tertentu</option>
            </select>
            @error('target_type')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
            <p id="alert_reward_duration" class="text-xs font-semibold mt-1 text-teal-600 hidden">Promo "Ekstra Durasi" hanya bisa berlaku untuk Membership.</p>
          </div>

          <div class="space-y-2" id="applicable_to_container">
            <label for="applicable_to" class="text-sm font-semibold text-slate-700">Status Pendaftar <span class="text-slate-400 font-normal">(Khusus Membership)</span></label>
            <select id="applicable_to" name="applicable_to" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
              <option value="any" {{ old('applicable_to', $campaign->applicable_to) === 'any' ? 'selected' : '' }}>Berlaku Akun Baru & Perpanjangan Langganan</option>
              <option value="new_member" {{ old('applicable_to', $campaign->applicable_to) === 'new_member' ? 'selected' : '' }}>Hanya Promo Anggota Baru</option>
              <option value="renewal" {{ old('applicable_to', $campaign->applicable_to) === 'renewal' ? 'selected' : '' }}>Hanya Perpanjangan / Renewal</option>
            </select>
            @error('applicable_to')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
          </div>

          <div class="space-y-4 md:col-span-2 pt-4 border-t border-slate-100" id="target_items_membership_container" style="display: none;">
            <label class="text-sm font-semibold text-slate-700 flex flex-col">
                <span>Pilih Paket Membership & Nilai Diskon per Plan</span>
                <span class="text-slate-400 font-normal text-xs">Centang paket lalu masukkan nilai diskonnya. Paket yang tidak dicentang <strong class="text-slate-500">tidak mendapat diskon</strong> dari campaign ini.</span>
            </label>
            <div class="flex flex-col gap-3 mt-2">
                @foreach($memberships as $membership)
                    @php
                        $isChecked = array_key_exists((string)$membership->id, old('target_items_checked', $campaign->target_items ?? []));
                        $overrideVal = old('target_item_overrides.'.$membership->id, $campaign->target_items[(string)$membership->id] ?? '');
                    @endphp
                    <div class="flex items-center gap-3 py-2 px-3 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors w-full">
                        <label class="flex items-center gap-3 cursor-pointer flex-1">
                            <input type="checkbox" name="target_items_checked[]" value="{{ $membership->id }}" {{ $isChecked ? 'checked' : '' }} class="h-5 w-5 rounded border-slate-300 text-teal-600 focus:ring-teal-500 target-checkbox">
                            <span class="text-sm font-medium text-slate-700">{{ $membership->name }} <span class="text-xs text-slate-400 font-normal ml-1">({{ $membership->is_daily ? 'Kunjungan Harian' : 'Berlangganan' }})</span></span>
                        </label>
                        <div class="w-1/3 min-w-[150px]">
                            <input type="number" name="target_item_overrides[{{ $membership->id }}]" value="{{ $overrideVal }}" placeholder="Nilai diskon plan ini..." min="1" {{ $isChecked ? '' : 'disabled' }} class="target-override w-full rounded-md border border-slate-200 bg-white px-3 py-1.5 text-xs focus:border-teal-500 focus:ring-1 focus:ring-teal-200">
                        </div>
                    </div>
                @endforeach
            </div>
          </div>

          <div class="space-y-4 md:col-span-2 pt-4 border-t border-slate-100" id="target_items_event_container" style="display: none;">
            <label class="text-sm font-semibold text-slate-700 flex flex-col">
                <span>Pilih Event Target</span>
                <span class="text-slate-400 font-normal text-xs">Pilih event yang menerima voucher ini. Biarkan kosong jika berlaku untuk SEMUA Event yang Aktif.</span>
            </label>
            <div class="flex flex-col gap-3 mt-2">
                @foreach($events as $event)
                    @php
                        $isChecked = array_key_exists((string)$event->id, old('target_items_checked', $campaign->target_items ?? []));
                        $overrideVal = old('target_item_overrides.'.$event->id, $campaign->target_items[(string)$event->id] ?? '');
                    @endphp
                    <div class="flex items-center gap-3 py-2 px-3 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors w-full">
                        <label class="flex items-center gap-3 cursor-pointer flex-1">
                            <input type="checkbox" name="target_items_checked[]" value="{{ $event->id }}" {{ $isChecked ? 'checked' : '' }} class="h-5 w-5 rounded border-slate-300 text-teal-600 focus:ring-teal-500 target-checkbox">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-slate-700">{{ $event->title }}</span>
                                <span class="text-xs text-slate-400 font-normal">{{ $event->starts_at->format('d M Y') }}</span>
                            </div>
                        </label>
                        <div class="w-1/3 min-w-[150px]">
                            <input type="number" name="target_item_overrides[{{ $event->id }}]" value="{{ $overrideVal }}" placeholder="Nilai diskon event ini..." min="1" {{ $isChecked ? '' : 'disabled' }} class="target-override w-full rounded-md border border-slate-200 bg-white px-3 py-1.5 text-xs focus:border-teal-500 focus:ring-1 focus:ring-teal-200">
                        </div>
                    </div>
                @endforeach
            </div>
          </div>
        </div>
      </div>

      <!-- STEP 5: PERIODE -->
      <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="font-serif text-lg font-semibold text-slate-900 mb-2">5. Periode Berlaku</h3>
        <p class="text-sm text-slate-500 mb-6">Penjadwalan mulainya promo.</p>
        
        <div class="grid gap-6 md:grid-cols-2">
          <div class="space-y-2">
            <label for="valid_from" class="text-sm font-semibold text-slate-700">Waktu Mulai Berlaku <span class="font-normal text-slate-400">(Opsional)</span></label>
            <input id="valid_from" name="valid_from" type="datetime-local" value="{{ old('valid_from', $campaign->valid_from?->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            @error('valid_from')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
          </div>

          <div class="space-y-2">
            <label for="valid_until" class="text-sm font-semibold text-slate-700">Waktu Batas Berakhir <span class="font-normal text-slate-400">(Opsional)</span></label>
            <input id="valid_until" name="valid_until" type="datetime-local" value="{{ old('valid_until', $campaign->valid_until?->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            @error('valid_until')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
          </div>

          <div class="space-y-2 md:col-span-2 pt-4 border-t border-slate-100">
            <label class="flex items-center gap-3 bg-emerald-50 rounded-xl p-4 border border-emerald-100 cursor-pointer">
              <input type="checkbox" name="is_active" value="1" {{ old('is_active', $campaign->exists ? $campaign->is_active : true) ? 'checked' : '' }} class="h-6 w-6 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
              <div class="flex flex-col">
                  <span class="text-sm font-bold text-emerald-800">Status Promosi Dinyalakan</span>
                  <span class="text-xs text-emerald-600 leading-snug tracking-wide">Jika Anda mematikan centang ini, promo apapun ini tidak bisa digunakan / diaktifkan untuk checkout pelanggan sama sekali.</span>
              </div>
            </label>
          </div>
        </div>
      </div>

      <!-- FOOTER -->
      <div class="flex items-center gap-3">
        <button type="submit" class="rounded-xl bg-teal-700 px-8 py-3.5 text-sm font-semibold text-white shadow-md hover:bg-teal-800 transition-colors flex items-center gap-2">
          <i data-lucide="save" class="w-4 h-4"></i>
          Simpan Campaign Ini
        </button>
        <a href="{{ route('admin.campaigns.index') }}" class="rounded-xl border border-slate-200 px-6 py-3.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
          Batal dan Kembali
        </a>
      </div>
    </form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
        // UI Nodes
        const promoMethodRadios = document.querySelectorAll('input[name="promo_method"]');
        const voucherFields = document.getElementById('voucher_fields');
        const codeInput = document.getElementById('code');

        const rewardTypeRadios = document.querySelectorAll('input[name="reward_type"]');
        const discountMethodSelect = document.getElementById('discount_method');
        const discountMethodContainer = document.getElementById('discount_method_container');
        const actualDiscountTypeInput = document.getElementById('actual_discount_type');
        const globalDiscountValueContainer = document.getElementById('global_discount_value_container');
        const discountValueUnit = document.getElementById('discount_value_unit');
        const discountValueLabel = document.getElementById('discount_value_label');
        const discountValueInput = document.getElementById('discount_value');
        const discountValueHelper = document.getElementById('discount_value_helper');
        const durationUnitSelect = document.getElementById('duration_unit_select');

        const targetTypeSelect = document.getElementById('target_type');
        const applicableToContainer = document.getElementById('applicable_to_container');
        const applicableToSelect = document.getElementById('applicable_to');
        const targetMembershipsContainer = document.getElementById('target_items_membership_container');
        const targetEventsContainer = document.getElementById('target_items_event_container');
        const optEvents = document.getElementById('opt_events');
        const alertRewardDuration = document.getElementById('alert_reward_duration');

        const form = document.getElementById('campaign-form');

        // Logic 1: Method (Auto vs Voucher)
        const updateMethodUI = () => {
            const isVoucher = document.querySelector('input[name="promo_method"]:checked').value === 'voucher';
            if (isVoucher) {
                voucherFields.classList.remove('hidden');
            } else {
                voucherFields.classList.add('hidden');
                codeInput.value = '';
            }
        };

        // Logic 2: Reward (Discount vs Duration)
        const updateRewardUI = () => {
            const isDuration = document.querySelector('input[name="reward_type"]:checked').value === 'duration';

            if (isDuration) {
                discountMethodContainer.style.display = 'none';
                optEvents.disabled = true;
                if (targetTypeSelect.value === 'event') {
                    targetTypeSelect.value = 'membership';
                }
                alertRewardDuration.classList.remove('hidden');
                if (discountValueLabel) discountValueLabel.textContent = 'Tambahan Durasi Membership';
                if (discountValueUnit) discountValueUnit.textContent = '';
                if (durationUnitSelect) durationUnitSelect.classList.remove('hidden');
                if (discountValueInput) discountValueInput.placeholder = 'Contoh: 30 atau 3';
                if (discountValueHelper) discountValueHelper.textContent = 'Jumlah hari/bulan yang ditambahkan ke durasi membership pelanggan.';
                document.querySelectorAll('#target_items_membership_container .target-override').forEach(el => {
                    el.placeholder = 'Hari bonus untuk plan ini...';
                });
            } else {
                discountMethodContainer.style.display = 'block';
                optEvents.disabled = false;
                alertRewardDuration.classList.add('hidden');
                if (discountValueLabel) discountValueLabel.textContent = 'Nilai Diskon';
                if (durationUnitSelect) durationUnitSelect.classList.add('hidden');
                if (discountValueInput) discountValueInput.placeholder = 'Contoh: 20 untuk 20%, atau 50000 untuk Rp 50.000';
                if (discountValueHelper) discountValueHelper.textContent = 'Berlaku merata untuk semua paket & event dalam campaign ini.';
                if (discountValueUnit) {
                    discountValueUnit.textContent = discountMethodSelect.value === 'fixed'
                        ? ' (Rp nominal tetap)'
                        : ' (% dari harga)';
                }
                document.querySelectorAll('#target_items_membership_container .target-override').forEach(el => {
                    el.placeholder = 'Nilai diskon plan ini...';
                });
            }

            updateTargetingUI();
        };

        // Logic 3: Targeting Engine
        const updateTargetingUI = () => {
            const targetType = targetTypeSelect.value;
            const isAny = targetType === 'any';

            if (globalDiscountValueContainer) {
                globalDiscountValueContainer.style.display = isAny ? 'block' : 'none';
            }

            if (targetType === 'membership') {
                applicableToContainer.style.display = 'block';
                targetMembershipsContainer.style.display = 'block';
                targetEventsContainer.style.display = 'none';
            } else if (targetType === 'event') {
                applicableToContainer.style.display = 'none';
                targetMembershipsContainer.style.display = 'none';
                targetEventsContainer.style.display = 'block';
                applicableToSelect.value = 'any';
            } else {
                applicableToContainer.style.display = 'none';
                targetMembershipsContainer.style.display = 'none';
                targetEventsContainer.style.display = 'none';
                applicableToSelect.value = 'any';
            }
        };

        // Pre Form-Submit logic
        form.addEventListener('submit', () => {
             const isDuration = document.querySelector('input[name="reward_type"]:checked').value === 'duration';
             if (isDuration) {
                 actualDiscountTypeInput.value = 'duration';
                 // Konversi bulan ke hari (1 bulan = 30 hari)
                 if (durationUnitSelect && durationUnitSelect.value === 'bulan' && discountValueInput && discountValueInput.value) {
                     discountValueInput.value = parseInt(discountValueInput.value) * 30;
                 }
             } else {
                 actualDiscountTypeInput.value = discountMethodSelect.value;
             }

             const targetType = targetTypeSelect.value;

             // Jika bukan 'any', hapus global discount_value - nilai diskon ada di per-plan
             if (targetType !== 'any') {
                 const dvInput = document.getElementById('discount_value');
                 if (dvInput) dvInput.value = '';
             }

             if (targetType !== 'membership') {
                 targetMembershipsContainer.querySelectorAll('input.target-checkbox').forEach(e => e.checked = false);
             }
             if (targetType !== 'event') {
                 targetEventsContainer.querySelectorAll('input.target-checkbox').forEach(e => e.checked = false);
             }
        });

        // Enable/disable overrides based on checkbox
        document.querySelectorAll('input.target-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                const overrideInput = e.target.closest('label').parentElement.querySelector('.target-override');
                if (overrideInput) {
                    overrideInput.disabled = !e.target.checked;
                    if(!e.target.checked) overrideInput.value = '';
                }
            })
        });

        // Binders
        promoMethodRadios.forEach(radio => radio.addEventListener('change', updateMethodUI));
        rewardTypeRadios.forEach(radio => radio.addEventListener('change', updateRewardUI));
        discountMethodSelect.addEventListener('change', updateRewardUI);
        targetTypeSelect.addEventListener('change', updateTargetingUI);

        // Initial paint
        updateMethodUI();
        updateRewardUI();
    });
  </script>
@endsection
