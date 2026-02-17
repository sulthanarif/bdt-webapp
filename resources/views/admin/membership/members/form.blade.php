@extends('admin.layouts.app')

@section('title', $mode === 'edit' ? 'Edit Member' : 'Tambah Member')
@section('page_title', $mode === 'edit' ? 'Edit Member' : 'Tambah Member')

@section('content')
  <div class="max-w-4xl space-y-6">
    <div>
      <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Membership</p>
      <h2 class="text-2xl font-semibold text-slate-900">
        {{ $mode === 'edit' ? 'Edit Data Member' : 'Tambah Member Baru' }}
      </h2>
      <p class="text-sm text-slate-500">Lengkapi data member dan, jika perlu, buat transaksi manual.</p>
    </div>

    @if ($errors->any())
      <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
        Lengkapi data yang masih kurang.
      </div>
    @endif

    <form method="POST" action="{{ $mode === 'edit' ? route('admin.membership.members.update', $member) : route('admin.membership.members.store') }}" class="space-y-6">
      @csrf
      @if ($mode === 'edit')
        @method('PUT')
      @endif

      <div class="grid gap-6 md:grid-cols-2">
        <div class="space-y-2 md:col-span-2">
          <label class="text-sm font-semibold text-slate-700">Pilih Paket Membership</label>
          <select name="member_type_id" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            <option value="">Belum dipilih</option>
            @foreach ($memberTypes as $type)
              <option value="{{ $type->id }}" data-duration="{{ $type->duration_days }}" data-is-student="{{ $type->is_student ? '1' : '0' }}" {{ (int) old('member_type_id', $member->member_type_id) === $type->id ? 'selected' : '' }}>
                {{ $type->name }} - Rp {{ number_format($type->pricing, 0, ',', '.') }}
              </option>
            @endforeach
          </select>
          @error('member_type_id')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">Nama Lengkap</label>
          <input type="text" name="name" value="{{ old('name', $member->name) }}" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('name')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">Email</label>
          <input type="email" name="email" value="{{ old('email', $member->email) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('email')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">Nomor Kontak</label>
          <input type="text" name="phone" value="{{ old('phone', $member->phone) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('phone')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">Jenis Kelamin</label>
          <select name="gender" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            <option value="">Pilih</option>
            <option value="laki-laki" {{ old('gender', $member->gender) === 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
            <option value="perempuan" {{ old('gender', $member->gender) === 'perempuan' ? 'selected' : '' }}>Perempuan</option>
            <option value="lainnya" {{ old('gender', $member->gender) === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
          </select>
          @error('gender')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">NIK</label>
          <input type="text" name="nik" value="{{ old('nik', $member->nik) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('nik')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">Tanggal Lahir</label>
          <input type="date" name="birth_date" value="{{ old('birth_date', optional($member->birth_date)->format('Y-m-d')) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('birth_date')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2 md:col-span-2">
          <label class="text-sm font-semibold text-slate-700">Domisili</label>
          <input type="text" name="domicile" value="{{ old('domicile', $member->domicile) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('domicile')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">Status</label>
          <select name="status" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            @foreach (['active' => 'Aktif', 'pending' => 'Pending', 'banned' => 'Banned'] as $value => $label)
              <option value="{{ $value }}" {{ old('status', $member->status ?? 'active') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
          @error('status')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">Masa Berlaku</label>
          <input type="date" name="expired_at" value="{{ old('expired_at', optional($member->expired_at)->format('Y-m-d')) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('expired_at')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2" data-student-field>
          <label class="text-sm font-semibold text-slate-700">NIM (Pelajar)</label>
          <input type="text" name="nim" value="{{ old('nim', $member->nim) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('nim')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2 md:col-span-2" data-student-field>
          <label class="text-sm font-semibold text-slate-700">Kampus</label>
          <input type="text" name="university" value="{{ old('university', $member->university) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('university')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <label class="inline-flex items-center gap-2 text-sm text-slate-600" data-student-verify>
        <input type="checkbox" name="is_verified_student" value="1" {{ old('is_verified_student', $member->is_verified_student) ? 'checked' : '' }} class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
        Mahasiswa sudah diverifikasi
      </label>

      @if ($mode === 'create')
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Transaksi Manual</p>
              <h3 class="text-lg font-semibold text-slate-900">Buat transaksi offline</h3>
            </div>
            <label class="inline-flex items-center gap-2 text-sm text-slate-600">
              <input type="checkbox" name="create_transaction" value="1" class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
              Buat transaksi sekarang
            </label>
          </div>

          <div class="mt-6 grid gap-6 md:grid-cols-2">
            <div class="space-y-2">
              <label class="text-sm font-semibold text-slate-700">Status Transaksi</label>
              <select name="transaction_status" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                <option value="pending">Pending</option>
                <option value="paid">Paid</option>
              </select>
            </div>

            <div class="space-y-2">
              <label class="text-sm font-semibold text-slate-700">Metode Pembayaran</label>
              <input type="text" name="payment_method" placeholder="Cash / Transfer / E-wallet" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            </div>

            <div class="space-y-2">
              <label class="text-sm font-semibold text-slate-700">Referensi Pembayaran</label>
              <input type="text" name="payment_reference" placeholder="No. resi / catatan kasir" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            </div>

            <div class="space-y-2">
              <label class="text-sm font-semibold text-slate-700">Tanggal Bayar</label>
              <input type="date" name="paid_at" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            </div>
          </div>
        </div>
      @endif

      <div class="flex items-center gap-3">
        <button type="submit" class="rounded-xl bg-teal-700 px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-teal-800">
          {{ $mode === 'edit' ? 'Simpan Perubahan' : 'Simpan Member' }}
        </button>
        <a href="{{ route('admin.membership.members.index') }}" class="rounded-xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">
          Batal
        </a>
      </div>
    </form>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const typeSelect = document.querySelector('select[name="member_type_id"]');
      const expiredInput = document.querySelector('input[name="expired_at"]');
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
            } else if (!checkbox.checked) {
              checkbox.checked = true;
            }
          }
        }
      };

      const setExpiryFromType = () => {
        if (!typeSelect || !expiredInput) return;
        if (expiredInput.value) return;
        const option = typeSelect.selectedOptions[0];
        if (!option || !option.dataset.duration) return;
        const duration = Number(option.dataset.duration || 0);
        if (!duration) return;
        const today = new Date();
        today.setDate(today.getDate() + duration);
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        expiredInput.value = `${year}-${month}-${day}`;
      };

      const handleTypeChange = () => {
        if (!typeSelect) return;
        const option = typeSelect.selectedOptions[0];
        const isStudent = option?.dataset?.isStudent === '1';
        setStudentVisibility(isStudent);
        setExpiryFromType();
      };

      if (typeSelect) {
        typeSelect.addEventListener('change', handleTypeChange);
      }

      handleTypeChange();
    });
  </script>
@endsection
