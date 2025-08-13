<nav class="bg-primary/80 backdrop-blur-md text-white shadow-lg fixed top-0 inset-x-0 z-50 dark:bg-gray-800 dark:text-white">
  <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
    <!-- Logo / Brand -->
    <a href="index.php" class="text-2xl font-semibold tracking-tight flex items-center gap-2">
      <img src="assets/images/favicon.svg" alt="Logo" class="w-7 h-7" />
      <span>Dinas Komunikasi dan Informatika Kabupaten Kediri</span>
    </a>

    <!-- Desktop Navigation -->
    <div class="hidden md:flex items-center gap-8 text-base font-medium">
      <a href="ajukan.php" class="hover:text-secondary transition-colors duration-300">Ajukan</a>
      <a href="jadwal.php" class="hover:text-secondary transition-colors duration-300">Jadwal</a>
      <a href="riwayat.php" class="hover:text-secondary transition-colors duration-300">Riwayat</a>
      <a href="admin/login.php" class="bg-secondary hover:bg-secondary/90 text-white px-4 py-2 rounded-md shadow-md transition-all duration-300">Login Admin</a>
    </div>

    <!-- Mobile Menu Button -->
    <button id="menu-toggle" aria-label="Toggle menu" class="md:hidden focus:outline-none transition duration-300">
      <svg id="menu-icon" class="w-7 h-7 text-white transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>
  </div>

  <!-- Mobile Navigation -->
  <div id="mobile-menu" class="hidden md:hidden bg-primary/90 backdrop-blur px-6 pb-4 pt-2 space-y-2 transition-all duration-300 ease-in-out text-sm font-medium dark:bg-gray-800">
    <a href="ajukan.php" class="block py-2 hover:text-secondary transition">Ajukan</a>
    <a href="jadwal.php" class="block py-2 hover:text-secondary transition">Jadwal</a>
    <a href="riwayat.php" class="block py-2 hover:text-secondary transition">Riwayat</a>
    <a href="admin/login.php" class="block py-2 text-secondary font-semibold hover:underline">Login Admin</a>
  </div>
</nav>
