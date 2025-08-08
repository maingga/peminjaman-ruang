<?php
$adminNama = htmlspecialchars($_SESSION['admin']['name'] ?? 'Admin');
?>

<header class="bg-[#005BAC] dark:bg-[#003f7d] text-white shadow-md py-4 px-6 flex justify-between items-center transition-colors duration-300 relative">
  <!-- Kiri: Brand / Menu -->
  <div class="flex items-center gap-4">
    <!-- Sidebar Toggle (Mobile) -->
    <button id="openSidebar" class="md:hidden p-2 rounded-lg bg-[#66C3F5] hover:bg-[#4cb6f3] text-[#005BAC] dark:text-white transition duration-300 shadow-md" aria-label="Toggle sidebar">
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

  <!-- Kanan: User dropdown -->
  <div class="relative">
    <button id="userMenuButton" aria-haspopup="true" aria-expanded="false" class="flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-blue-300 rounded-md px-3 py-1 hover:bg-blue-700 transition">
      <span class="text-sm font-medium text-white dark:text-[#D1E9FF]">Halo, <span class="font-semibold text-[#66C3F5] dark:text-[#a6dcfb]"><?= $adminNama ?></span></span>
      <i data-feather="chevron-down" class="w-4 h-4 text-white"></i>
    </button>

    <!-- Dropdown menu, hidden by default -->
    <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
      <a href="ubah_password.php" class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-blue-600 hover:text-white transition">
        <i data-feather="key" class="inline w-4 h-4 mr-2"></i> Ubah Password
      </a>
      <div class="border-t border-gray-200 dark:border-gray-700"></div>
      <a href="logout.php" class="block px-4 py-2 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white transition font-semibold">
        <i data-feather="log-out" class="inline w-4 h-4 mr-2"></i> Logout
      </a>
    </div>
  </div>
</header>

<script>
  feather.replace();

  // Toggle dropdown user menu
  const userMenuButton = document.getElementById('userMenuButton');
  const userDropdown = document.getElementById('userDropdown');

  userMenuButton.addEventListener('click', () => {
    const isExpanded = userMenuButton.getAttribute('aria-expanded') === 'true';
    userDropdown.classList.toggle('hidden');
    userMenuButton.setAttribute('aria-expanded', String(!isExpanded));
  });

  // Close dropdown on click outside
  document.addEventListener('click', (event) => {
    if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
      userDropdown.classList.add('hidden');
      userMenuButton.setAttribute('aria-expanded', 'false');
    }
  });
</script>
