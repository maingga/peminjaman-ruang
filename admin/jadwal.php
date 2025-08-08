<?php
session_start();
require_once '../inc/db.php';
require_once '../inc/auth.php'; // proteksi login admin

// Ambil semua ruangan untuk filter
$rooms = $pdo->query("SELECT * FROM rooms")->fetchAll();

include '../inc/header.php';
?>

<style>
  /* Animasi modal smooth fade-in & scale */
  .modal-enter {
    animation: fadeInScale 0.25s ease forwards;
  }
  @keyframes fadeInScale {
    0% {opacity: 0; transform: scale(0.95);}
    100% {opacity: 1; transform: scale(1);}
  }

  /* FullCalendar dark mode tweaks */
  body.dark .fc {
    background-color: #1f2937 !important;
    color: #f3f4f6 !important;
    border-color: #374151 !important;
  }
  body.dark .fc-toolbar-chunk button {
    background-color: #374151 !important;
    color: #f3f4f6 !important;
    border: none;
    transition: background-color 0.2s ease;
  }
  body.dark .fc-toolbar-chunk button:hover,
  body.dark .fc-toolbar-chunk button:focus {
    background-color: #4b5563 !important;
    outline: none;
  }
  .fc-theme-standard .fc-toolbar-title {
    font-weight: 700;
  }
</style>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100 dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-gray-100 transition-colors duration-500">
  <?php include 'components/navbar.php'; ?>

  <div class="flex min-h-screen">
    <?php include 'components/sidebar.php'; ?>

    <main class="flex-1 p-6 md:p-10 overflow-auto">
      <!-- Header -->
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-extrabold flex items-center gap-3 text-blue-700 dark:text-blue-400 select-none">
          <i data-feather="calendar" class="w-8 h-8"></i>
          Jadwal Peminjaman
        </h1>
      </div>

      <!-- Filter -->
      <form id="filterForm" class="flex flex-wrap gap-4 mb-8 p-5 bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 max-w-3xl">
        <select name="status" id="status" class="flex-1 min-w-[160px] border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-100 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
          <option value="">Semua Status</option>
          <option value="pending">Menunggu</option>
          <option value="approved">Disetujui</option>
          <option value="rejected">Ditolak</option>
        </select>
        <select name="ruangan" id="ruangan" class="flex-1 min-w-[160px] border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-100 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
          <option value="">Semua Ruangan</option>
          <?php foreach($rooms as $room): ?>
            <option value="<?= $room['id'] ?>"><?= htmlspecialchars($room['name']) ?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-400 rounded-lg text-white font-semibold shadow-md transition select-none">
          Terapkan Filter
        </button>
      </form>

      <!-- Kalender -->
      <div id="calendar" class="bg-white dark:bg-gray-900 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 p-5"></div>
    </main>
  </div>
</div>

<!-- Modal Detail -->
<div id="eventModal" class="hidden fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
  <div class="bg-white dark:bg-gray-800 p-7 rounded-2xl w-full max-w-md shadow-2xl modal-enter">
    <h3 id="modalTitle" class="text-2xl font-bold mb-5 text-blue-700 dark:text-blue-300 truncate"></h3>
    <p id="modalTime" class="text-gray-700 dark:text-gray-300 mb-3"></p>
    <p id="modalRoom" class="text-gray-700 dark:text-gray-300 mb-6"></p>
    <div class="flex justify-end gap-3">
      <button id="approveBtn" class="bg-green-600 hover:bg-green-700 active:bg-green-800 text-white px-4 py-2 rounded-lg font-semibold transition select-none shadow-md">
        ✅ Setujui
      </button>
      <button id="rejectBtn" class="bg-red-600 hover:bg-red-700 active:bg-red-800 text-white px-4 py-2 rounded-lg font-semibold transition select-none shadow-md">
        ❌ Tolak
      </button>
      <button onclick="closeModal()" class="bg-gray-600 hover:bg-gray-700 active:bg-gray-800 text-white px-4 py-2 rounded-lg font-semibold transition select-none shadow-md">
        Tutup
      </button>
    </div>
  </div>
</div>

<?php include '../inc/footer.php'; ?>

<!-- FullCalendar -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const calendarEl = document.getElementById('calendar');
  const modal = document.getElementById('eventModal');
  const approveBtn = document.getElementById('approveBtn');
  const rejectBtn = document.getElementById('rejectBtn');
  let currentEventId = null;

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    locale: 'id',
    themeSystem: 'standard',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
    },
    buttonText: {
      today: 'Hari ini',
      month: 'Bulan',
      week: 'Minggu',
      day: 'Hari',
      list: 'Daftar'
    },
    events: {
      url: 'load_events.php',
      method: 'GET',
      extraParams: () => ({
        status: document.getElementById('status').value,
        ruangan: document.getElementById('ruangan').value,
      }),
    },
    eventClick: info => {
      currentEventId = info.event.id;
      document.getElementById('modalTitle').textContent = info.event.title;
      document.getElementById('modalTime').textContent = new Date(info.event.start).toLocaleString('id-ID', {
        dateStyle: 'full',
        timeStyle: 'short',
      });
      document.getElementById('modalRoom').textContent = `Ruangan: ${info.event.extendedProps.room || '-'}`;
      modal.classList.remove('hidden');
    }
  });

  calendar.render();

  document.getElementById('filterForm').addEventListener('submit', e => {
    e.preventDefault();
    calendar.refetchEvents();
  });

  approveBtn.addEventListener('click', () => {
    if (confirm("Setujui peminjaman ini?")) {
      window.location.href = `approve.php?id=${currentEventId}&action=approve`;
    }
  });

  rejectBtn.addEventListener('click', () => {
    if (confirm("Tolak peminjaman ini?")) {
      window.location.href = `approve.php?id=${currentEventId}&action=reject`;
    }
  });
});

function closeModal() {
  const modal = document.getElementById('eventModal');
  modal.classList.add('hidden');
}
</script>

<script>
  feather.replace();
</script>
