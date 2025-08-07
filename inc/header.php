<?php include __DIR__ . '/config.php'; ?>

<!-- Script untuk memastikan dark mode aktif sebelum HTML dirender -->
<script>
  (function () {
    const savedTheme = localStorage.getItem('theme');
    if (
      savedTheme === 'dark' || 
      (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)
    ) {
      document.documentElement.classList.add('dark');
    }
  })();
</script>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Sistem Peminjaman Ruang Rapat Diskominfo</title>

  <!-- SEO Meta -->
  <meta name="description" content="Sistem Peminjaman Ruang Rapat Hall Utama & Ruang Kadis Diskominfo. Ajukan peminjaman dan cek jadwal secara online." />
  <meta name="keywords" content="Diskominfo, peminjaman ruang rapat, hall utama, ruang kadis, sistem reservasi, ruang meeting" />
  <meta name="author" content="Diskominfo Kabupaten/Kota" />
  <meta name="robots" content="index, follow" />
  <link rel="canonical" href="https://yourdomain.com/" />

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="<?= $base_url ?>assets/images/favicon.svg" />
  <link rel="alternate icon" type="image/png" href="<?= $base_url ?>assets/images/favicon.png" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Tailwind Custom Config -->
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            primary: '#1e40af',
            secondary: '#f59e0b',
          },
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
          },
        },
      },
    }
  </script>

  <!-- AOS Animate on Scroll -->
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css" />
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?= $base_url ?>assets/css/style.css" />

  <!-- Mobile Theme -->
  <meta name="theme-color" content="#1e40af" />

  <!-- Open Graph -->
  <meta property="og:title" content="Sistem Peminjaman Ruang Rapat Diskominfo" />
  <meta property="og:description" content="Ajukan peminjaman dan cek jadwal Hall Utama & Ruang Kadis Diskominfo secara online." />
  <meta property="og:image" content="<?= $base_url ?>assets/images/cover.jpg" />
  <meta property="og:url" content="https://yourdomain.com/" />
  <meta property="og:type" content="website" />

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="Sistem Peminjaman Ruang Rapat Diskominfo" />
  <meta name="twitter:description" content="Ajukan peminjaman dan cek jadwal Hall Utama & Ruang Kadis Diskominfo secara online." />
  <meta name="twitter:image" content="<?= $base_url ?>assets/images/cover.jpg" />

  <!-- Feather Icons -->
  <script src="https://unpkg.com/feather-icons"></script>
  
</head>
<body class="bg-white text-black dark:bg-gray-900 dark:text-white transition-colors duration-300 font-sans">
