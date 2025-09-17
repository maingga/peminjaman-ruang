<?php
require_once '../inc/db.php';

// Ambil jadwal rapat terdekat yang belum mulai
$stmt = $pdo->prepare("
    SELECT b.date, b.start_time, r.name AS room_name
    FROM bookings b
    JOIN rooms r ON b.room_id = r.id
    WHERE CONCAT(b.date, ' ', b.start_time) > NOW()
    ORDER BY CONCAT(b.date, ' ', b.start_time) ASC
    LIMIT 1
");
$stmt->execute();
$nextMeeting = $stmt->fetch(PDO::FETCH_ASSOC);
$targetTime = $nextMeeting ? $nextMeeting['date'] . ' ' . $nextMeeting['start_time'] : null;
?>

<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md">
    <h2 class="text-2xl font-bold mb-2 flex items-center gap-2">
        <i data-feather="clock" class="w-6 h-6 text-red-500"></i>
        Countdown ke Jadwal Terdekat
    </h2>
    <p class="text-gray-600 dark:text-gray-300 mb-4">Waktu menuju peminjaman berikutnya:</p>

    <div id="countdown-dashboard" class="text-center text-5xl font-mono text-red-600 dark:text-red-400">
        <?php echo $targetTime ? '00:00:00' : 'Tidak ada jadwal'; ?>
    </div>

    <?php if ($nextMeeting): ?>
        <p class="mt-4 text-center text-gray-700 dark:text-gray-300">
            📍 <strong><?= htmlspecialchars($nextMeeting['room_name']); ?></strong><br>
            🗓 <?= date('d M Y', strtotime($nextMeeting['date'])); ?> — ⏰ <?= date('H:i', strtotime($nextMeeting['start_time'])); ?>
        </p>
    <?php endif; ?>
</div>

<?php if ($targetTime): ?>
<script>
    const countdownElemDashboard = document.getElementById('countdown-dashboard');
    const targetDateDashboard = new Date("<?= $targetTime ?>").getTime();

    function updateCountdownDashboard() {
        const now = new Date().getTime();
        const diff = targetDateDashboard - now;

        if (diff <= 0) {
            countdownElemDashboard.innerText = "Sedang Berlangsung";
            return;
        }

        const h = String(Math.floor(diff / (1000 * 60 * 60))).padStart(2, '0');
        const m = String(Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
        const s = String(Math.floor((diff % (1000 * 60)) / 1000)).padStart(2, '0');

        countdownElemDashboard.innerText = `${h}:${m}:${s}`;
    }

    updateCountdownDashboard();
    setInterval(updateCountdownDashboard, 1000);
</script>
<?php endif; ?>
