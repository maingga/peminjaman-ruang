// Countdown Script
const target = new Date(new Date().getTime() + 30 * 60 * 1000); // +30 menit
const countdown = document.getElementById('countdown');

function updateCountdown() {
  const now = new Date();
  const diff = target - now;
  if (diff <= 0) {
    countdown.innerText = "Sedang Berlangsung";
    return;
  }
  const h = String(Math.floor(diff / 3600000)).padStart(2, '0');
  const m = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
  const s = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
  countdown.innerText = `${h}:${m}:${s}`;
}

if (countdown) {
  updateCountdown();
  setInterval(updateCountdown, 1000);
}

// Dark Mode Toggle Script with localStorage
const toggleBtn = document.getElementById('darkToggle');
const html = document.documentElement;

// Fungsi set mode tema
function setTheme(mode) {
  if (mode === 'dark') {
    html.classList.add('dark');
    toggleBtn.innerText = '☀️';
  } else {
    html.classList.remove('dark');
    toggleBtn.innerText = '🌙';
  }
}

// Cek preferensi dari localStorage saat load
const savedTheme = localStorage.getItem('theme');
if (savedTheme) {
  setTheme(savedTheme);
} else {
  // Jika belum ada preferensi, pakai default: light
  setTheme('light');
}

// Event toggle
if (toggleBtn) {
  toggleBtn.addEventListener('click', () => {
    const isDark = html.classList.contains('dark');
    const newTheme = isDark ? 'light' : 'dark';
    setTheme(newTheme);
    localStorage.setItem('theme', newTheme);
  });
}

// Dynamic Year
document.getElementById("year").textContent = new Date().getFullYear();

// AOS Init
if (typeof AOS !== 'undefined') {
  AOS.init({
    duration: 800,
    once: true,
    easing: 'ease-in-out',
    offset: 50,
  });
}
