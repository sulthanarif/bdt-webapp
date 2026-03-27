@extends('layouts.public')

@section('title', 'Pendaftaran Event - Baca Di Tebet')

@section('content')
  <section class="bg-[#F9F7F2] py-16">
    <div class="max-w-3xl mx-auto px-6">
      <a href="{{ route('landing') }}" class="text-sm font-semibold text-teal-700 hover:underline">Kembali ke beranda</a>

      <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h1 class="font-serif text-2xl md:text-3xl font-bold text-slate-900">Pendaftaran Event</h1>
        <p class="mt-2 text-slate-600">{{ $event->title }}</p>
        @if ($event->subtitle)
          <p class="text-sm text-slate-500">{{ $event->subtitle }}</p>
        @endif
        @if ($event->price && $event->price > 0)
          <p class="mt-3 text-sm font-semibold text-teal-700">Harga: Rp {{ number_format($event->price, 0, ',', '.') }}</p>
        @else
          <p class="mt-3 text-sm font-semibold text-slate-500">Gratis</p>
        @endif
      </div>

      @if (session('status'))
        <div class="mt-6 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
          {{ session('status') }}
        </div>
      @endif

      <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('events.register.store', $event) }}" class="space-y-6" data-event-form>
          @csrf

          <div class="grid gap-6 md:grid-cols-2">
            <div class="space-y-2">
              <label class="text-sm font-semibold text-slate-700">Nama Lengkap</label>
              <input name="name" type="text" value="{{ old('name') }}" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
              @error('name')
                <p class="text-xs text-rose-600">{{ $message }}</p>
              @enderror
            </div>
            <div class="space-y-2">
              <label class="text-sm font-semibold text-slate-700">Email</label>
              <input name="email" type="email" value="{{ old('email') }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
              @error('email')
                <p class="text-xs text-rose-600">{{ $message }}</p>
              @enderror
            </div>
            <div class="space-y-2">
              <label class="text-sm font-semibold text-slate-700">No. Kontak</label>
              <input name="phone" type="text" value="{{ old('phone') }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
              @error('phone')
                <p class="text-xs text-rose-600">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <div class="space-y-3">
            <p class="text-sm font-semibold text-slate-700">Apakah kamu member BDT?</p>
            <div class="flex flex-wrap gap-4">
              <label class="inline-flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700">
                <input type="radio" name="is_member" value="1" {{ old('is_member') == '1' ? 'checked' : '' }} class="h-4 w-4 text-teal-600">
                Ya
              </label>
              <label class="inline-flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700">
                <input type="radio" name="is_member" value="0" {{ old('is_member', '0') == '0' ? 'checked' : '' }} class="h-4 w-4 text-teal-600">
                Tidak
              </label>
            </div>
            @error('is_member')
              <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          <div data-member-section class="space-y-2 hidden">
            <label class="text-sm font-semibold text-slate-700">ID Member / Email / No. HP Member</label>
            <input name="member_identifier" type="text" value="{{ old('member_identifier') }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            @error('member_identifier')
              <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          @php
            $defaultFields = [
              ['key' => 'usia', 'label' => 'Usia', 'type' => 'text', 'required' => true, 'options' => []],
              ['key' => 'pekerjaan', 'label' => 'Pekerjaan', 'type' => 'text', 'required' => true, 'options' => []],
              ['key' => 'domisili', 'label' => 'Domisili', 'type' => 'text', 'required' => true, 'options' => []],
            ];
            $customFields = is_array($event->registration_fields ?? null) ? $event->registration_fields : [];
            $customFields = array_values(array_filter($customFields, function ($field) {
              $key = $field['key'] ?? '';
              return ! in_array($key, ['usia', 'pekerjaan', 'domisili'], true);
            }));
          @endphp

          <div class="space-y-4">
            @foreach ($defaultFields as $field)
              @php
                $fieldKey = $field['key'];
                $fieldLabel = $field['label'];
                $fieldType = $field['type'];
                $fieldRequired = true;
                $fieldOptions = $field['options'] ?? [];
                $fieldName = "custom[$fieldKey]";
              @endphp

              <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700">
                  {{ $fieldLabel }}<span class="text-rose-600">*</span>
                </label>

                @if (in_array($fieldType, ['select', 'radio', 'checkbox'], true) && $fieldOptions)
                  @if ($fieldType === 'select')
                    <select name="{{ $fieldName }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200" required>
                      <option value="">Pilih salah satu</option>
                      @foreach ($fieldOptions as $option)
                        <option value="{{ $option }}" {{ old("custom.$fieldKey") == $option ? 'selected' : '' }}>
                          {{ $option }}
                        </option>
                      @endforeach
                    </select>
                  @elseif ($fieldType === 'radio')
                    <div class="flex flex-wrap gap-4">
                      @foreach ($fieldOptions as $option)
                        <label class="inline-flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700">
                          <input type="radio" name="{{ $fieldName }}" value="{{ $option }}" {{ old("custom.$fieldKey") == $option ? 'checked' : '' }} class="h-4 w-4 text-teal-600" required>
                          {{ $option }}
                        </label>
                      @endforeach
                    </div>
                  @else
                    @php
                      $oldValues = old("custom.$fieldKey", []);
                      $oldValues = is_array($oldValues) ? $oldValues : [];
                    @endphp
                    <div class="flex flex-wrap gap-4">
                      @foreach ($fieldOptions as $option)
                        <label class="inline-flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700">
                          <input type="checkbox" name="custom[{{ $fieldKey }}][]" value="{{ $option }}" {{ in_array($option, $oldValues, true) ? 'checked' : '' }} class="h-4 w-4 text-teal-600">
                          {{ $option }}
                        </label>
                      @endforeach
                    </div>
                  @endif
                @elseif ($fieldType === 'textarea')
                  <textarea name="{{ $fieldName }}" rows="4" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm leading-relaxed focus:border-teal-500 focus:ring-2 focus:ring-teal-200" required>{{ old("custom.$fieldKey") }}</textarea>
                @else
                  <input name="{{ $fieldName }}" type="{{ $fieldType === 'number' ? 'number' : ($fieldType === 'email' ? 'email' : ($fieldType === 'tel' ? 'tel' : 'text')) }}" value="{{ old("custom.$fieldKey") }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200" required>
                @endif

                @error("custom.$fieldKey")
                  <p class="text-xs text-rose-600">{{ $message }}</p>
                @enderror
              </div>
            @endforeach
          </div>

          @if ($customFields)
            <div class="space-y-4">
              <h3 class="text-sm font-semibold text-slate-700">Pertanyaan Tambahan</h3>
              @foreach ($customFields as $field)
                @php
                  $fieldKey = $field['key'] ?? 'field_' . $loop->index;
                  $fieldLabel = $field['label'] ?? 'Pertanyaan';
                  $fieldType = $field['type'] ?? 'text';
                  $fieldRequired = (bool) ($field['required'] ?? false);
                  $fieldOptions = is_array($field['options'] ?? null) ? $field['options'] : [];
                  $fieldName = "custom[$fieldKey]";
                @endphp

                <div class="space-y-2">
                  <label class="text-sm font-semibold text-slate-700">
                    {{ $fieldLabel }}@if ($fieldRequired)<span class="text-rose-600">*</span>@endif
                  </label>

                  @if (in_array($fieldType, ['select', 'radio', 'checkbox'], true) && $fieldOptions)
                    @if ($fieldType === 'select')
                      <select name="{{ $fieldName }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200" @if ($fieldRequired) required @endif>
                        <option value="">Pilih salah satu</option>
                        @foreach ($fieldOptions as $option)
                          <option value="{{ $option }}" {{ old("custom.$fieldKey") == $option ? 'selected' : '' }}>
                            {{ $option }}
                          </option>
                        @endforeach
                      </select>
                    @elseif ($fieldType === 'radio')
                      <div class="flex flex-wrap gap-4">
                        @foreach ($fieldOptions as $option)
                          <label class="inline-flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700">
                            <input type="radio" name="{{ $fieldName }}" value="{{ $option }}" {{ old("custom.$fieldKey") == $option ? 'checked' : '' }} class="h-4 w-4 text-teal-600" @if ($fieldRequired) required @endif>
                            {{ $option }}
                          </label>
                        @endforeach
                      </div>
                    @else
                      @php
                        $oldValues = old("custom.$fieldKey", []);
                        $oldValues = is_array($oldValues) ? $oldValues : [];
                      @endphp
                      <div class="flex flex-wrap gap-4">
                        @foreach ($fieldOptions as $option)
                          <label class="inline-flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700">
                            <input type="checkbox" name="custom[{{ $fieldKey }}][]" value="{{ $option }}" {{ in_array($option, $oldValues, true) ? 'checked' : '' }} class="h-4 w-4 text-teal-600">
                            {{ $option }}
                          </label>
                        @endforeach
                      </div>
                    @endif
                  @elseif ($fieldType === 'textarea')
                    <textarea name="{{ $fieldName }}" rows="4" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm leading-relaxed focus:border-teal-500 focus:ring-2 focus:ring-teal-200" @if ($fieldRequired) required @endif>{{ old("custom.$fieldKey") }}</textarea>
                  @else
                    <input name="{{ $fieldName }}" type="{{ $fieldType === 'number' ? 'number' : ($fieldType === 'email' ? 'email' : ($fieldType === 'tel' ? 'tel' : 'text')) }}" value="{{ old("custom.$fieldKey") }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200" @if ($fieldRequired) required @endif>
                  @endif

                  @error("custom.$fieldKey")
                    <p class="text-xs text-rose-600">{{ $message }}</p>
                  @enderror
                </div>
              @endforeach
            </div>
          @endif

          <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">Kode Voucher <span class="text-slate-400 font-normal">(Opsional)</span></label>
            <input type="text" name="promo_code" value="{{ old('promo_code') }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm uppercase font-mono focus:border-teal-500 focus:ring-2 focus:ring-teal-200" placeholder="Ketik kode jika ada">
            @error('promo_code')
              <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
          </div>

          <div class="flex flex-wrap gap-3">
            <button type="submit" class="rounded-xl bg-teal-700 px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-teal-800">Kirim Pendaftaran</button>
          </div>
        </form>
      </div>
    </div>
  </section>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.querySelector('[data-event-form]');
      if (!form) return;
      const memberSection = document.querySelector('[data-member-section]');
      const radios = form.querySelectorAll('input[name="is_member"]');

      const toggleSections = () => {
        const value = form.querySelector('input[name="is_member"]:checked')?.value || '0';
        const isMember = value === '1';
        if (memberSection) memberSection.classList.toggle('hidden', !isMember);
      };

      radios.forEach((radio) => {
        radio.addEventListener('change', toggleSections);
      });

      toggleSections();
    });
  </script>
@endsection
