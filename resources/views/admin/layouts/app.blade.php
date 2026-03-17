<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Dashboard Admin') - BDT Super App</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-900 font-sans">
  <div class="min-h-screen flex">
    <input type="checkbox" id="admin-nav-toggle" class="peer hidden">
    <div class="fixed inset-0 bg-slate-900/50 opacity-0 pointer-events-none transition peer-checked:opacity-100 peer-checked:pointer-events-auto lg:hidden"></div>

    <aside class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full bg-white border-r border-slate-200 shadow-lg transition duration-300 peer-checked:translate-x-0 lg:translate-x-0">
      <div class="flex items-center gap-3 px-6 py-6 border-b border-slate-200">
        <div class="h-11 w-11 rounded-2xl bg-teal-700 text-white flex items-center justify-center font-bold tracking-wide shadow-lg">BDT</div>
        <div>
          <p class="font-serif text-lg font-semibold text-slate-900">BDT Admin</p>
          <p class="text-xs text-slate-500">Super App Control</p>
        </div>
      </div>

      <nav class="px-4 py-6 space-y-1 text-sm">
        @php
          $isDashboard = request()->routeIs('admin.dashboard');
          $isContent = request()->routeIs('admin.content.*');
          $isMembership = request()->routeIs('admin.membership.*');
          $isCampaigns = request()->routeIs('admin.campaigns.*');
          $isEvents = request()->routeIs('admin.events.*');
          $isTransactions = request()->routeIs('admin.transactions.*');
        @endphp
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 font-semibold {{ $isDashboard ? 'bg-teal-50 text-teal-700' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="h-9 w-9 rounded-lg bg-teal-600/10 flex items-center justify-center">
            <svg class="h-4 w-4 text-teal-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
              <path d="M3 12l9-9 9 9"></path>
              <path d="M9 21V9h6v12"></path>
            </svg>
          </span>
          Dashboard
        </a>
        <a href="{{ route('admin.content.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 font-semibold {{ $isContent ? 'bg-teal-50 text-teal-700' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="h-9 w-9 rounded-lg {{ $isContent ? 'bg-teal-600/10' : 'bg-slate-100' }} flex items-center justify-center">
            <svg class="h-4 w-4 {{ $isContent ? 'text-teal-700' : 'text-slate-500' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
              <path d="M4 5h16M4 12h16M4 19h10"></path>
            </svg>
          </span>
          CMS Konten
        </a>
        <div class="rounded-xl border border-transparent px-2 py-2 {{ $isMembership ? 'bg-teal-50/60 border-teal-100' : '' }}" data-collapsible data-open="{{ $isMembership ? '1' : '0' }}">
          <button type="button" class="w-full flex items-center gap-3 px-2 py-2 font-semibold {{ $isMembership ? 'text-teal-700' : 'text-slate-600' }}" data-collapsible-trigger aria-expanded="{{ $isMembership ? 'true' : 'false' }}">
            <span class="h-9 w-9 rounded-lg bg-slate-100 flex items-center justify-center">
              <svg class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                <path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"></path>
                <path d="M8 2h8v4H8z"></path>
              </svg>
            </span>
            <span class="flex-1 text-left">Membership</span>
            <svg class="h-4 w-4 transition-transform {{ $isMembership ? 'rotate-180' : '' }}" data-collapsible-icon viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
              <path d="M6 9l6 6 6-6"></path>
            </svg>
          </button>
          <div class="ml-14 mt-1 space-y-1 text-sm {{ $isMembership ? '' : 'hidden' }}" data-collapsible-content>
            <a href="{{ route('admin.membership.types.index') }}" class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.membership.types.*') ? 'bg-teal-600 text-white' : 'text-slate-600 hover:bg-white' }}">
              Membership Types
            </a>
            <a href="{{ route('admin.membership.members.index') }}" class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.membership.members.*') ? 'bg-teal-600 text-white' : 'text-slate-600 hover:bg-white' }}">
              Members
            </a>
            <a href="{{ route('admin.membership.visits.index') }}" class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.membership.visits.index') ? 'bg-teal-600 text-white' : 'text-slate-600 hover:bg-white' }}">
              Kunjungan di Tempat
            </a>
            <a href="{{ route('admin.membership.visits.online') }}" class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.membership.visits.online') ? 'bg-teal-600 text-white' : 'text-slate-600 hover:bg-white' }}">
              Kunjungan Tiket Harian (Online)
            </a>
          </div>
        </div>
        <a href="{{ route('admin.campaigns.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 font-semibold {{ $isCampaigns ? 'bg-teal-50 text-teal-700' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="h-9 w-9 rounded-lg {{ $isCampaigns ? 'bg-teal-600/10' : 'bg-slate-100' }} flex items-center justify-center">
            <svg class="h-4 w-4 {{ $isCampaigns ? 'text-teal-700' : 'text-slate-500' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
              <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
              <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
              <line x1="12" y1="22.08" x2="12" y2="12"></line>
            </svg>
          </span>
          Campaign & Voucher
        </a>
        <a href="{{ route('admin.events.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 font-semibold {{ $isEvents ? 'bg-teal-50 text-teal-700' : 'text-slate-600 hover:bg-slate-50' }}">
          <span class="h-9 w-9 rounded-lg {{ $isEvents ? 'bg-teal-600/10' : 'bg-slate-100' }} flex items-center justify-center">
            <svg class="h-4 w-4 {{ $isEvents ? 'text-teal-700' : 'text-slate-500' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
              <circle cx="12" cy="12" r="9"></circle>
              <path d="M12 7v5l3 3"></path>
            </svg>
          </span>
          Event & Agenda
        </a>
        <div class="rounded-xl border border-transparent px-2 py-2 {{ $isTransactions ? 'bg-teal-50/60 border-teal-100' : '' }}" data-collapsible data-open="{{ $isTransactions ? '1' : '0' }}">
          <button type="button" class="w-full flex items-center gap-3 px-2 py-2 font-semibold {{ $isTransactions ? 'text-teal-700' : 'text-slate-600' }}" data-collapsible-trigger aria-expanded="{{ $isTransactions ? 'true' : 'false' }}">
            <span class="h-9 w-9 rounded-lg bg-slate-100 flex items-center justify-center">
              <svg class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                <path d="M8 10h8M8 14h6"></path>
              </svg>
            </span>
            <span class="flex-1 text-left">Transaksi</span>
            <svg class="h-4 w-4 transition-transform {{ $isTransactions ? 'rotate-180' : '' }}" data-collapsible-icon viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
              <path d="M6 9l6 6 6-6"></path>
            </svg>
          </button>
          <div class="ml-14 mt-1 space-y-1 text-sm {{ $isTransactions ? '' : 'hidden' }}" data-collapsible-content>
            <a href="{{ route('admin.transactions.index') }}" class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.transactions.index') ? 'bg-teal-600 text-white' : 'text-slate-600 hover:bg-white' }}">
              Semua Transaksi
            </a>
            <a href="{{ route('admin.transactions.memberships') }}" class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.transactions.memberships') ? 'bg-teal-600 text-white' : 'text-slate-600 hover:bg-white' }}">
              Membership
            </a>
            <a href="{{ route('admin.transactions.visits') }}" class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.transactions.visits') ? 'bg-teal-600 text-white' : 'text-slate-600 hover:bg-white' }}">
              Tiket Harian
            </a>
            <a href="{{ route('admin.transactions.events') }}" class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.transactions.events') ? 'bg-teal-600 text-white' : 'text-slate-600 hover:bg-white' }}">
              Event Registrasi
            </a>
          </div>
        </div>
        <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-slate-600 hover:bg-slate-50">
          <span class="h-9 w-9 rounded-lg bg-slate-100 flex items-center justify-center">
            <svg class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
              <path d="M3 3v18h18"></path>
              <path d="M7 14l4-4 4 3 5-6"></path>
            </svg>
          </span>
          Laporan & Export
        </a>
        <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-slate-600 hover:bg-slate-50">
          <span class="h-9 w-9 rounded-lg bg-slate-100 flex items-center justify-center">
            <svg class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
              <path d="M4 4h16v16H4z"></path>
              <path d="M8 8h8v8H8z"></path>
            </svg>
          </span>
          Borrow Check
        </a>
        <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-slate-600 hover:bg-slate-50">
          <span class="h-9 w-9 rounded-lg bg-slate-100 flex items-center justify-center">
            <svg class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
              <path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"></path>
            </svg>
          </span>
          System Config
        </a>
      </nav>

      <div class="mt-auto px-4 pb-6">
        <div class="rounded-2xl bg-slate-50 p-4 border border-slate-200">
          <p class="text-xs uppercase tracking-wide text-slate-500">Akun aktif</p>
          <p class="font-semibold text-slate-800">{{ auth()->user()->name ?? 'Admin' }}</p>
          <form method="POST" action="{{ route('admin.logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="w-full rounded-xl bg-slate-900 text-white px-3 py-2 text-xs font-semibold tracking-wide hover:bg-slate-800">
              Keluar
            </button>
          </form>
        </div>
      </div>
    </aside>

    <div class="flex-1 lg:ml-72">
      <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/80 backdrop-blur">
        <div class="flex flex-col gap-4 px-6 py-5 lg:flex-row lg:items-center lg:justify-between">
          <div class="flex items-center gap-3">
            <label for="admin-nav-toggle" class="lg:hidden flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600">
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M3 6h18M3 12h18M3 18h18"></path>
              </svg>
            </label>
            <div>
              <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Admin Dashboard</p>
              <h1 class="text-2xl font-serif font-semibold text-slate-900">@yield('page_title', 'Ringkasan Operasional')</h1>
            </div>
          </div>

          <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative">
              <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                  <circle cx="11" cy="11" r="7"></circle>
                  <path d="M20 20l-3.5-3.5"></path>
                </svg>
              </span>
              <input type="search" placeholder="Cari member, transaksi, event" class="h-11 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-4 text-sm text-slate-700 focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            </div>
            <div class="flex items-center gap-2">
              <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                Buat Event
              </button>
              <a href="{{ route('admin.membership.members.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-teal-700 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-teal-800">
                Tambah Member
              </a>
            </div>
          </div>
        </div>
      </header>

      <main class="px-6 py-8 lg:px-10 lg:py-10">
        @yield('content')
      </main>
    </div>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('[data-collapsible]').forEach((section) => {
        const trigger = section.querySelector('[data-collapsible-trigger]');
        const content = section.querySelector('[data-collapsible-content]');
        const icon = section.querySelector('[data-collapsible-icon]');
        if (!trigger || !content) return;

        const setState = (open) => {
          content.classList.toggle('hidden', !open);
          trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
          if (icon) {
            icon.classList.toggle('rotate-180', open);
          }
        };

        const isOpen = section.dataset.open === '1';
        setState(isOpen);

        trigger.addEventListener('click', () => {
          const nextOpen = trigger.getAttribute('aria-expanded') !== 'true';
          setState(nextOpen);
        });
      });
    });
  </script>
  <script src="https://unpkg.com/lucide@latest" onload="lucide.createIcons()"></script>
</body>
</html>
