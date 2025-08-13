// Countdown
const countdownElem = document.getElementById('countdown');
if (countdownElem) {
  const targetAttr = countdownElem.getAttribute('data-target');
  if (targetAttr) {
    const targetDate = new Date(targetAttr).getTime();

    function updateCountdown() {
      const now = Date.now();
      const diff = targetDate - now;

      if (diff <= 0) {
        countdownElem.innerText = "Sedang Berlangsung";
        return;
      }

      const h = String(Math.floor(diff / (1000 * 60 * 60))).padStart(2, '0');
      const m = String(Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
      const s = String(Math.floor((diff % (1000 * 60)) / 1000)).padStart(2, '0');

      countdownElem.innerText = `${h}:${m}:${s}`;
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);
  } else {
    countdownElem.innerText = "Tidak ada jadwal";
  }
}

// Dark Mode Toggle
const toggleBtn = document.getElementById('darkToggle');
const html = document.documentElement;

function setTheme(mode) {
  if (mode === 'dark') {
    html.classList.add('dark');
    if (toggleBtn) toggleBtn.innerText = '☀️';
  } else {
    html.classList.remove('dark');
    if (toggleBtn) toggleBtn.innerText = '🌙';
  }
}

const savedTheme = localStorage.getItem('theme');
setTheme(savedTheme || 'light');

if (toggleBtn) {
  toggleBtn.addEventListener('click', () => {
    const isDark = html.classList.contains('dark');
    const newTheme = isDark ? 'light' : 'dark';
    setTheme(newTheme);
    localStorage.setItem('theme', newTheme);
  });
}

// Dynamic Year
const yearElem = document.getElementById("year");
if (yearElem) {
  yearElem.textContent = new Date().getFullYear();
}

// AOS Init
if (typeof AOS !== 'undefined') {
  AOS.init({
    duration: 800,
    once: true,
    easing: 'ease-in-out',
    offset: 50,
  });
}
