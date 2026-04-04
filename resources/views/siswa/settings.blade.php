@extends('layouts.app')

@section('title', 'Settings')

@push('styles')
    <link rel="stylesheet" href="{{ asset('templates/css/settings.css') }}">
@endpush

@section('content')
  <main class="page-body">
    <div class="row g-4">
      <div class="col-12 col-xl-8">

        <!-- ─── PROFIL ──────────────────────────────────── -->
        <div class="settings-card fade-up">
          <div class="settings-card-header">
            <div class="settings-header-icon gray"><i class="bi bi-person-fill"></i></div>
            <div>
              <h3>Informasi Profil</h3>
              <p>Nama dan email yang terdaftar di akun kamu</p>
            </div>
          </div>
          <div class="settings-card-body">

            <div class="profile-avatar-row">
              <div class="profile-avatar-lg">AU</div>
              <div class="profile-info">
                <div class="pname">{{auth()->user()->name}}</div>
                <div class="pemail">{{auth()->user()->email}}</div>
                <div class="prole"><i class="bi bi-mortarboard-fill"></i> {{auth()->user()->role}}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- ─── AUTO DELETE ─────────────────────────────── -->
        <div class="settings-card fade-up">
          <div class="settings-card-header">
            <div class="settings-header-icon red"><i class="bi bi-trash3-fill"></i></div>
            <div>
              <h3>Hapus Otomatis Laporan</h3>
              <p>Laporan yang sudah selesai atau ditolak akan terhapus secara otomatis</p>
            </div>
          </div>
          <div class="settings-card-body">

            <!-- Pilihan hari -->
            <form action="{{ route('siswa.settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="section-label"><i class="bi bi-calendar-week"></i> Hapus setelah</div>
    
                <div class="radio-group" id="radioGroup">
    
                  <label class="radio-option">
                    <input type="radio" name="auto_delete_days" value="3" {{ $user->auto_delete_days == 3 ? 'checked' : '' }}/>
                    <div class="radio-card">
                      <div class="radio-dot"></div>
                      <div class="radio-content">
                        <div class="radio-title">3 Hari</div>
                        <div class="radio-desc">Laporan dihapus 3 hari setelah status berubah</div>
                      </div>
                    </div>
                  </label>
    
                  <label class="radio-option">
                    <input type="radio" name="auto_delete_days" value="7" {{ $user->auto_delete_days == 7 ? 'checked' : '' }}/>
                    <div class="radio-card">
                      <div class="radio-dot"></div>
                      <div class="radio-content">
                        <div class="radio-title">7 Hari</div>
                        <div class="radio-desc">Laporan dihapus 7 hari setelah status berubah</div>
                      </div>
                    </div>
                  </label>
    
                  <label class="radio-option">
                    <input type="radio" name="auto_delete_days" value="14" {{ $user->auto_delete_days == 14 ? 'checked' : '' }}/>
                    <div class="radio-card">
                      <div class="radio-dot"></div>
                      <div class="radio-content">
                        <div class="radio-title">14 Hari</div>
                        <div class="radio-desc">Laporan dihapus 14 hari setelah status berubah</div>
                      </div>
                    </div>
                  </label>
    
                  <label class="radio-option">
                    <input type="radio" name="auto_delete_days" value="30" {{ $user->auto_delete_days == 30 ? 'checked' : '' }}/>
                    <div class="radio-card">
                      <div class="radio-dot"></div>
                      <div class="radio-content">
                        <div class="radio-title">30 Hari</div>
                        <div class="radio-desc">Laporan dihapus 30 hari setelah status berubah</div>
                      </div>
                      <span class="radio-badge recommended">Default</span>
                    </div>
                  </label>
    
                </div>
                <div class="settings-card-footer">
                  <button type="submit" class="btn-save ms-auto" onclick="showToast()">
                    <i class="bi bi-check-lg"></i> Simpan Pengaturan
                  </button>
                </div>
            </form>

            <!-- Info note -->
            <div id="autoDeleteNote" style="display:none;margin-top:16px;">
              <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:12px 16px;display:flex;align-items:flex-start;gap:10px;">
                <i class="bi bi-info-circle-fill" style="color:#2563eb;font-size:.9rem;margin-top:1px;flex-shrink:0;"></i>
                <div style="font-size:.8rem;color:#1e40af;line-height:1.55;">
                  Laporan yang terhapus otomatis <strong>tidak dapat dipulihkan</strong>. Pastikan kamu sudah tidak membutuhkan laporan tersebut sebelum fitur ini aktif.
                </div>
              </div>
            </div>

          </div>
        </div>

        {{-- <!-- ─── DANGER ZONE ──────────────────────────────── -->
        <div class="settings-card fade-up" style="border-color:#fca5a5;">
          <div class="settings-card-header" style="background:#fff8f8;border-bottom-color:#fee2e2;">
            <div class="settings-header-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div>
              <h3 style="color:var(--red-deep);">Zona Berbahaya</h3>
              <p>Tindakan berikut bersifat permanen dan tidak dapat dibatalkan</p>
            </div>
          </div>
          <div class="settings-card-body">
            <div class="danger-zone">
              <div class="danger-zone-header">
                <i class="bi bi-shield-exclamation"></i>
                <span>Tindakan Permanen</span>
              </div>
              <div class="danger-item">
                <div class="danger-item-text">
                  <div class="dtitle">Hapus Akun</div>
                  <div class="ddesc">Akun dan seluruh data kamu akan dihapus secara permanen</div>
                </div>
                <button class="btn-danger" onclick="confirmDeleteAccount()">
                  <i class="bi bi-person-x me-1"></i> Hapus Akun
                </button>
              </div>
            </div>
          </div>
        </div> --}}

      </div><!-- /.col -->

      <!-- ─── SIDEBAR INFO ────────────────────────────── -->
      <div class="col-12 col-xl-4">
        {{-- <!-- Info Auto Delete -->
        <div class="settings-card fade-up">
          <div class="settings-card-header">
            <div class="settings-header-icon red"><i class="bi bi-clock-history"></i></div>
            <div>
              <h3>Status Auto-Delete</h3>
              <p>Pengaturan hapus otomatis saat ini</p>
            </div>
          </div>
          <div class="settings-card-body">
            <div id="autoDeleteSummary"
                 style="text-align:center;padding:16px 0;">
              <div style="width:52px;height:52px;border-radius:50%;background:var(--gray-100);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:1.3rem;color:var(--gray-400);">
                <i class="bi bi-slash-circle"></i>
              </div>
              <div style="font-size:.86rem;font-weight:600;color:var(--gray-600);">Tidak Aktif</div>
              <div style="font-size:.76rem;color:var(--gray-400);margin-top:4px;">Laporan tidak akan dihapus otomatis</div>
            </div>
          </div>
        </div>

      </div> --}}
    </div><!-- /.row -->
