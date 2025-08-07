<?php
function statistikBox($title, $icon, $color, $value = '--') {
  echo '
  <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md hover:shadow-xl transition-transform hover:scale-105 border-l-4 border-' . $color . '-500">
    <div class="flex items-center justify-between">
      <h2 class="text-lg font-semibold">' . $title . '</h2>
      <i data-feather="' . $icon . '" class="w-6 h-6 text-' . $color . '-500"></i>
    </div>
    <p class="text-4xl mt-3 text-' . $color . '-600 dark:text-' . $color . '-400 font-bold">' . $value . '</p>
  </div>';
}
