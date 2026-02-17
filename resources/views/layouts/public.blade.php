<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>@yield('title', 'Baca Di Tebet - Perpustakaan & Ruang Kreatif')</title>
  <meta name="description" content="@yield('meta_description', 'Perpustakaan, Library, Ruang Temu, Ruang Kreatif, Buku, Baca Buku, BDT, Baca di Tebet, Creative Space, Ruang Pikir, Ruang Baca')">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="{{ asset('images/bdt-icon.png') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">

  <script src="https://unpkg.com/lucide@latest" defer></script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDFBF7] text-slate-800 font-sans">
  @yield('content')
</body>
</html>
