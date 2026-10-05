/* =========================================================
   BrainCanvas — script.js
   Sprint 02: Checklist Card interaktif
   Fitur: centang tugas, tambah tugas, hapus yang selesai,
   hitung progres, dan simpan otomatis (localStorage).
   ========================================================= */

const KUNCI = 'braincanvas:tugas';

/* ---------- Select element (Task 01) ---------- */
const daftar   = document.querySelector('#daftar-tugas');
const progres  = document.querySelector('#progres');
const bar      = document.querySelector('#bar');
const form     = document.querySelector('#form-tugas');
const inputBaru = document.querySelector('#tugas-baru');
const pesan    = document.querySelector('#pesan');
const btnHapus = document.querySelector('#hapus-selesai');

/* ---------- State ---------- */
// Data awal diambil dari HTML, kecuali sudah ada data tersimpan.
let tugas = muat() ?? ambilDariHtml();

function ambilDariHtml() {
  return [...daftar.querySelectorAll('li')].map((li, i) => ({
    id: i + 1,
    teks: li.querySelector('label').textContent,
    selesai: li.querySelector('input').checked,
  }));
}

function muat() {
  try {
    return JSON.parse(localStorage.getItem(KUNCI));
  } catch {
    return null;
  }
}

function simpan() {
  try {
    localStorage.setItem(KUNCI, JSON.stringify(tugas));
  } catch {
    /* penyimpanan tidak tersedia: abaikan, halaman tetap jalan */
  }
}

/* ---------- Render (ubah DOM) ---------- */
function buatItem(t) {
  const li = document.createElement('li');

  const cek = document.createElement('input');
  cek.type = 'checkbox';
  cek.id = `tugas-${t.id}`;
  cek.dataset.id = t.id;
  cek.checked = t.selesai;

  const label = document.createElement('label');
  label.htmlFor = cek.id;
  label.textContent = t.teks;           // textContent: aman dari HTML injection

  li.append(cek, label);
  return li;
}

function tampilProgres() {
  const selesai = tugas.filter(t => t.selesai).length;
  progres.textContent = `${selesai} dari ${tugas.length} tugas selesai`;
  bar.max = Math.max(tugas.length, 1);
  bar.value = selesai;
  btnHapus.disabled = selesai === 0;
}

function render() {
  daftar.replaceChildren(...tugas.map(buatItem));
  tampilProgres();
}

/* ---------- Event handling (Task 02) ---------- */

// 1) change: centang / hilangkan centang (event delegation di <ul>)
daftar.addEventListener('change', (e) => {
  const id = Number(e.target.dataset.id);
  const t = tugas.find(item => item.id === id);
  if (!t) return;
  t.selesai = e.target.checked;
  simpan();
  tampilProgres();
});

// 2) submit: tambah tugas baru
form.addEventListener('submit', (e) => {
  e.preventDefault();
  const teks = inputBaru.value.trim();

  if (!teks) {
    pesan.textContent = 'Tulis nama tugas dulu ya.';
    inputBaru.focus();
    return;
  }

  const idBaru = tugas.reduce((maks, t) => Math.max(maks, t.id), 0) + 1;
  tugas.push({ id: idBaru, teks, selesai: false });
  simpan();
  render();

  pesan.textContent = '';
  form.reset();
  inputBaru.focus();
});

// 3) click: hapus semua tugas yang sudah selesai
btnHapus.addEventListener('click', () => {
  tugas = tugas.filter(t => !t.selesai);
  simpan();
  render();
});

// Pesan error hilang begitu pengguna mulai mengetik lagi
inputBaru.addEventListener('input', () => {
  pesan.textContent = '';
});

render();