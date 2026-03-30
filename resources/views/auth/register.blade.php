<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar — LaporKu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <style>
    :root {
      --red-deep:  #b91c1c;
      --red-vivid: #dc2626;
      --red-light: #fee2e2;
      --red-mid:   #fca5a5;
      --white:     #ffffff;
      --gray-50:   #f9fafb;
      --gray-100:  #f3f4f6;
      --gray-200:  #e5e7eb;
      --gray-400:  #9ca3af;
      --gray-600:  #4b5563;
      --gray-800:  #1f2937;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--white);
      min-height: 100vh;
      display: flex;
    }

    /* ─── LEFT PANEL ──────────────────────────────────── */
    .left-panel {
      width: 420px;
      flex-shrink: 0;
      background: var(--gray-800);
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 48px 44px;
    }

    .left-panel::before {
      content: '';
      position: absolute;
      width: 300px; height: 300px;
      border-radius: 50%;
      border: 50px solid rgba(220,38,38,.12);
      top: -80px; right: -100px;
    }
    .left-panel::after {
      content: '';
      position: absolute;
      width: 200px; height: 200px;
      border-radius: 50%;
      background: rgba(220,38,38,.07);
      bottom: 80px; left: -70px;
    }
    .deco-square {
      position: absolute;
      width: 80px; height: 80px;
      border: 2px solid rgba(220,38,38,.15);
      border-radius: 16px;
      bottom: 220px; right: 44px;
      transform: rotate(20deg);
    }
    .deco-dot-grid {
      position: absolute;
      bottom: 44px; right: 44px;
      display: grid;
      grid-template-columns: repeat(4, 8px);
      gap: 8px;
    }
    .deco-dot-grid span {
      width: 4px; height: 4px;
      border-radius: 50%;
      background: rgba(255,255,255,.12);
      display: block;
    }

    .left-brand { position: relative; z-index: 1; }
    .left-brand .brand-mark {
      width: 48px; height: 48px;
      background: var(--red-vivid);
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 20px; color: #fff;
      margin-bottom: 20px;
      box-shadow: 0 4px 16px rgba(220,38,38,.4);
    }
    .left-brand h1 {
      font-family: 'Playfair Display', serif;
      font-size: 2rem;
      font-weight: 700;
      color: #fff;
      line-height: 1.15;
      margin-bottom: 12px;
    }
    .left-brand p {
      font-size: .88rem;
      color: rgba(255,255,255,.5);
      line-height: 1.65;
      max-width: 280px;
    }

    .left-bottom { position: relative; z-index: 1; }
    .steps-list { list-style: none; display: flex; flex-direction: column; gap: 16px; }
    .steps-list li {
      display: flex;
      align-items: flex-start;
      gap: 12px;
    }
    .step-num {
      width: 24px; height: 24px;
      border-radius: 6px;
      background: rgba(220,38,38,.25);
      color: var(--red-mid);
      font-size: .72rem;
      font-weight: 700;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
      margin-top: 1px;
    }
    .step-text { font-size: .82rem; color: rgba(255,255,255,.55); line-height: 1.5; }
    .step-text strong { color: rgba(255,255,255,.85); display: block; margin-bottom: 1px; }

    /* ─── RIGHT PANEL ─────────────────────────────────── */
    .right-panel {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 48px 32px;
      background: var(--white);
      position: relative;
      overflow-y: auto;
    }

    .form-wrap {
      width: 100%;
      max-width: 400px;
      animation: fadeUp .45s ease both;
    }

    .form-header { margin-bottom: 28px; }
    .form-header .eyebrow {
      font-size: .72rem;
      font-weight: 600;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: var(--red-vivid);
      margin-bottom: 8px;
    }
    .form-header h2 {
      font-family: 'Playfair Display', serif;
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--gray-800);
      line-height: 1.2;
      margin-bottom: 8px;
    }
    .form-header p {
      font-size: .84rem;
      color: var(--gray-400);
      line-height: 1.55;
    }

    /* ─── FIELD ───────────────────────────────────────── */
    .field-group { margin-bottom: 16px; }
    .field-label {
      display: block;
      font-size: .82rem;
      font-weight: 600;
      color: var(--gray-800);
      margin-bottom: 7px;
    }
    .field-wrap { position: relative; }
    .field-wrap .field-icon {
      position: absolute;
      left: 13px; top: 50%;
      transform: translateY(-50%);
      color: var(--gray-400);
      font-size: .9rem;
      pointer-events: none;
    }
    .field-input {
      width: 100%;
      padding: 11px 14px 11px 38px;
      border: 1.5px solid var(--gray-200);
      border-radius: 10px;
      font-family: 'DM Sans', sans-serif;
      font-size: .9rem;
      color: var(--gray-800);
      background: var(--white);
      transition: border-color .15s, box-shadow .15s;
      outline: none;
    }
    .field-input:focus {
      border-color: var(--red-vivid);
      box-shadow: 0 0 0 3px rgba(220,38,38,.1);
    }
    .field-input::placeholder { color: var(--gray-400); }
    .field-input.is-invalid   { border-color: var(--red-vivid); }
    .field-input.is-valid     { border-color: #10b981; }
    .field-input.has-eye      { padding-right: 40px; }

    .field-error {
      font-size: .74rem;
      color: var(--red-vivid);
      margin-top: 5px;
      display: flex; align-items: center; gap: 4px;
    }
    .field-hint {
      font-size: .74rem;
      color: var(--gray-400);
      margin-top: 5px;
    }

    .btn-eye {
      position: absolute;
      right: 12px; top: 50%;
      transform: translateY(-50%);
      background: none; border: none;
      color: var(--gray-400); font-size: .9rem;
      cursor: pointer; padding: 2px;
      transition: color .15s;
    }
    .btn-eye:hover { color: var(--red-vivid); }

    /* ─── PASSWORD STRENGTH ───────────────────────────── */
    .strength-bar {
      display: flex; gap: 4px; margin-top: 8px;
    }
    .strength-segment {
      flex: 1; height: 3px; border-radius: 2px;
      background: var(--gray-200);
      transition: background .2s;
    }
    .strength-segment.weak   { background: #ef4444; }
    .strength-segment.medium { background: #f59e0b; }
    .strength-segment.strong { background: #10b981; }
    .strength-label {
      font-size: .72rem; margin-top: 5px;
      color: var(--gray-400);
    }
    .strength-label.weak   { color: #ef4444; }
    .strength-label.medium { color: #f59e0b; }
    .strength-label.strong { color: #10b981; }

    /* ─── ROW FIELDS (2 kolom) ────────────────────────── */
    .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

    /* ─── SUBMIT ──────────────────────────────────────── */
    .btn-auth {
      width: 100%;
      padding: 12px;
      border-radius: 10px;
      border: none;
      background: var(--red-vivid);
      color: #fff;
      font-family: 'DM Sans', sans-serif;
      font-size: .92rem;
      font-weight: 600;
      cursor: pointer;
      transition: background .15s, box-shadow .15s;
      box-shadow: 0 4px 14px rgba(220,38,38,.3);
      display: flex; align-items: center; justify-content: center;
      gap: 8px;
      margin-top: 6px;
    }
    .btn-auth:hover { background: var(--red-deep); box-shadow: 0 6px 20px rgba(220,38,38,.35); }

    /* ─── DIVIDER & SWITCH ────────────────────────────── */
    .divider {
      display: flex; align-items: center; gap: 12px;
      margin: 20px 0;
      color: var(--gray-400); font-size: .76rem;
    }
    .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--gray-200); }
    .switch-link { text-align: center; font-size: .84rem; color: var(--gray-400); }
    .switch-link a { color: var(--red-vivid); font-weight: 600; text-decoration: none; }
    .switch-link a:hover { text-decoration: underline; }

    /* ─── TERMS ───────────────────────────────────────── */
    .terms-note {
      font-size: .74rem;
      color: var(--gray-400);
      text-align: center;
      line-height: 1.55;
      margin-top: 16px;
    }
    .terms-note a { color: var(--red-vivid); text-decoration: none; }

    /* ─── FOOTER ──────────────────────────────────────── */
    .form-footer-note {
      margin-top: 28px;
      text-align: center;
      font-size: .72rem;
      color: var(--gray-400);
    }

    /* ─── ANIMATIONS ──────────────────────────────────── */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(18px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* ─── RESPONSIVE ──────────────────────────────────── */
    @media (max-width: 768px) {
      .left-panel  { display: none; }
      .right-panel { padding: 36px 24px; }
      .field-row   { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

  <!-- ═══ LEFT PANEL ════════════════════════════════════ -->
  <div class="left-panel">
    <div class="deco-square"></div>
    <div class="deco-dot-grid">
      <span></span><span></span><span></span><span></span>
      <span></span><span></span><span></span><span></span>
      <span></span><span></span><span></span><span></span>
    </div>

    <div class="left-brand">
      <div class="brand-mark"><i class="bi bi-file-earmark-text-fill"></i></div>
      <h1>Buat<br>Akun Baru</h1>
      <p>Daftar sekarang dan mulai laporkan masalah di sekolah dengan mudah.</p>
    </div>

    <div class="left-bottom">
      <ul class="steps-list">
        <li>
          <span class="step-num">1</span>
          <span class="step-text">
            <strong>Isi data diri</strong>
            Nama lengkap dan email sekolah kamu
          </span>
        </li>
        <li>
          <span class="step-num">2</span>
          <span class="step-text">
            <strong>Buat password</strong>
            Minimal 8 karakter untuk keamanan akun
          </span>
        </li>
        <li>
          <span class="step-num">3</span>
          <span class="step-text">
            <strong>Mulai melapor</strong>
            Langsung bisa kirim laporan ke admin sekolah
          </span>
        </li>
      </ul>
    </div>
  </div>

  <!-- ═══ RIGHT PANEL — REGISTER ════════════════════════ -->
  <div class="right-panel">
    <div class="form-wrap">

      <div class="form-header">
        <div class="eyebrow">Pendaftaran akun</div>
        <h2>Daftar sebagai<br>siswa</h2>
        <p>Sudah punya akun? <a href="{{ route('login') }}" style="color:var(--red-vivid);font-weight:600;text-decoration:none;">Masuk di sini</a></p>
      </div>

      @if($errors->any())
        <div class="alert-auth error" ...>
      @endif

      <form action="{{ route('register') }}" method="POST">
      
        @csrf

        <!-- Nama -->
        <div class="field-group">
          <label class="field-label" for="name">Nama Lengkap</label>
          <div class="field-wrap">
            <i class="bi bi-person field-icon"></i>
            <input type="text" id="name" name="name"
                   class="field-input"
                   placeholder="Nama sesuai identitas"
                   autocomplete="name"
                   value="{{ old('name') }}" 
                   required/>
          </div>
        </div>

        <!-- Email -->
        <div class="field-group">
          <label class="field-label" for="email">Alamat Email</label>
          <div class="field-wrap">
            <i class="bi bi-envelope field-icon"></i>
            <input type="email" id="email" name="email"
                   class="field-input"
                   placeholder="nama@sekolah.sch.id"
                   autocomplete="email"
                   value="{{ old('email') }}" 
                   required/>
          </div>
        </div>

        <!-- Password -->
        <div class="field-group">
          <label class="field-label" for="password">Password</label>
          <div class="field-wrap">
            <i class="bi bi-lock field-icon"></i>
            <input type="password" id="password" name="password"
                   class="field-input has-eye"
                   placeholder="Minimal 8 karakter"
                   autocomplete="new-password"
                   oninput="checkStrength(this.value)" 
                   required/>
            <button type="button" class="btn-eye" onclick="togglePassword('password', this)">
              <i class="bi bi-eye-slash"></i>
            </button>
          </div>
          <!-- Password strength indicator -->
          <div class="strength-bar" id="strengthBar">
            <div class="strength-segment" id="seg1"></div>
            <div class="strength-segment" id="seg2"></div>
            <div class="strength-segment" id="seg3"></div>
            <div class="strength-segment" id="seg4"></div>
          </div>
          <div class="strength-label" id="strengthLabel"></div>
        </div>

        <!-- Konfirmasi Password -->
        <div class="field-group">
          <label class="field-label" for="password_confirmation">Konfirmasi Password</label>
          <div class="field-wrap">
            <i class="bi bi-lock-fill field-icon"></i>
            <input type="password" id="password_confirmation"
                   name="password_confirmation"
                   class="field-input has-eye"
                   placeholder="Ulangi password"
                   autocomplete="new-password"
                   oninput="checkConfirm()" 
                   required/>
            <button type="button" class="btn-eye"
                    onclick="togglePassword('password_confirmation', this)">
              <i class="bi bi-eye-slash"></i>
            </button>
          </div>
          <div class="field-error" id="confirmError" style="display:none;">
            <i class="bi bi-exclamation-circle-fill"></i> Password tidak cocok
          </div>
        </div>

        <button type="submit" class="btn-auth" style="margin-top:8px;">
          <i class="bi bi-person-plus-fill"></i> Buat Akun
        </button>

      </form>

      {{-- <p class="terms-note">
        Dengan mendaftar, kamu menyetujui
        <a href="#">Syarat & Ketentuan</a> penggunaan platform ini.
      </p> --}}

      <div class="form-footer-note">
        &copy; 2026 SCH Care · Sistem Pelaporan Sekolah
      </div>

    </div>
  </div>

  <script>
    /* ─── Toggle password visibility ── */
    function togglePassword(id, btn) {
      const input = document.getElementById(id);
      const icon  = btn.querySelector('i');
      if (input.type === 'password') {
        input.type     = 'text';
        icon.className = 'bi bi-eye';
      } else {
        input.type     = 'password';
        icon.className = 'bi bi-eye-slash';
      }
    }

    /* ─── Password strength ──────────── */
    function checkStrength(val) {
      const segs  = [1,2,3,4].map(i => document.getElementById('seg' + i));
      const label = document.getElementById('strengthLabel');
      const reset = () => segs.forEach(s => { s.className = 'strength-segment'; });

      if (!val) { reset(); label.textContent = ''; return; }

      let score = 0;
      if (val.length >= 8)             score++;
      if (/[A-Z]/.test(val))           score++;
      if (/[0-9]/.test(val))           score++;
      if (/[^A-Za-z0-9]/.test(val))    score++;

      reset();
      if (score <= 1) {
        segs[0].classList.add('weak');
        label.textContent = 'Lemah';
        label.className   = 'strength-label weak';
      } else if (score === 2) {
        segs[0].classList.add('medium'); segs[1].classList.add('medium');
        label.textContent = 'Sedang';
        label.className   = 'strength-label medium';
      } else if (score === 3) {
        [0,1,2].forEach(i => segs[i].classList.add('strong'));
        label.textContent = 'Kuat';
        label.className   = 'strength-label strong';
      } else {
        segs.forEach(s => s.classList.add('strong'));
        label.textContent = 'Sangat Kuat';
        label.className   = 'strength-label strong';
      }
    }

    /* ─── Confirm password check ─────── */
    function checkConfirm() {
      const pw      = document.getElementById('password').value;
      const confirm = document.getElementById('password_confirmation').value;
      const errEl   = document.getElementById('confirmError');
      const input   = document.getElementById('password_confirmation');

      if (confirm.length === 0) {
        errEl.style.display = 'none';
        input.classList.remove('is-invalid', 'is-valid');
        return;
      }
      if (pw === confirm) {
        errEl.style.display = 'none';
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
      } else {
        errEl.style.display = 'flex';
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
      }
    }
  </script>
</body>
</html>