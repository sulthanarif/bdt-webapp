<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Masuk Admin') - BDT Super App</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-900 font-sans">
  <div class="min-h-screen grid lg:grid-cols-2">
    <div class="hidden lg:flex flex-col justify-between bg-slate-900 text-white p-12 relative overflow-hidden">
      <div class="absolute -top-20 -right-10 h-64 w-64 rounded-full bg-teal-600/30 blur-3xl"></div>
      <div class="absolute bottom-10 left-10 h-48 w-48 rounded-full bg-emerald-500/20 blur-3xl"></div>

      <div class="relative z-10">
        <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-teal-200">
          BDT Super App
        </div>
        <h1 class="mt-6 text-4xl font-serif font-semibold leading-tight">
          Kendali penuh operasional, anggota, dan event.
        </h1>
        <p class="mt-4 text-slate-300 leading-relaxed max-w-md">
          Panel admin ini dirancang untuk memantau membership, tiket, transaksi, dan integrasi layanan
          dengan tampilan yang rapi serta aman.
        </p>
      </div>

      <div class="relative z-10 grid gap-4 text-sm text-slate-200">
        <div class="flex items-start gap-3">
          <span class="mt-1 h-2 w-2 rounded-full bg-teal-400"></span>
          Dashboard real-time untuk status transaksi, event, dan tiket.
        </div>
        <div class="flex items-start gap-3">
          <span class="mt-1 h-2 w-2 rounded-full bg-teal-400"></span>
          Monitoring koneksi SLiMS dan sinkronisasi pembayaran.
        </div>
        <div class="flex items-start gap-3">
          <span class="mt-1 h-2 w-2 rounded-full bg-teal-400"></span>
          Akses cepat ke CMS, membership, dan laporan harian.
        </div>
      </div>
    </div>

    <div class="flex flex-col justify-center px-6 py-12 sm:px-16 bg-slate-100">
      <div class="mb-10">
        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">BDT Admin Access</p>
        <h2 class="text-3xl font-serif font-semibold text-slate-900">Masuk ke Dashboard</h2>
      </div>
      @yield('content')
    </div>
  </div>
</body>
</html>
