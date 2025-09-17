<?php
require_once 'inc/db.php';
date_default_timezone_set('Asia/Jakarta');

// ============================
// Ambil Hero Background dari DB
// ============================
$stmt = $pdo->prepare("SELECT value_text FROM settings WHERE key_name = 'hero_background'");
$stmt->execute();
$heroBg = $stmt->fetchColumn() ?: 'assets/images/ruang-rapat.jpeg';

// ============================
// Ambil Jadwal Rapat Terdekat
// ============================
$rooms = ['Hall Utama', 'Ruang Rapat Kadis'];
$nextMeetings = [];

foreach ($rooms as $roomName) {
    $stmt = $pdo->prepare("
        SELECT b.date, b.start_time
        FROM bookings b
        JOIN rooms r ON b.room_id = r.id
        WHERE r.name = :roomName
          AND CONCAT(b.date, ' ', b.start_time) > NOW()
        ORDER BY CONCAT(b.date, ' ', b.start_time) ASC
        LIMIT 1
    ");
    $stmt->execute(['roomName' => $roomName]);
    $meeting = $stmt->fetch(PDO::FETCH_ASSOC);

    $nextMeetings[$roomName] = $meeting 
        ? [
            'target' => date('c', strtotime($meeting['date'].' '.$meeting['start_time'])),
            'date' => $meeting['date'],
            'start_time' => $meeting['start_time']
          ]
        : null;
}
?>

<?php include 'inc/header.php'; ?>
<?php include 'inc/navbar.php'; ?>

<!-- ================= Hero Section ================= -->
<section class="relative bg-cover bg-center h-[600px] md:h-[700px]"
         style="background-image: url('<?php echo htmlspecialchars($heroBg); ?>');">
  <div class="absolute inset-0 bg-gradient-to-br from-black/60 via-black/40 to-black/60 flex items-center justify-center">
    <div class="text-white text-center px-4" data-aos="zoom-in-up" data-aos-duration="1000">
      <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-4">
        <span class="bg-clip-text text-transparent bg-gradient-to-r from-yellow-400 via-red-500 to-pink-500 drop-shadow-md">
          Sistem Peminjaman
        </span><br>
        <span class="text-white">Ruang Rapat Digital</span>
      </h1>
      <p class="mt-3 text-lg md:text-xl max-w-xl mx-auto text-gray-200">
        Kelola peminjaman ruang rapat <span class="font-semibold">lebih cepat, efisien</span>, dan <span class="font-semibold">tanpa tumpang tindih</span>.
      </p>
      <div class="mt-6">
        <a href="ajukan.php" class="inline-block px-8 py-4 bg-yellow-500 text-black font-semibold rounded-full shadow-lg hover:bg-yellow-600 hover:scale-105 transition-transform duration-300">
          🚀 Ajukan Sekarang
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ================= Countdown Section ================= -->
<section class="py-16 bg-white dark:bg-gray-900 text-center" data-aos="fade-up">
  <div class="max-w-2xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12">
    <?php foreach ($nextMeetings as $roomName => $meeting): ?>
      <div>
        <h2 class="text-2xl font-bold text-[#0D47A1] dark:text-white mb-4">
          <?php echo htmlspecialchars($roomName); ?> Dalam:
        </h2>
        <div class="countdown text-5xl font-mono font-semibold text-[#1976D2] dark:text-yellow-400 tracking-widest"
             data-room="<?php echo htmlspecialchars($roomName); ?>"
             data-target="<?php echo $meeting ? $meeting['target'] : ''; ?>">
          <?php echo $meeting ? '00:00:00' : 'Tidak ada jadwal'; ?>
        </div>
        <?php if ($meeting): ?>
          <p class="mt-2 text-gray-600 dark:text-gray-300">
            🗓 <?php echo date('d M Y', strtotime($meeting['date'])); ?> — ⏰ <?php echo date('H:i', strtotime($meeting['start_time'])); ?>
          </p>
        <?php else: ?>
          <p class="mt-2 text-gray-600 dark:text-gray-300">Tidak ada jadwal rapat berikutnya.</p>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ================= Fitur Section ================= -->
<section class="py-24 bg-[#E3F2FD] dark:bg-gray-900">
  <div class="max-w-6xl mx-auto px-6">
    <h2 class="text-3xl font-bold text-center text-[#0D47A1] dark:text-white mb-12">Fitur Utama</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-center">
      <?php
      $features = [
        ['icon'=>'📅','title'=>'Lihat Jadwal','desc'=>'Cek ketersediaan ruang secara real-time tanpa perlu konfirmasi manual.'],
        ['icon'=>'📝','title'=>'Ajukan Peminjaman','desc'=>'Formulir digital siap isi untuk mempercepat proses pengajuan.'],
        ['icon'=>'🔐','title'=>'Login Admin','desc'=>'Akses admin untuk memverifikasi dan mengatur semua jadwal rapat.'],
      ];
      foreach ($features as $index => $f): ?>
        <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg hover:shadow-2xl transition" 
             data-aos="fade-up" data-aos-delay="<?php echo ($index+1)*100; ?>">
          <div class="text-6xl mb-4 text-[#1976D2]"><?php echo $f['icon']; ?></div>
          <h3 class="text-xl font-semibold mb-2 text-[#0D47A1] dark:text-white"><?php echo $f['title']; ?></h3>
          <p class="text-gray-600 dark:text-gray-300"><?php echo $f['desc']; ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ================= Testimoni Section ================= -->
<section class="py-20 bg-white dark:bg-gray-900" data-aos="fade-up">
  <div class="max-w-5xl mx-auto px-6 text-center">
    <h2 class="text-3xl font-bold text-[#0D47A1] dark:text-white mb-10">Apa Kata Pengguna</h2>
    <div class="bg-[#E3F2FD] dark:bg-gray-800 rounded-2xl p-8 md:p-10 shadow-lg transition hover:shadow-xl">
      <svg class="mx-auto mb-4 w-12 h-12 text-[#0D47A1] dark:text-white" fill="currentColor" viewBox="0 0 24 24">
        <path d="M7 17v-7H3V7h6v10H7zm10 0v-7h-4V7h6v10h-2z"/>
      </svg>
      <p class="text-gray-700 dark:text-gray-300 text-lg italic">"Sistem ini sangat membantu kami menjadwalkan rapat tanpa tumpang tindih. Prosesnya cepat, transparan, dan mudah digunakan."</p>
      <div class="mt-6 font-bold text-[#0D47A1] dark:text-yellow-300">– Ibu Sari, Dinas Kesehatan</div>
    </div>
  </div>
</section>

<?php include 'inc/footer.php'; ?>

<!-- JS -->
<script src="assets/js/navbar.js"></script>
