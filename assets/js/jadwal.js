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
      extraParams: function() {
        return {
          status: document.getElementById('status').value,
          ruangan: document.getElementById('ruangan').value,
        };
      }
    },
    eventClick: function(info) {
      currentEventId = info.event.id;
      document.getElementById('modalTitle').textContent = info.event.title;
      document.getElementById('modalTime').textContent = info.event.start.toLocaleString('id-ID', {
        dateStyle: 'full',
        timeStyle: 'short',
      });
      document.getElementById('modalRoom').textContent = 'Ruangan: ' + (info.event.extendedProps.room || '-');
      modal.classList.remove('hidden');
    }
  });

  calendar.render();

  document.getElementById('filterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    calendar.refetchEvents();
  });

  approveBtn.addEventListener('click', function() {
    if (confirm("Setujui peminjaman ini?")) {
      window.location.href = 'approve.php?id=' + currentEventId + '&action=approve';
    }
  });

  rejectBtn.addEventListener('click', function() {
    if (confirm("Tolak peminjaman ini?")) {
      window.location.href = 'approve.php?id=' + currentEventId + '&action=reject';
    }
  });
});

function closeModal() {
  document.getElementById('eventModal').classList.add('hidden');
}
