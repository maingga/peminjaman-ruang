<?php
$adminNama = htmlspecialchars($_SESSION['admin']['name'] ?? 'Admin');
?>

<header class="bg-[#005BAC] dark:bg-[#003f7d] text-white shadow-md py-4 px-6 flex justify-between items-center transition-colors duration-300">
  <!-- Kiri: Brand / Menu -->
  <div class="flex items-center gap-4">
    <!-- Sidebar Toggle (Mobile) -->
    <button id="openSidebar" class="md:hidden p-2 rounded-lg bg-[#66C3F5] hover:bg-[#4cb6f3] text-[#005BAC] dark:text-white transition duration-300 shadow-md">
      <i data-feather="menu" class="w-5 h-5"></i>
    </button>

    <!-- Logo & Title -->
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-full bg-white text-[#005BAC] dark:bg-[#66C3F5] dark:text-[#003f7d] flex items-center justify-center shadow-inner transition-colors duration-300">
        <i data-feather="sliders" class="w-4 h-4"></i>
      </div>
      <h1 class="text-2xl font-semibold tracking-tight leading-none dark:text-white">
        Admin Dashboard
      </h1>
    </div>
  </div>

  <!-- Kanan: User & Logout -->
  <div class="flex items-center gap-6">
    <!-- Salam Admin -->
    <p class="text-sm font-medium text-white dark:text-[#D1E9FF]">
      Halo, <span class="text-[#66C3F5] dark:text-[#a6dcfb] font-semibold"><?= $adminNama ?></span>
    </p>

    <!-- Logout Button -->
    <a href="logout.php" class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium bg-rose-500 hover:bg-rose-600 text-white transition duration-300 shadow-md">
      <i data-feather="log-out" class="w-4 h-4"></i> Logout
    </a>
  </div>
</header>
