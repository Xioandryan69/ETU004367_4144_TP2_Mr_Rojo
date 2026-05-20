document.addEventListener('DOMContentLoaded', function () {
  const dateDebut = document.getElementById('date-debut');
  const dateFin = document.getElementById('date-fin');
  const box = document.getElementById('jours-box');
  const nbEl = document.getElementById('jours-nb');
  const rangeEl = document.getElementById('jours-range');

  if (!dateDebut || !dateFin || !box || !nbEl || !rangeEl) {
    return;
  }

  const pad2 = (n) => String(n).padStart(2, '0');
  const fmtDMY = (d) => `${pad2(d.getDate())}/${pad2(d.getMonth() + 1)}/${d.getFullYear()}`;

  const update = () => {
    const v1 = dateDebut.value;
    const v2 = dateFin.value;
    if (!v1 || !v2) {
      box.style.display = 'none';
      return;
    }

    const start = new Date(v1 + 'T00:00:00');
    const end = new Date(v2 + 'T00:00:00');
    if (Number.isNaN(start.getTime()) || Number.isNaN(end.getTime()) || start > end) {
      box.style.display = 'none';
      return;
    }

    const diffDays = Math.floor((end - start) / 86400000) + 1;
    nbEl.textContent = String(diffDays);
    rangeEl.textContent = `du ${fmtDMY(start)} au ${fmtDMY(end)}`;
    box.style.display = '';
  };

  dateDebut.addEventListener('change', update);
  dateFin.addEventListener('change', update);
  update();
});
