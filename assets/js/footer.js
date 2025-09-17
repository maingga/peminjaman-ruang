document.addEventListener("DOMContentLoaded", () => {

  // =======================
  // Countdown Multi-Ruangan
  // =======================
  const countdownElems = document.querySelectorAll(".countdown");

  countdownElems.forEach(el => {
    function startCountdown(target) {
      if (!target) {
        el.textContent = "Tidak ada jadwal";
        return;
      }

      let endTime = new Date(target).getTime();

      const timer = setInterval(() => {
        const now = Date.now();
        let distance = endTime - now;

        if (distance <= 0) {
          clearInterval(timer);
          el.textContent = "Rapat dimulai!";
          return;
        }

        const hours = Math.floor(distance / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        el.textContent = `${hours.toString().padStart(2,'0')}:${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}`;
      }, 1000);
    }

    const target = el.getAttribute("data-target");
    startCountdown(target);
  });

  // =======================
  // Dark Mode Toggle
  // =======================
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

  // =======================
  // Dynamic Year
  // =======================
  const yearElem = document.getElementById("year");
  if (yearElem) yearElem.textContent = new Date().getFullYear();

  // =======================
  // AOS Init
  // =======================
  if (typeof AOS !== 'undefined') {
    AOS.init({
      duration: 800,
      once: true,
      easing: 'ease-in-out',
      offset: 50,
    });
  }

});
