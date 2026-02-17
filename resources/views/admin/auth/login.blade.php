@extends('admin.layouts.auth')

@section('title', 'Masuk Admin')

@section('content')
  <div class="bg-white border border-slate-200 shadow-xl rounded-2xl p-8">
    <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-6">
      @csrf

      <div>
        <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
        <input
          id="email"
          name="email"
          type="email"
          autocomplete="email"
          value="{{ old('email') }}"
          required
          class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-teal-500 focus:ring-2 focus:ring-teal-200"
          placeholder="admin@bdt.id"
        >
        @error('email')
          <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Kata Sandi</label>
        <input
          id="password"
          name="password"
          type="password"
          autocomplete="current-password"
          required
          class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-teal-500 focus:ring-2 focus:ring-teal-200"
          placeholder="Masukkan kata sandi"
        >
      </div>

      <div class="flex items-center justify-between text-sm text-slate-600">
        <label class="inline-flex items-center gap-2">
          <input type="checkbox" name="remember" class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
          Ingat saya
        </label>
        <span class="text-slate-400">Hubungi super admin jika lupa</span>
      </div>

      <button type="submit" class="w-full rounded-xl bg-teal-700 text-white py-3 text-sm font-semibold shadow-lg hover:bg-teal-800 transition">
        Masuk Dashboard
      </button>
    </form>

    <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 p-4 text-xs text-slate-500">
      Pastikan Anda menggunakan akun admin yang sudah terdaftar di sistem.
    </div>
  </div>
@endsection
