@extends('admin.layouts.app')

@section('title', $mode === 'edit' ? 'Edit Event' : 'Tambah Event')
@section('page_title', $mode === 'edit' ? 'Edit Event' : 'Tambah Event')

@section('content')
  <div class="max-w-4xl space-y-6">
    <div>
      <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Agenda</p>
      <h2 class="text-2xl font-semibold text-slate-900">
        {{ $mode === 'edit' ? 'Edit Event' : 'Tambah Event' }}
      </h2>
      <p class="text-sm text-slate-500">Atur konten event sesuai tampilan agenda di landing page.</p>
    </div>

    @if ($errors->any())
      <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
        Lengkapi data yang masih kurang.
      </div>
    @endif

    @if (session('status'))
      <div class="rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        {{ session('status') }}
      </div>
    @endif

    <form method="POST" action="{{ $mode === 'edit' ? route('admin.events.update', $event) : route('admin.events.store') }}" enctype="multipart/form-data" class="space-y-6">
      @csrf
      @if ($mode === 'edit')
        @method('PUT')
      @endif

      <div class="grid gap-6 md:grid-cols-2">
        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">Judul Event</label>
          <input name="title" type="text" value="{{ old('title', $event->title) }}" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('title')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">Subjudul</label>
          <input name="subtitle" type="text" value="{{ old('subtitle', $event->subtitle) }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('subtitle')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">Label Kategori</label>
          <input name="category_label" type="text" value="{{ old('category_label', $event->category_label) }}" placeholder="BINCANG PUBLIK / BEDAH BUKU" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('category_label')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <input type="hidden" name="category_style" value="teal">

      </div>

      <div class="grid gap-6 md:grid-cols-2">
        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">Lokasi</label>
          <input name="location_label" type="text" value="{{ old('location_label', $event->location_label) }}" placeholder="Baca Di Tebet (Jl. Tebet Barat Dalam Raya No. 29)" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('location_label')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">Harga (Rp)</label>
          <input name="price" type="number" min="0" value="{{ old('price', $event->price) }}" placeholder="0 untuk gratis" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('price')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">Urutan</label>
          <input name="order" type="number" min="0" value="{{ old('order', $event->order) }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('order')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <div class="space-y-2">
        <label class="text-sm font-semibold text-slate-700">Deskripsi</label>
        <textarea name="description" rows="6" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm leading-relaxed focus:border-teal-500 focus:ring-2 focus:ring-teal-200">{{ old('description', $event->description) }}</textarea>
        @error('description')
          <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
      </div>

      <div class="grid gap-6 md:grid-cols-2">
        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">Upload Gambar</label>
          <input name="image_file" type="file" accept="image/*" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('image_file')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
          @if ($event->image_path)
            <p class="text-xs text-slate-500">Gambar saat ini: {{ $event->image_path }}</p>
          @endif
        </div>

        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">URL Gambar (opsional)</label>
          <input name="image_url" type="text" value="{{ old('image_url', $event->image_url) }}" placeholder="https://..." class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('image_url')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">Alt Text Gambar</label>
          <input name="image_alt" type="text" value="{{ old('image_alt', $event->image_alt) }}" placeholder="Poster Event" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('image_alt')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <div class="grid gap-6 md:grid-cols-2">
        <div class="space-y-2 md:col-span-2">
          <label class="text-sm font-semibold text-slate-700">URL Tombol (opsional)</label>
          <input name="cta_url" type="text" value="{{ old('cta_url', $event->cta_url) }}" placeholder="https://..." class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('cta_url')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
          <label class="mt-3 inline-flex items-center gap-3 text-sm font-semibold text-slate-700">
            <input type="checkbox" name="use_internal_registration" value="1" {{ old('use_internal_registration', $event->use_internal_registration) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-teal-600">
            Gunakan pendaftaran internal (form peserta)
          </label>
          <p class="text-xs text-slate-500">Jika aktif, tombol akan otomatis menuju form pendaftaran internal.</p>
        </div>
      </div>

      @php
        $registrationFieldsRaw = old('registration_fields');
        $registrationFields = [];
        if (is_string($registrationFieldsRaw) && $registrationFieldsRaw !== '') {
          $decodedFields = json_decode($registrationFieldsRaw, true);
          if (is_array($decodedFields)) {
            $registrationFields = $decodedFields;
          }
        } elseif (is_array($event->registration_fields ?? null)) {
          $registrationFields = $event->registration_fields;
        }
        $registrationFields = array_values(array_filter($registrationFields, function ($field) {
          $key = $field['key'] ?? '';
          return ! in_array($key, ['usia', 'pekerjaan', 'domisili'], true);
        }));
      @endphp

      <div class="rounded-2xl border border-slate-200 bg-white p-4 md:p-6" data-registration-fields data-fields='@json($registrationFields)'>
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div>
            <h3 class="text-sm font-semibold text-slate-800">Pertanyaan Tambahan</h3>
            <p class="text-xs text-slate-500">Opsional. Untuk kebutuhan khusus event tertentu.</p>
          </div>
          <button type="button" data-add-field class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">Tambah pertanyaan</button>
        </div>

        <div class="mt-4 space-y-4" data-field-list></div>
        <input type="hidden" name="registration_fields" value="{{ old('registration_fields') }}">
        @error('registration_fields')
          <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
        @enderror
      </div>

      <div class="grid gap-6 md:grid-cols-2">
        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">Mulai</label>
          <input name="starts_at" type="datetime-local" value="{{ old('starts_at', $event->starts_at?->format('Y-m-d\\TH:i')) }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('starts_at')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="space-y-2">
          <label class="text-sm font-semibold text-slate-700">Selesai</label>
          <input name="ends_at" type="datetime-local" value="{{ old('ends_at', $event->ends_at?->format('Y-m-d\\TH:i')) }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
          @error('ends_at')
            <p class="text-xs text-rose-600">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <div class="flex items-center gap-6">
        <label class="inline-flex items-center gap-3 text-sm font-semibold text-slate-700">
          <input type="checkbox" name="is_active" value="1" {{ old('is_active', $event->is_active ?? true) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-teal-600">
          Tampilkan di landing
        </label>
        <label class="inline-flex items-center gap-3 text-sm font-semibold text-slate-700">
          <input type="checkbox" name="is_finished" value="1" {{ old('is_finished', $event->is_finished) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-teal-600">
          Tandai sebagai selesai
        </label>
      </div>

      <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.events.index') }}" class="rounded-xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">Kembali</a>
        <button type="submit" class="rounded-xl bg-teal-700 px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-teal-800">Simpan</button>
      </div>
    </form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const wrapper = document.querySelector('[data-registration-fields]');
      if (!wrapper) return;

      const list = wrapper.querySelector('[data-field-list]');
      const addButton = wrapper.querySelector('[data-add-field]');
      const hiddenInput = wrapper.querySelector('input[name="registration_fields"]');
      const initial = JSON.parse(wrapper.dataset.fields || '[]');

      const fieldTypes = [
        { value: 'text', label: 'Teks' },
        { value: 'textarea', label: 'Paragraf' },
        { value: 'email', label: 'Email' },
        { value: 'tel', label: 'Telepon' },
        { value: 'number', label: 'Angka' },
        { value: 'select', label: 'Dropdown' },
        { value: 'radio', label: 'Pilihan tunggal' },
        { value: 'checkbox', label: 'Multi pilihan' },
      ];

      const slugify = (value) => {
        return value
          .toLowerCase()
          .replace(/[^a-z0-9]+/g, '_')
          .replace(/^_+|_+$/g, '');
      };

      const toggleOptions = (row) => {
        const type = row.querySelector('[data-field-type]')?.value || 'text';
        const options = row.querySelector('[data-field-options]');
        if (!options) return;
        options.classList.toggle('hidden', !['select', 'radio', 'checkbox'].includes(type));
      };

      const createRow = (field = {}) => {
        const row = document.createElement('div');
        row.className = 'rounded-xl border border-slate-200 bg-slate-50 px-4 py-4';
        row.innerHTML = `
          <div class="grid gap-4 md:grid-cols-12 items-end">
            <div class="md:col-span-5 space-y-2">
              <label class="text-xs font-semibold text-slate-600">Pertanyaan</label>
              <input type="text" value="${field.label || ''}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm" data-field-label>
            </div>
            <div class="md:col-span-3 space-y-2">
              <label class="text-xs font-semibold text-slate-600">Tipe</label>
              <select class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm" data-field-type>
                ${fieldTypes.map((type) => `<option value="${type.value}">${type.label}</option>`).join('')}
              </select>
            </div>
            <div class="md:col-span-2 space-y-2">
              <label class="text-xs font-semibold text-slate-600">Wajib</label>
              <label class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600">
                <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-teal-600" data-field-required>
                Ya
              </label>
            </div>
            <div class="md:col-span-2 text-right">
              <button type="button" class="text-xs font-semibold text-rose-600 hover:text-rose-700" data-remove-field>Hapus</button>
            </div>
            <div class="md:col-span-12 space-y-2 hidden" data-field-options>
              <label class="text-xs font-semibold text-slate-600">Opsi (pisahkan dengan koma)</label>
              <input type="text" value="${Array.isArray(field.options) ? field.options.join(', ') : ''}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm" placeholder="Contoh: <20 tahun, 20-30 tahun, 30-40 tahun">
            </div>
            <input type="hidden" value="${field.key || ''}" data-field-key>
          </div>
        `;

        list.appendChild(row);

        const typeSelect = row.querySelector('[data-field-type]');
        if (typeSelect && field.type) {
          typeSelect.value = field.type;
        }

        if (field.required) {
          const required = row.querySelector('[data-field-required]');
          if (required) required.checked = true;
        }

        toggleOptions(row);

        row.querySelector('[data-remove-field]')?.addEventListener('click', () => {
          row.remove();
          updateHidden();
        });

        row.querySelector('[data-field-type]')?.addEventListener('change', () => {
          toggleOptions(row);
          updateHidden();
        });

        row.querySelectorAll('input, select').forEach((input) => {
          input.addEventListener('input', updateHidden);
          input.addEventListener('change', updateHidden);
        });
      };

      const collectFields = () => {
        const rows = Array.from(list.querySelectorAll('[data-field-label]')).map((input) => input.closest('div'));
        const fields = [];
        const usedKeys = new Set();

        rows.forEach((row, index) => {
          const label = row.querySelector('[data-field-label]')?.value?.trim() || '';
          if (!label) return;
          const type = row.querySelector('[data-field-type]')?.value || 'text';
          const required = row.querySelector('[data-field-required]')?.checked || false;
          const optionsInput = row.querySelector('[data-field-options] input');
          let options = [];
          if (optionsInput && ['select', 'radio', 'checkbox'].includes(type)) {
            options = optionsInput.value.split(',').map((item) => item.trim()).filter(Boolean);
          }

          let key = row.querySelector('[data-field-key]')?.value?.trim() || '';
          if (!key) {
            key = slugify(label);
          }
          if (!key) {
            key = `field_${index + 1}`;
          }
          let uniqueKey = key;
          let counter = 2;
          while (usedKeys.has(uniqueKey)) {
            uniqueKey = `${key}_${counter}`;
            counter += 1;
          }
          usedKeys.add(uniqueKey);

          fields.push({
            key: uniqueKey,
            label,
            type,
            required,
            options,
          });
        });

        return fields;
      };

      const updateHidden = () => {
        if (!hiddenInput) return;
        hiddenInput.value = JSON.stringify(collectFields());
      };

      addButton?.addEventListener('click', () => {
        createRow();
        updateHidden();
      });

      if (Array.isArray(initial)) {
        initial.forEach((field) => createRow(field));
      }

      updateHidden();
    });
  </script>
@endsection
