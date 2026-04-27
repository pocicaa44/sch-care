<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - SCH Care</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('templates/css/login.css') }}">
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

            @if (session('error'))
                <div class="alert-auth error">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    {{ session('error') }}
                </div>
            @endif

            {{-- <form action="{{ route('login') }}" method="POST"> --}}
            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <!-- Email -->
                <div class="field-group">
                    <label class="field-label" for="email">Alamat Email</label>
                    <div class="field-wrap">
                        <i class="bi bi-envelope field-icon"></i>
                        <input type="email" id="email" name="email" class="field-input"
                            placeholder="email@example.com" autocomplete="email" required />
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
                        <input type="password" id="password" name="password" class="field-input has-eye"
                            placeholder="Masukkan password" autocomplete="current-password" required />
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
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye-slash';
            }
        }
    </script>

</body>

</html>
