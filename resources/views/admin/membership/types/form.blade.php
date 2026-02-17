@extends('admin.layouts.app')

@section('title', $mode === 'edit' ? 'Edit Membership Type' : 'Tambah Membership Type')
@section('page_title', $mode === 'edit' ? 'Edit Membership Type' : 'Tambah Membership Type')

@section('content')
  <div class="max-w-4xl space-y-6">
    <div>
      <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Membership</p>
      <h2 class="text-2xl font-semibold text-slate-900">
        {{ $mode === 'edit' ? 'Edit Paket Membership' : 'Tambah Paket Membership' }}
      </h2>
      <p class="text-sm text-slate-500">Atur detail paket agar tampil konsisten di landing page.</p>
    </div>

    @if ($errors->any())
      <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
        Lengkapi data yang masih kurang.
      </div>
    @endif

    <form method="POST" action="{{ $mode === 'edit' ? route('admin.membership.types.update', $memberType) : route('admin.membership.types.store') }}" class="space-y-6">
      @csrf
      @if ($mode === 'edit')
        @method('PUT')
      @endif

      <div class="grid gap-6 md:grid-cols-2">
        <div class="space-y-2">
          <label for="name" class="text-sm font-semibold text-slate-700">Nama Paket</label>
          <input id="name" name="name" type="text" value="{{ old('name', $memberType->name) }}" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('name')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label for="label" class="text-sm font-semibold text-slate-700">Label Badge</label>
          <input id="label" name="label" type="text" value="{{ old('label', $memberType->label) }}" placeholder="PALING HEMAT / FAVORIT" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('label')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label for="pricing" class="text-sm font-semibold text-slate-700">Harga (Rupiah)</label>
          <input id="pricing" name="pricing" type="number" min="0" value="{{ old('pricing', $memberType->pricing) }}" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('pricing')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label for="duration_days" class="text-sm font-semibold text-slate-700">Durasi (Hari)</label>
          <input id="duration_days" name="duration_days" type="number" min="1" value="{{ old('duration_days', $memberType->duration_days) }}" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('duration_days')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label for="order" class="text-sm font-semibold text-slate-700">Urutan Tampil</label>
          <input id="order" name="order" type="number" min="0" value="{{ old('order', $memberType->order) }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('order')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label for="accent_color" class="text-sm font-semibold text-slate-700">Warna Aksen</label>
          <div class="flex items-center gap-3">
            <input id="accent_color" name="accent_color" type="color" value="{{ old('accent_color', $memberType->accent_color ?? '#14b8a6') }}" class="h-11 w-14 rounded-xl border border-slate-200 bg-white p-1">
            <span class="text-xs text-slate-500">Gunakan warna ini untuk membedakan paket.</span>
          </div>
          @error('accent_color')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <div class="flex flex-wrap gap-6">
        <label class="inline-flex items-center gap-2 text-sm text-slate-600">
          <input type="checkbox" name="is_full_color" value="1" {{ old('is_full_color', $memberType->is_full_color) ? 'checked' : '' }} class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
          Kartu full color (pakai warna aksen)
        </label>
        <label class="inline-flex items-center gap-2 text-sm text-slate-600">
          <input type="checkbox" name="is_daily" value="1" {{ old('is_daily', $memberType->is_daily) ? 'checked' : '' }} class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
          Paket kunjungan harian
        </label>
        <label class="inline-flex items-center gap-2 text-sm text-slate-600">
          <input type="checkbox" name="is_student" value="1" {{ old('is_student', $memberType->is_student) ? 'checked' : '' }} class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
          Paket khusus pelajar/mahasiswa
        </label>
        <label class="inline-flex items-center gap-2 text-sm text-slate-600">
          <input type="checkbox" name="is_active" value="1" {{ old('is_active', $memberType->exists ? $memberType->is_active : true) ? 'checked' : '' }} class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
          Aktifkan paket
        </label>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Benefit</p>
            <h3 class="text-lg font-semibold text-slate-900">Daftar Benefit</h3>
          </div>
          <span class="text-xs text-slate-400">Centang untuk benefit aktif</span>
        </div>

        @php
          $benefitInputs = old('benefits');
          if (! is_array($benefitInputs)) {
              $benefitInputs = $benefits->map(function ($benefit) {
                  return [
                      'label' => $benefit->label,
                      'is_included' => $benefit->is_included,
                  ];
              })->toArray();
          }
          $rows = max(3, count($benefitInputs) + 1);
        @endphp

        <div class="mt-6 space-y-3" data-benefits-wrapper data-benefits-index="{{ $rows }}">
          @for ($i = 0; $i < $rows; $i++)
            @php
              $benefit = $benefitInputs[$i] ?? null;
              $label = is_array($benefit) ? ($benefit['label'] ?? '') : '';
              $included = is_array($benefit) ? ($benefit['is_included'] ?? true) : true;
            @endphp
            <div class="flex flex-wrap items-center gap-3">
              <input type="text" name="benefits[{{ $i }}][label]" value="{{ $label }}" placeholder="Benefit {{ $i + 1 }}" class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
              <label class="inline-flex items-center gap-2 text-xs text-slate-600">
                <input type="checkbox" name="benefits[{{ $i }}][is_included]" value="1" {{ $included ? 'checked' : '' }} class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                Aktif
              </label>
            </div>
          @endfor
        </div>

        <div class="mt-4">
          <button type="button" data-add-benefit class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
            Tambah Benefit
          </button>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <button type="submit" class="rounded-xl bg-teal-700 px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-teal-800">
          {{ $mode === 'edit' ? 'Simpan Perubahan' : 'Simpan Paket' }}
        </button>
        <a href="{{ route('admin.membership.types.index') }}" class="rounded-xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">
          Batal
        </a>
      </div>
    </form>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const wrapper = document.querySelector('[data-benefits-wrapper]');
      const addButton = document.querySelector('[data-add-benefit]');
      if (!wrapper || !addButton) {
        return;
      }

      const buildRow = (index) => {
        const row = document.createElement('div');
        row.className = 'flex flex-wrap items-center gap-3';
        row.innerHTML = `
          <input type="text" name="benefits[${index}][label]" placeholder="Benefit ${index + 1}" class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          <label class="inline-flex items-center gap-2 text-xs text-slate-600">
            <input type="checkbox" name="benefits[${index}][is_included]" value="1" checked class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
            Aktif
          </label>
        `;
        return row;
      };

      let index = Number(wrapper.dataset.benefitsIndex) || wrapper.children.length;
      addButton.addEventListener('click', () => {
        wrapper.appendChild(buildRow(index));
        index += 1;
        wrapper.dataset.benefitsIndex = String(index);
      });
    });
  </script>
@endsection
