/* ── Filter chip toggle ─────────────────── */
document.querySelectorAll('.filter-chip').forEach(chip => {
    chip.addEventListener('click', () => {
        document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
        chip.classList.add('active');
    });
});

  /* ── Delete confirmation (cosmetic) ──────── */
document.querySelectorAll('.btn-hapus').forEach(btn => {
    btn.addEventListener('click', function () {
        const card = this.closest('.report-card');
        if (confirm('Yakin ingin menghapus laporan ini?')) {
        card.style.transition = 'opacity .3s, transform .3s';
        card.style.opacity = '0';
        card.style.transform = 'scale(.96)';
        setTimeout(() => card.closest('.col-12').remove(), 310);
        }
    });
});

  /* ── Detail button (cosmetic) ────────────── */
document.querySelectorAll('.btn-detail').forEach(btn => {
    btn.addEventListener('click', function () {
        const title = this.closest('.report-card').querySelector('.report-title').textContent;
        alert('Membuka detail: ' + title);
    });
});