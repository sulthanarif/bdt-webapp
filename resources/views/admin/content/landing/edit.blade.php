@extends('admin.layouts.app')

@section('title', 'Konten Landing Page')
@section('page_title', 'Konten Landing Page')

@section('content')
  @if (session('status'))
    <div class="mb-6 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
      {{ session('status') }}
    </div>
  @endif

  @if ($errors->any())
    <div class="mb-6 rounded-2xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm text-rose-700">
      <p class="font-semibold">Periksa kembali input Anda.</p>
      <ul class="mt-2 list-disc pl-5">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="rounded-2xl border border-slate-200 bg-white px-6 py-4 text-sm text-slate-600 shadow-sm">
    Semua kolom hanya menerima teks biasa. Tag HTML tidak diizinkan.
  </div>

  @php
    $form = old('content', $content);
    $navItems = $form['nav']['items'] ?? [];
    $facilityItems = $form['facilities']['items'] ?? [];
    $rentalItems = $form['facilities']['rental']['items'] ?? [];
    $agendaItems = $form['agenda']['items'] ?? [];
    $addressLines = $form['contact']['address_lines'] ?? [];
    $hours = $form['contact']['hours'] ?? [];
    $links = $form['links'] ?? [];

    $navCount = max(count($navItems), 6);
    $facilityCount = max(count($facilityItems), 6);
    $rentalCount = max(count($rentalItems), 4);
    $agendaCount = max(count($agendaItems), 2);
    $addressCount = max(count($addressLines), 3);
    $hoursCount = max(count($hours), 5);
  @endphp

  <form method="POST" action="{{ route('admin.content.landing.update') }}" class="space-y-10">
    @csrf
    @method('PUT')

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <h2 class="text-lg font-semibold text-slate-900">Meta SEO</h2>
      <p class="mt-1 text-sm text-slate-500">Judul dan deskripsi untuk browser.</p>
      <div class="mt-4 grid gap-4 md:grid-cols-2">
        <div>
          <label class="text-sm font-semibold text-slate-700">Judul</label>
          <input type="text" name="content[meta][title]" value="{{ $form['meta']['title'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
        <div>
          <label class="text-sm font-semibold text-slate-700">Deskripsi</label>
          <input type="text" name="content[meta][description]" value="{{ $form['meta']['description'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
      </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <h2 class="text-lg font-semibold text-slate-900">Navigasi</h2>
      <div class="mt-4 grid gap-4 lg:grid-cols-2">
        @for ($i = 0; $i < $navCount; $i++)
          <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Item {{ $i + 1 }}</label>
            <div class="mt-3 grid gap-3 md:grid-cols-2">
              <input type="text" name="content[nav][items][{{ $i }}][label]" value="{{ $navItems[$i]['label'] ?? '' }}" placeholder="Label" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
              <input type="text" name="content[nav][items][{{ $i }}][target]" value="{{ $navItems[$i]['target'] ?? '' }}" placeholder="Target (contoh: about)" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
            </div>
          </div>
        @endfor
      </div>
      <div class="mt-4 max-w-sm">
        <label class="text-sm font-semibold text-slate-700">Tombol CTA</label>
        <input type="text" name="content[nav][cta]" value="{{ $form['nav']['cta'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
      </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <h2 class="text-lg font-semibold text-slate-900">Hero</h2>
      <div class="mt-4 grid gap-4 md:grid-cols-2">
        <div>
          <label class="text-sm font-semibold text-slate-700">Badge</label>
          <input type="text" name="content[hero][badge]" value="{{ $form['hero']['badge'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
        <div>
          <label class="text-sm font-semibold text-slate-700">Judul (Awal)</label>
          <input type="text" name="content[hero][title_prefix]" value="{{ $form['hero']['title_prefix'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
        <div>
          <label class="text-sm font-semibold text-slate-700">Judul (Highlight)</label>
          <input type="text" name="content[hero][title_highlight]" value="{{ $form['hero']['title_highlight'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
        <div>
          <label class="text-sm font-semibold text-slate-700">Judul (Akhir)</label>
          <input type="text" name="content[hero][title_suffix]" value="{{ $form['hero']['title_suffix'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
      </div>
      <div class="mt-4 grid gap-4">
        <div>
          <label class="text-sm font-semibold text-slate-700">Deskripsi (Sebelum highlight)</label>
          <textarea name="content[hero][description_before]" rows="3" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">{{ $form['hero']['description_before'] ?? '' }}</textarea>
        </div>
        <div>
          <label class="text-sm font-semibold text-slate-700">Deskripsi (Highlight)</label>
          <input type="text" name="content[hero][description_highlight]" value="{{ $form['hero']['description_highlight'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
        <div>
          <label class="text-sm font-semibold text-slate-700">Deskripsi (Sesudah highlight)</label>
          <textarea name="content[hero][description_after]" rows="2" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">{{ $form['hero']['description_after'] ?? '' }}</textarea>
        </div>
      </div>
      <div class="mt-4 grid gap-4 md:grid-cols-2">
        <div>
          <label class="text-sm font-semibold text-slate-700">CTA Utama</label>
          <input type="text" name="content[hero][primary_cta]" value="{{ $form['hero']['primary_cta'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
        <div>
          <label class="text-sm font-semibold text-slate-700">CTA Sekunder</label>
          <input type="text" name="content[hero][secondary_cta]" value="{{ $form['hero']['secondary_cta'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
      </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <h2 class="text-lg font-semibold text-slate-900">Tentang Kami</h2>
      <div class="mt-4 grid gap-4">
        <div>
          <label class="text-sm font-semibold text-slate-700">Judul</label>
          <input type="text" name="content[about][title]" value="{{ $form['about']['title'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
        <div>
          <label class="text-sm font-semibold text-slate-700">Kutipan</label>
          <textarea name="content[about][quote]" rows="2" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">{{ $form['about']['quote'] ?? '' }}</textarea>
        </div>
        <div>
          <label class="text-sm font-semibold text-slate-700">Paragraf 1</label>
          <textarea name="content[about][paragraph_one]" rows="4" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">{{ $form['about']['paragraph_one'] ?? '' }}</textarea>
        </div>
        <div>
          <label class="text-sm font-semibold text-slate-700">Paragraf 2 (Sebelum highlight)</label>
          <textarea name="content[about][paragraph_two_before]" rows="4" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">{{ $form['about']['paragraph_two_before'] ?? '' }}</textarea>
        </div>
        <div>
          <label class="text-sm font-semibold text-slate-700">Paragraf 2 (Highlight)</label>
          <textarea name="content[about][paragraph_two_highlight]" rows="2" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">{{ $form['about']['paragraph_two_highlight'] ?? '' }}</textarea>
        </div>
      </div>
      <div class="mt-4 grid gap-4 md:grid-cols-2">
        <div>
          <label class="text-sm font-semibold text-slate-700">Gambar</label>
          <input type="text" name="content[about][image]" value="{{ $form['about']['image'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
        <div>
          <label class="text-sm font-semibold text-slate-700">Alt Gambar</label>
          <input type="text" name="content[about][image_alt]" value="{{ $form['about']['image_alt'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
      </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <h2 class="text-lg font-semibold text-slate-900">Fasilitas</h2>
      <div class="mt-4 grid gap-4">
        <div>
          <label class="text-sm font-semibold text-slate-700">Judul</label>
          <input type="text" name="content[facilities][title]" value="{{ $form['facilities']['title'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
        <div>
          <label class="text-sm font-semibold text-slate-700">Deskripsi</label>
          <textarea name="content[facilities][description]" rows="2" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">{{ $form['facilities']['description'] ?? '' }}</textarea>
        </div>
      </div>
      <div class="mt-6 grid gap-4 lg:grid-cols-2">
        @for ($i = 0; $i < $facilityCount; $i++)
          @php $item = $facilityItems[$i] ?? []; @endphp
          <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 space-y-3">
            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Fasilitas {{ $i + 1 }}</label>
            <input type="text" name="content[facilities][items][{{ $i }}][title]" value="{{ $item['title'] ?? '' }}" placeholder="Judul" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
            <textarea name="content[facilities][items][{{ $i }}][description]" rows="2" placeholder="Deskripsi" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">{{ $item['description'] ?? '' }}</textarea>
            <div class="grid gap-3 md:grid-cols-2">
              <input type="text" name="content[facilities][items][{{ $i }}][image]" value="{{ $item['image'] ?? '' }}" placeholder="Path gambar" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
              <input type="text" name="content[facilities][items][{{ $i }}][alt]" value="{{ $item['alt'] ?? '' }}" placeholder="Alt text" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
            </div>
            <div class="grid gap-3 md:grid-cols-2">
              <input type="text" name="content[facilities][items][{{ $i }}][icon]" value="{{ $item['icon'] ?? '' }}" placeholder="Ikon (lucide)" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
              <input type="text" name="content[facilities][items][{{ $i }}][badges][0]" value="{{ $item['badges'][0] ?? '' }}" placeholder="Badge 1" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
            </div>
            <div class="grid gap-3 md:grid-cols-2">
              <input type="text" name="content[facilities][items][{{ $i }}][badges][1]" value="{{ $item['badges'][1] ?? '' }}" placeholder="Badge 2" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
              <input type="text" name="content[facilities][items][{{ $i }}][badges][2]" value="{{ $item['badges'][2] ?? '' }}" placeholder="Badge 3" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
            </div>
          </div>
        @endfor
      </div>

      <div class="mt-8 grid gap-4">
        <div>
          <label class="text-sm font-semibold text-slate-700">Judul Ketentuan Sewa</label>
          <input type="text" name="content[facilities][rental][title]" value="{{ $form['facilities']['rental']['title'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
        <div class="grid gap-3 md:grid-cols-2">
          @for ($i = 0; $i < $rentalCount; $i++)
            <input type="text" name="content[facilities][rental][items][{{ $i }}]" value="{{ $rentalItems[$i] ?? '' }}" placeholder="Ketentuan {{ $i + 1 }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
          @endfor
        </div>
        <div class="max-w-sm">
          <label class="text-sm font-semibold text-slate-700">Label Tombol Sewa</label>
          <input type="text" name="content[facilities][rental][cta_label]" value="{{ $form['facilities']['rental']['cta_label'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
      </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <h2 class="text-lg font-semibold text-slate-900">Pendaftaran</h2>
      <div class="mt-4 grid gap-4">
        <div>
          <label class="text-sm font-semibold text-slate-700">Judul</label>
          <input type="text" name="content[pricing][title]" value="{{ $form['pricing']['title'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
        <div>
          <label class="text-sm font-semibold text-slate-700">Deskripsi</label>
          <textarea name="content[pricing][description]" rows="2" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">{{ $form['pricing']['description'] ?? '' }}</textarea>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
          <div>
            <label class="text-sm font-semibold text-slate-700">Judul FAQ</label>
            <input type="text" name="content[pricing][faq_title]" value="{{ $form['pricing']['faq_title'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
          </div>
          <div>
            <label class="text-sm font-semibold text-slate-700">Deskripsi FAQ</label>
            <input type="text" name="content[pricing][faq_description]" value="{{ $form['pricing']['faq_description'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
          </div>
        </div>
      </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <h2 class="text-lg font-semibold text-slate-900">Agenda</h2>
      <div class="mt-4 grid gap-4">
        <div>
          <label class="text-sm font-semibold text-slate-700">Judul</label>
          <input type="text" name="content[agenda][title]" value="{{ $form['agenda']['title'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
        <div>
          <label class="text-sm font-semibold text-slate-700">Deskripsi</label>
          <textarea name="content[agenda][description]" rows="2" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">{{ $form['agenda']['description'] ?? '' }}</textarea>
        </div>
      </div>
      <div class="mt-6 grid gap-4 lg:grid-cols-2">
        @for ($i = 0; $i < $agendaCount; $i++)
          @php $item = $agendaItems[$i] ?? []; @endphp
          <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 space-y-3">
            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Agenda {{ $i + 1 }}</label>
            <div class="flex items-center gap-2 text-sm">
              <input type="hidden" name="content[agenda][items][{{ $i }}][is_active]" value="0">
              <input type="checkbox" name="content[agenda][items][{{ $i }}][is_active]" value="1" {{ !empty($item['is_active']) ? 'checked' : '' }}>
              <span>Aktifkan event</span>
            </div>
            <input type="text" name="content[agenda][items][{{ $i }}][status_label]" value="{{ $item['status_label'] ?? '' }}" placeholder="Label status" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
            <input type="text" name="content[agenda][items][{{ $i }}][category]" value="{{ $item['category'] ?? '' }}" placeholder="Kategori" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
            <input type="text" name="content[agenda][items][{{ $i }}][title]" value="{{ $item['title'] ?? '' }}" placeholder="Judul" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
            <input type="text" name="content[agenda][items][{{ $i }}][subtitle]" value="{{ $item['subtitle'] ?? '' }}" placeholder="Subjudul" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
            <input type="text" name="content[agenda][items][{{ $i }}][date]" value="{{ $item['date'] ?? '' }}" placeholder="Tanggal & jam" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
            <input type="text" name="content[agenda][items][{{ $i }}][location]" value="{{ $item['location'] ?? '' }}" placeholder="Lokasi" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
            <textarea name="content[agenda][items][{{ $i }}][description]" rows="3" placeholder="Deskripsi" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">{{ $item['description'] ?? '' }}</textarea>
            <div class="grid gap-3 md:grid-cols-2">
              <input type="text" name="content[agenda][items][{{ $i }}][cta_label]" value="{{ $item['cta_label'] ?? '' }}" placeholder="Label tombol" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
              <input type="text" name="content[agenda][items][{{ $i }}][cta_url]" value="{{ $item['cta_url'] ?? '' }}" placeholder="URL tombol" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
            </div>
            <div class="grid gap-3 md:grid-cols-2">
              <input type="text" name="content[agenda][items][{{ $i }}][image]" value="{{ $item['image'] ?? '' }}" placeholder="Path gambar" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
              <input type="text" name="content[agenda][items][{{ $i }}][image_alt]" value="{{ $item['image_alt'] ?? '' }}" placeholder="Alt gambar" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
            </div>
            <input type="text" name="content[agenda][items][{{ $i }}][theme]" value="{{ $item['theme'] ?? '' }}" placeholder="Tema warna (teal/slate)" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
          </div>
        @endfor
      </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <h2 class="text-lg font-semibold text-slate-900">Blog</h2>
      <div class="mt-4 grid gap-4">
        <div>
          <label class="text-sm font-semibold text-slate-700">Judul</label>
          <input type="text" name="content[blog][title]" value="{{ $form['blog']['title'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
        <div>
          <label class="text-sm font-semibold text-slate-700">Deskripsi</label>
          <input type="text" name="content[blog][description]" value="{{ $form['blog']['description'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
        <div class="max-w-sm">
          <label class="text-sm font-semibold text-slate-700">Label CTA Blog</label>
          <input type="text" name="content[blog][cta_label]" value="{{ $form['blog']['cta_label'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
      </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <h2 class="text-lg font-semibold text-slate-900">Kontak</h2>
      <div class="mt-4 grid gap-4">
        <div>
          <label class="text-sm font-semibold text-slate-700">Judul</label>
          <input type="text" name="content[contact][title]" value="{{ $form['contact']['title'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
        <div>
          <label class="text-sm font-semibold text-slate-700">Judul Alamat</label>
          <input type="text" name="content[contact][address_title]" value="{{ $form['contact']['address_title'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
        <div class="grid gap-3 md:grid-cols-2">
          @for ($i = 0; $i < $addressCount; $i++)
            <input type="text" name="content[contact][address_lines][{{ $i }}]" value="{{ $addressLines[$i] ?? '' }}" placeholder="Baris alamat {{ $i + 1 }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
          @endfor
        </div>
        <div class="grid gap-4 md:grid-cols-2">
          <div>
            <label class="text-sm font-semibold text-slate-700">Judul Email</label>
            <input type="text" name="content[contact][email_title]" value="{{ $form['contact']['email_title'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
          </div>
          <div>
            <label class="text-sm font-semibold text-slate-700">Alamat Email</label>
            <input type="text" name="content[contact][email]" value="{{ $form['contact']['email'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
          </div>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
          <div>
            <label class="text-sm font-semibold text-slate-700">Judul Instagram</label>
            <input type="text" name="content[contact][instagram_title]" value="{{ $form['contact']['instagram_title'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
          </div>
          <div>
            <label class="text-sm font-semibold text-slate-700">Handle Instagram</label>
            <input type="text" name="content[contact][instagram_handle]" value="{{ $form['contact']['instagram_handle'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
          </div>
        </div>
        <div>
          <label class="text-sm font-semibold text-slate-700">Judul Jam Operasional</label>
          <input type="text" name="content[contact][hours_title]" value="{{ $form['contact']['hours_title'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
        <div class="grid gap-3 md:grid-cols-2">
          @for ($i = 0; $i < $hoursCount; $i++)
            <div class="grid grid-cols-2 gap-2">
              <input type="text" name="content[contact][hours][{{ $i }}][day]" value="{{ $hours[$i]['day'] ?? '' }}" placeholder="Hari" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
              <input type="text" name="content[contact][hours][{{ $i }}][time]" value="{{ $hours[$i]['time'] ?? '' }}" placeholder="Jam" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
            </div>
          @endfor
        </div>
        <div>
          <label class="text-sm font-semibold text-slate-700">Embed Maps</label>
          <textarea name="content[contact][map_embed]" rows="2" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">{{ $form['contact']['map_embed'] ?? '' }}</textarea>
        </div>
      </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <h2 class="text-lg font-semibold text-slate-900">Footer</h2>
      <div class="mt-4 grid gap-4 md:grid-cols-3">
        <div>
          <label class="text-sm font-semibold text-slate-700">Label Privasi</label>
          <input type="text" name="content[footer][privacy_label]" value="{{ $form['footer']['privacy_label'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
        <div>
          <label class="text-sm font-semibold text-slate-700">Label Ketentuan</label>
          <input type="text" name="content[footer][terms_label]" value="{{ $form['footer']['terms_label'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
        <div>
          <label class="text-sm font-semibold text-slate-700">Label FAQ</label>
          <input type="text" name="content[footer][faq_label]" value="{{ $form['footer']['faq_label'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
      </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <h2 class="text-lg font-semibold text-slate-900">Link & Sosial Media</h2>
      <p class="mt-1 text-sm text-slate-500">URL akan dipakai untuk tombol WhatsApp, email, dan sosial media.</p>
      <div class="mt-4 grid gap-4 md:grid-cols-2">
        @foreach ($links as $key => $value)
          <div>
            <label class="text-sm font-semibold text-slate-700">{{ $key }}</label>
            <input type="text" name="content[links][{{ $key }}]" value="{{ $value }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
          </div>
        @endforeach
      </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <h2 class="text-lg font-semibold text-slate-900">Halaman FAQ</h2>
      <div class="mt-4 grid gap-4 md:grid-cols-2">
        <div>
          <label class="text-sm font-semibold text-slate-700">Judul</label>
          <input type="text" name="content[faq_page][title]" value="{{ $form['faq_page']['title'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
        <div>
          <label class="text-sm font-semibold text-slate-700">Deskripsi</label>
          <input type="text" name="content[faq_page][description]" value="{{ $form['faq_page']['description'] ?? '' }}" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
        </div>
      </div>
    </div>

    <div class="flex items-center justify-end gap-3">
      <button type="submit" class="rounded-xl bg-teal-700 px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-teal-800">
        Simpan Perubahan
      </button>
    </div>
  </form>
@endsection