@endsection

<!-- FAB -->
<a href="#" class="fab" aria-label="Tambah Laporan">
  <i class="bi bi-plus-lg"></i>
</a>

<!-- Toast -->
<div class="toast-saved" id="toastSaved">
  <i class="bi bi-check-circle-fill"></i> Pengaturan berhasil disimpan
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  /* ─── AUTO DELETE TOGGLE ────────────────── */
  function toggleAutoDelete(cb) {
    const group   = document.getElementById('radioGroup');
    const note    = document.getElementById('autoDeleteNote');
    const status  = document.getElementById('autoDeleteStatus');
    const row     = document.getElementById('autoDeleteToggleRow');
    const summary = document.getElementById('autoDeleteSummary');

    if (cb.checked) {
      group.classList.remove('disabled');
      note.style.display = 'block';
      row.classList.add('active');

      const selected = document.querySelector('input[name="auto_delete_days"]:checked')?.value || 7;
      status.innerHTML = `<i class="bi bi-check-circle-fill me-1" style="color:#059669;"></i>
        <span style="color:#059669;">Aktif — ${selected} hari</span>`;

      summary.innerHTML = `
        <div style="text-align:center;padding:16px 0;">
          <div style="width:52px;height:52px;border-radius:50%;background:var(--red-light);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:1.3rem;color:var(--red-vivid);">
            <i class="bi bi-trash3-fill"></i>
          </div>
          <div style="font-size:.86rem;font-weight:600;color:var(--red-deep);">Aktif</div>
          <div style="font-size:.76rem;color:var(--gray-400);margin-top:4px;">Hapus setelah <strong>${selected} hari</strong></div>
        </div>`;
    } else {
      group.classList.add('disabled');
      note.style.display = 'none';
      row.classList.remove('active');
      status.innerHTML = `<i class="bi bi-dash-circle me-1"></i> Tidak aktif`;
      summary.innerHTML = `
        <div style="text-align:center;padding:16px 0;">
          <div style="width:52px;height:52px;border-radius:50%;background:var(--gray-100);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:1.3rem;color:var(--gray-400);">
            <i class="bi bi-slash-circle"></i>
          </div>
          <div style="font-size:.86rem;font-weight:600;color:var(--gray-600);">Tidak Aktif</div>
          <div style="font-size:.76rem;color:var(--gray-400);margin-top:4px;">Laporan tidak akan dihapus otomatis</div>
        </div>`;
    }
  }

  /* Update status saat radio berubah */
  document.querySelectorAll('input[name="auto_delete_days"]').forEach(r => {
    r.addEventListener('change', () => {
      const toggle = document.getElementById('autoDeleteToggle');
      if (!toggle.checked) return;
      const status  = document.getElementById('autoDeleteStatus');
      const summary = document.getElementById('autoDeleteSummary');
      status.innerHTML = `<i class="bi bi-check-circle-fill me-1" style="color:#059669;"></i>
        <span style="color:#059669;">Aktif — ${r.value} hari</span>`;
      summary.querySelector('div[style*="strong"]') &&
        (summary.querySelector('strong').textContent = r.value + ' hari');
    });
  });

  /* ─── TOAST ─────────────────────────────── */
  function showToast() {
    const t = document.getElementById('toastSaved');
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
  }

  /* ─── DANGER ACTIONS ─────────────────────── */
  function confirmBulkDelete() {
    if (confirm('Yakin ingin menghapus semua laporan? Tindakan ini tidak dapat dibatalkan.')) {
      showToast();
    }
  }
  function confirmDeleteAccount() {
    if (confirm('Yakin ingin menghapus akun? Seluruh data kamu akan hilang permanen.')) {
      alert('(simulasi) Akun dihapus.');
    }
  }

  /* ─── OFFCANVAS NAV ──────────────────────── */
  document.getElementById('mobileNav')
    ?.querySelectorAll('.nav-link:not(.logout)')
    .forEach(link => {
      link.addEventListener('click', function(e) {
        const instance = bootstrap.Offcanvas.getInstance(document.getElementById('mobileNav'));
        if (instance) {
          e.preventDefault();
          const href = this.getAttribute('href');
          instance.hide();
          document.getElementById('mobileNav').addEventListener(
            'hidden.bs.offcanvas',
            () => { window.location.href = href; },
            { once: true }
          );
        }
      });
    });
</script>
