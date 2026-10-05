/* =========================================================
   BrainCanvas — app.js (Sprint 03)
   Kartu dan posisinya diambil dan disimpan lewat Fetch API.
   Backend: json-server  ->  npx json-server@0.17.4 db.json
   ========================================================= */

const API = 'http://localhost:3000/cards';
const HEADERS = { 'Content-Type': 'application/json' };

const canvas   = document.querySelector('#canvas');
const statusEl = document.querySelector('#syncStatus');
const panel    = document.querySelector('#panel');
const pesan    = document.querySelector('#pesanApi');
const btnUlang = document.querySelector('#btnUlang');
const coords   = document.querySelector('#coords');

let kartu = [];      // data kartu dari API
let geser = null;    // state drag yang sedang berjalan
let tundaSimpan;     // timer simpan posisi saat geser pakai keyboard

/* ---------- Helper ---------- */
function setStatus(teks, error = false) {
  statusEl.textContent = teks;
  statusEl.classList.toggle('error', error);
}

async function api(url, opsi) {
  const res = await fetch(url, opsi);
  if (!res.ok) throw new Error(`Server membalas ${res.status}`);
  return res.json();
}

const sama   = (a, b) => String(a) === String(b);
const cari   = (node) => kartu.find(k => sama(k.id, node.closest('.card').dataset.id));
const batas  = (n, min, maks) => Math.min(Math.max(n, min), Math.max(maks, min));

function buatEl(tag, kelas, teks) {
  const e = document.createElement(tag);
  if (kelas) e.className = kelas;
  if (teks !== undefined) e.textContent = teks;
  return e;
}

/* ---------- Task 01 + 03: ambil data, tampilkan loading / error ---------- */
async function muat() {
  panel.hidden = false;
  panel.classList.remove('error');
  pesan.textContent = 'Memuat kanvas...';
  btnUlang.hidden = true;
  canvas.setAttribute('aria-busy', 'true');
  setStatus('Memuat...');

  try {
    kartu = await api(API);                 // GET /cards
    render();                               // Task 02
    setStatus('✓ Tersimpan');
    if (kartu.length) panel.hidden = true;
    else pesan.textContent = 'Kanvas masih kosong. Tambahkan kartu dari toolbar.';
  } catch (err) {
    console.error(err);
    panel.classList.add('error');
    pesan.textContent = 'Gagal memuat kanvas. Pastikan json-server sedang berjalan.';
    btnUlang.hidden = false;
    setStatus('Gagal terhubung', true);
  } finally {
    canvas.removeAttribute('aria-busy');
  }
}

async function simpan(id, data) {           // PATCH /cards/:id
  setStatus('Menyimpan...');
  try {
    await api(`${API}/${id}`, { method: 'PATCH', headers: HEADERS, body: JSON.stringify(data) });
    Object.assign(kartu.find(k => sama(k.id, id)), data);
    setStatus('✓ Tersimpan');
  } catch (err) {
    console.error(err);
    setStatus('Gagal menyimpan', true);
  }
}

/* ---------- Task 02: ubah data JSON menjadi elemen kartu ---------- */
function gambarTodo(ul, k) {
  k.tugas ??= [];
  ul.replaceChildren(...k.tugas.map((t, i) => {
    const li = buatEl('li');
    const label = buatEl('label');
    const cb = buatEl('input');
    cb.type = 'checkbox';
    cb.checked = t.selesai;
    cb.dataset.i = i;
    label.append(cb, ' ' + t.teks);
    li.append(label);
    return li;
  }));
}

// Kotak catatan melebar ke bawah mengikuti isi tulisan
function tumbuh(ta) {
  ta.style.height = 'auto';
  ta.style.height = `${ta.scrollHeight}px`;
}

function taruh(card, x, y) {
  card.style.left = `${Math.round(x)}px`;
  card.style.top  = `${Math.round(y)}px`;
  coords.textContent = `X: ${Math.round(x)}px, Y: ${Math.round(y)}px`;
}

function buatKartu(k) {
  const card = buatEl('div', `card ${k.tipe}-card`);
  card.dataset.id = k.id;
  card.style.left = `${k.x}px`;
  card.style.top  = `${k.y}px`;

  const head = buatEl('div', 'card-header');
  const handle = buatEl('button', 'drag-handle', '⋮⋮');
  handle.type = 'button';
  handle.title = 'Geser kartu (bisa juga dengan tombol panah)';
  handle.setAttribute('aria-label', 'Geser kartu');
  const tutup = buatEl('button', 'btn-close', '×');
  tutup.type = 'button';
  tutup.setAttribute('aria-label', 'Hapus kartu');
  head.append(handle, buatEl('span', 'card-type', k.tipe), tutup);

  const body = buatEl('div', 'card-body');
  if (k.tipe === 'note') {
    const ta = buatEl('textarea');
    ta.value = k.isi ?? '';
    ta.placeholder = 'Tuliskan ide Anda...';
    ta.setAttribute('aria-label', 'Isi catatan');
    body.append(ta);
  } else if (k.tipe === 'todo') {
    const ul = buatEl('ul');
    gambarTodo(ul, k);
    const baru = buatEl('input', 'todo-baru');
    baru.placeholder = 'Tambah tugas, tekan Enter';
    baru.setAttribute('aria-label', 'Tugas baru');
    body.append(ul, baru);
  } else {
    const fig = buatEl('figure');
    const img = buatEl('img');
    const cap = buatEl('figcaption', '', k.keterangan);
    img.src = k.url;
    img.alt = k.keterangan || 'Gambar kartu';
    img.addEventListener('error', () => { cap.textContent = 'Gambar gagal dimuat'; });
    fig.append(img, cap);
    body.append(fig);
  }

  card.append(head, body);
  return card;
}

