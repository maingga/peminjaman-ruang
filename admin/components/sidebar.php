<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<aside id="sidebar"
  class="w-64 bg-gradient-to-br from-white to-blue-50 dark:from-gray-800 dark:to-gray-900 shadow-md p-4 space-y-4 
  fixed md:relative z-40 top-0 left-0 min-h-screen transform transition-transform duration-300 ease-in-out 
  -translate-x-full md:translate-x-0">

  <div class="flex items-center justify-between mb-6">
    <h2 class="text-lg font-bold text-gray-700 dark:text-gray-100">📂 Navigasi</h2>
    <button id="closeSidebar" class="md:hidden text-gray-600 dark:text-gray-300 hover:text-red-500">
      <i data-feather="x" class="w-5 h-5"></i>
    </button>
  </div>

  <ul class="space-y-2">
    <?php
    $navItems = [
      ['dashboard.php', 'home', 'Dashboard'],
      ['peminjaman.php', 'folder', 'Peminjaman'],
      ['ruangan.php', 'layout', 'Ruangan'],
      ['jadwal.php', 'calendar', 'Jadwal'],
      ['pengguna.php', 'user', 'Pengguna'],
    ];

    foreach ($navItems as [$url, $icon, $label]) {
      $active = $currentPage === $url;
      echo '<li>
        <a href="' . $url . '" class="flex items-center gap-2 p-2 rounded-lg transition ' .
        ($active ? 'bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 dark:hover:bg-blue-800') . '">
          <i data-feather="' . $icon . '" class="w-5 h-5"></i> ' . $label . '
        </a>
      </li>';
    }
    ?>
  </ul>
</aside>
