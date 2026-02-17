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
              <option value="{{ $type->id }}" data-is-student="{{ $type->is_student ? '1' : '0' }}" {{ (int) old('member_type_id') === $type->id ? 'selected' : '' }}>
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
          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">Status Transaksi</label>
            <select name="status" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
              <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
              <option value="paid" {{ old('status') === 'paid' ? 'selected' : '' }}>Paid</option>
            </select>
            @error('status')
              <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">Metode Pembayaran</label>
            <input type="text" name="payment_method" value="{{ old('payment_method') }}" placeholder="Cash / Transfer / E-wallet" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            @error('payment_method')
              <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">Referensi Pembayaran</label>
            <input type="text" name="payment_reference" value="{{ old('payment_reference') }}" placeholder="No. resi / catatan kasir" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            @error('payment_reference')
              <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">Tanggal Bayar</label>
            <input type="date" name="paid_at" value="{{ old('paid_at') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            @error('paid_at')
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
      };

      kindRadios.forEach((radio) => {
        radio.addEventListener('change', () => setKindVisibility(radio.value));
      });

      if (memberType) {
        memberType.addEventListener('change', () => {
          const option = memberType.selectedOptions[0];
          setStudentVisibility(option?.dataset?.isStudent === '1');
        });
      }

      const initialKind = kindRadios.find((radio) => radio.checked)?.value || 'daily';
      setKindVisibility(initialKind);
    });
  </script>
@endsection