function render() {
  canvas.replaceChildren(...kartu.map(buatKartu));
  canvas.querySelectorAll('textarea').forEach(tumbuh);
}

/* ---------- Event: ubah isi, centang, tambah tugas, hapus ---------- */
canvas.addEventListener('input', (e) => {
  if (e.target.matches('textarea')) tumbuh(e.target);
});

canvas.addEventListener('change', (e) => {
  if (!e.target.closest('.card')) return;
  const k = cari(e.target);
  if (e.target.matches('textarea')) simpan(k.id, { isi: e.target.value });
  if (e.target.matches('[type="checkbox"]')) {
    k.tugas[e.target.dataset.i].selesai = e.target.checked;
    simpan(k.id, { tugas: k.tugas });
  }
});

canvas.addEventListener('keydown', (e) => {
  // Enter di kolom tugas baru: tambah tugas
  if (e.key === 'Enter' && e.target.matches('.todo-baru')) {
    const teks = e.target.value.trim();
    if (!teks) return;
    const k = cari(e.target);
    k.tugas.push({ teks, selesai: false });
    gambarTodo(e.target.closest('.card').querySelector('ul'), k);
    e.target.value = '';
    simpan(k.id, { tugas: k.tugas });
    return;
  }

  // Tombol panah di ikon geser: pindahkan kartu 10px (alternatif drag mouse)
  const arah = { ArrowLeft: [-10, 0], ArrowRight: [10, 0], ArrowUp: [0, -10], ArrowDown: [0, 10] }[e.key];
  if (!arah || !e.target.matches('.drag-handle')) return;
  e.preventDefault();
  const card = e.target.closest('.card');
  const x = batas(card.offsetLeft + arah[0], 0, canvas.clientWidth - card.offsetWidth);
  const y = batas(card.offsetTop + arah[1], 0, canvas.clientHeight - card.offsetHeight);
  taruh(card, x, y);
  clearTimeout(tundaSimpan);
  tundaSimpan = setTimeout(() => simpan(card.dataset.id, { x, y }), 400);
});

canvas.addEventListener('click', async (e) => {
  if (!e.target.matches('.btn-close')) return;
  const card = e.target.closest('.card');
  const id = card.dataset.id;
  setStatus('Menghapus...');
  try {
    await api(`${API}/${id}`, { method: 'DELETE' });
    kartu = kartu.filter(k => !sama(k.id, id));
    card.remove();
    setStatus('✓ Tersimpan');
  } catch (err) {
    console.error(err);
    setStatus('Gagal menghapus', true);
  }
});

/* ---------- Tambah kartu dari toolbar (POST) ---------- */
document.querySelector('.toolbar').addEventListener('click', async (e) => {
  const tipe = e.target.closest('button[data-tipe]')?.dataset.tipe;
  if (!tipe) return;

  const offset = (kartu.length % 6) * 30;
  const baru = { tipe, x: 40 + offset, y: 40 + offset };
  if (tipe === 'note') baru.isi = '';
  if (tipe === 'todo') baru.tugas = [];
  if (tipe === 'image') {
    const url = prompt('Tautan gambar (https://... atau storage/nama-file.jpg)')?.trim();
    if (!url) return;
    try {
      if (!/^https?:$/.test(new URL(url, location.href).protocol)) throw new Error();
    } catch {
      setStatus('Tautan gambar tidak valid', true);
      return;
    }
    baru.url = url;
    baru.keterangan = 'Gambar baru';
  }

  setStatus('Menyimpan...');
  try {
    const dibuat = await api(API, { method: 'POST', headers: HEADERS, body: JSON.stringify(baru) });
    kartu.push(dibuat);
    canvas.append(buatKartu(dibuat));
    canvas.querySelectorAll('textarea').forEach(tumbuh);
    panel.hidden = true;
    setStatus('✓ Tersimpan');
  } catch (err) {
    console.error(err);
    setStatus('Gagal menambah kartu', true);
  }
});

/* ---------- Drag and drop + simpan posisi (X, Y) ---------- */
canvas.addEventListener('pointerdown', (e) => {
  const handle = e.target.closest('.drag-handle');
  if (!handle || e.button !== 0) return;
  const card = handle.closest('.card');
  const r = card.getBoundingClientRect();
  geser = { card, dx: e.clientX - r.left, dy: e.clientY - r.top };
  handle.setPointerCapture(e.pointerId);
  card.classList.add('dragging');
});

canvas.addEventListener('pointermove', (e) => {
  if (!geser) return;
  const { card, dx, dy } = geser;
  const c = canvas.getBoundingClientRect();
  taruh(
    card,
    batas(e.clientX - c.left - dx, 0, canvas.clientWidth - card.offsetWidth),
    batas(e.clientY - c.top - dy, 0, canvas.clientHeight - card.offsetHeight)
  );
});

function lepas() {
  if (!geser) return;
  const { card } = geser;
  geser = null;
  card.classList.remove('dragging');
  simpan(card.dataset.id, { x: card.offsetLeft, y: card.offsetTop });
}
canvas.addEventListener('pointerup', lepas);
canvas.addEventListener('pointercancel', lepas);

btnUlang.addEventListener('click', muat);

muat();