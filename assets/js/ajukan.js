// File: assets/js/ajukan.js

document.addEventListener('DOMContentLoaded', function () {
  AOS.init();

  const form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', function () {
      const btn = document.getElementById('submitBtn');
      if (btn) {
        btn.disabled = true;
        btn.textContent = '⏳ Mengirim...';
      }
    });
  }
});
