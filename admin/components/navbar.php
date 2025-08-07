<?php
$adminNama = htmlspecialchars($_SESSION['admin']['name'] ?? 'Admin');
?>

<header class="bg-white dark:bg-gray-900 shadow-md py-4 px-6 flex justify-between items-center">
  <div class="flex items-center gap-3">
    <button id="openSidebar" class="md:hidden bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg">
      <i data-feather="menu" class="w-5 h-5"></i>
    </button>
    <i data-feather="sliders" class="text-blue-600 dark:text-blue-400 w-6 h-6"></i>
    <span class="text-xl font-bold tracking-wide text-gray-800 dark:text-gray-100">Admin Dashboard</span>
  </div>
  <div class="flex items-center gap-4">
    <span class="text-sm">Halo, <strong><?= $adminNama ?></strong></span>
    <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded-full text-sm transition">Logout</a>
  </div>
</header>
