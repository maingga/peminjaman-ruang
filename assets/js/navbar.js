document.addEventListener('DOMContentLoaded', function () {
  const toggle = document.getElementById('menu-toggle');
  const menu = document.getElementById('mobile-menu');
  const icon = document.getElementById('menu-icon');

  let isOpen = false;

  toggle.addEventListener('click', () => {
    menu.classList.toggle('hidden');
    isOpen = !isOpen;

    icon.innerHTML = isOpen
      ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />`
      : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />`;
  });
});
