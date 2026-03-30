<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Masuk — LaporKu</title>
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
      background: var(--red-vivid);
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 48px 44px;
    }

    /* Decorative geometric shapes */
    .left-panel::before {
      content: '';
      position: absolute;
      width: 340px; height: 340px;
      border-radius: 50%;
      border: 60px solid rgba(255,255,255,.07);
      top: -100px; right: -120px;
    }
    .left-panel::after {
      content: '';
      position: absolute;
      width: 220px; height: 220px;
      border-radius: 50%;
      border: 40px solid rgba(255,255,255,.06);
      bottom: 60px; left: -80px;
    }
    .deco-circle {
      position: absolute;
      width: 160px; height: 160px;
      border-radius: 50%;
      background: rgba(255,255,255,.05);
      bottom: 180px; right: -50px;
    }
    .deco-line {
      position: absolute;
      width: 1px;
      background: rgba(255,255,255,.12);
      top: 0; bottom: 0;
      right: 48px;
    }

    .left-brand {
      position: relative;
      z-index: 1;
    }
    .left-brand .brand-mark {
      width: 48px; height: 48px;
      background: rgba(255,255,255,.18);
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 20px; color: #fff;
      margin-bottom: 20px;
      backdrop-filter: blur(4px);
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
      color: rgba(255,255,255,.7);
      line-height: 1.65;
      max-width: 280px;
    }

    .left-bottom {
      position: relative;
      z-index: 1;
    }
    .feature-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }
    .feature-list li {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: .82rem;
      color: rgba(255,255,255,.8);
    }
    .feature-list li .feat-dot {
      width: 6px; height: 6px;
      border-radius: 50%;
      background: rgba(255,255,255,.5);
      flex-shrink: 0;
    }

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
    }

    .form-wrap {
      width: 100%;
      max-width: 400px;
    }

    /* ─── FORM HEADER ─────────────────────────────────── */
    .form-header {
      margin-bottom: 32px;
    }
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

    /* ─── FORM FIELDS ─────────────────────────────────── */
    .field-group { margin-bottom: 18px; }
    .field-label {
      display: block;
      font-size: .82rem;
      font-weight: 600;
      color: var(--gray-800);
      margin-bottom: 7px;
    }
    .field-wrap {
      position: relative;
    }
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
    .field-input.is-invalid {
      border-color: var(--red-vivid);
      box-shadow: 0 0 0 3px rgba(220,38,38,.1);
    }
    .field-error {
      font-size: .74rem;
      color: var(--red-vivid);
      margin-top: 5px;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    /* toggle password visibility */
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
    .field-input.has-eye { padding-right: 40px; }

    /* ─── SUBMIT BUTTON ───────────────────────────────── */
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
      transition: background .15s, box-shadow .15s, transform .1s;
      box-shadow: 0 4px 14px rgba(220,38,38,.3);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-top: 6px;
    }
    .btn-auth:hover  { background: var(--red-deep); box-shadow: 0 6px 20px rgba(220,38,38,.35); }
    .btn-auth:active { transform: scale(.99); }

    /* ─── DIVIDER ─────────────────────────────────────── */
    .divider {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 22px 0;
      color: var(--gray-400);
      font-size: .76rem;
    }
    .divider::before, .divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: var(--gray-200);
    }

    /* ─── SWITCH LINK ─────────────────────────────────── */
    .switch-link {
      text-align: center;
      font-size: .84rem;
      color: var(--gray-400);
    }
    .switch-link a {
      color: var(--red-vivid);
      font-weight: 600;
      text-decoration: none;
    }
    .switch-link a:hover { text-decoration: underline; }

    /* ─── ALERT ───────────────────────────────────────── */
    .alert-auth {
      border-radius: 10px;
      padding: 12px 16px;
      font-size: .84rem;
      display: flex;
      align-items: flex-start;
      gap: 9px;
      margin-bottom: 20px;
      line-height: 1.5;
    }
    .alert-auth.error   { background: var(--red-light); color: #991b1b; border: 1px solid var(--red-mid); }
    .alert-auth.success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
    .alert-auth i { margin-top: 1px; flex-shrink: 0; }

    /* ─── FOOTER NOTE ─────────────────────────────────── */
    .form-footer-note {
      position: absolute;
      bottom: 24px;
      left: 0; right: 0;
      text-align: center;
      font-size: .72rem;
      color: var(--gray-400);
    }

    /* ─── ANIMATIONS ──────────────────────────────────── */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(18px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .form-wrap { animation: fadeUp .45s ease both; }

    /* ─── RESPONSIVE ──────────────────────────────────── */
    @media (max-width: 768px) {
      .left-panel { display: none; }
      .right-panel { padding: 36px 24px; }
    }
  </style>
</head>
<body>

  <!-- ═══ LEFT PANEL ════════════════════════════════════ -->
  <div class="left-panel">
    <div class="deco-circle"></div>
    <div class="deco-line"></div>

    <div class="left-brand">
      <div class="brand-mark"><i class="bi bi-file-earmark-text-fill"></i></div>
      <h1>SCH<br>Care</h1>
      <p>Platform pelaporan sekolah yang aman, mudah, dan terpercaya untuk siswa dan admin.</p>
    </div>

    <div class="left-bottom">
      <ul class="feature-list">
        <li><span class="feat-dot"></span> Laporan langsung sampai ke admin</li>
        <li><span class="feat-dot"></span> Pilihan kirim secara anonim</li>
        <li><span class="feat-dot"></span> Pantau status laporan kamu</li>
        <li><span class="feat-dot"></span> Bukti foto bisa dilampirkan</li>
      </ul>
    </div>
  </div>

  <!-- ═══ RIGHT PANEL — LOGIN ════════════════════════════ -->
  <div class="right-panel">

    <div class="form-wrap">

      <div class="form-header">
        <div class="eyebrow">Selamat datang</div>
        <h2>Masuk ke akun<br>kamu</h2>
        <p>Masukkan email dan password untuk melanjutkan.</p>
      </div>

      @if(session('error'))
        <div class="alert-auth error">
          <i class="bi bi-exclamation-circle-fill"></i>
          {{ session('error') }}
        </div>
      @endif

      <!-- Demo alert error -->
      <div class="alert-auth error" style="display:none;">
        <i class="bi bi-exclamation-circle-fill"></i>
        Email atau password tidak sesuai.
      </div>

      {{-- <form action="{{ route('login') }}" method="POST"> --}}
      <form action="{{ route('login.post') }}" method="POST">
        @csrf

        <!-- Email -->
        <div class="field-group">
          <label class="field-label" for="email">Alamat Email</label>
          <div class="field-wrap">
            <i class="bi bi-envelope field-icon"></i>
            <input type="email" id="email" name="email"
                   class="field-input"
                   placeholder="email@example.com"
                   autocomplete="email" 
                   required/>
          </div>
        </div>

        <!-- Password -->
        <div class="field-group">
          <div style="display:flex;align-items:center;justify-content:space-between;">
            <label class="field-label" for="password" style="margin-bottom:0;">Password</label>
            {{-- <a href="{{ route('password.request') }}" --}}
            {{-- <a href="#"
               style="font-size:.78rem;color:var(--red-vivid);text-decoration:none;font-weight:500;">
              Lupa password?
            </a> --}}
          </div>
          <div class="field-wrap" style="margin-top:7px;">
            <i class="bi bi-lock field-icon"></i>
            <input type="password" id="password" name="password"
                   class="field-input has-eye"
                   placeholder="Masukkan password"
                   autocomplete="current-password" 
                   required/>
            <button type="button" class="btn-eye" onclick="togglePassword('password', this)">
              <i class="bi bi-eye-slash" id="eyeIcon-password"></i>
            </button>
          </div>
        </div>

        <!-- Remember me -->
        {{-- <div style="display:flex;align-items:center;gap:9px;margin-bottom:20px;">
          <input type="checkbox" id="remember" name="remember"
                 style="width:16px;height:16px;accent-color:var(--red-vivid);cursor:pointer;" />
          <label for="remember"
                 style="font-size:.82rem;color:var(--gray-600);cursor:pointer;">
            Ingat saya selama 30 hari
          </label>
        </div> --}}

        <button type="submit" class="btn-auth">
          <i class="bi bi-box-arrow-in-right"></i> Masuk
        </button>

      </form>

      <div class="divider">atau</div>

      <div class="switch-link">
        Belum punya akun?
        <a href="{{ route('register') }}">Daftar sekarang</a>
      </div>

    </div>

    <div class="form-footer-note">
      &copy; 2026 LaporKu · Sistem Pelaporan Sekolah
    </div>

  </div>

  <script>
    function togglePassword(fieldId, btn) {
      const input = document.getElementById(fieldId);
      const icon  = btn.querySelector('i');
      if (input.type === 'password') {
        input.type  = 'text';
        icon.className = 'bi bi-eye';
      } else {
        input.type  = 'password';
        icon.className = 'bi bi-eye-slash';
      }
    }
  </script>

</body>
</html>