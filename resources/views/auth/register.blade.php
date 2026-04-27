<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register - SCH Care</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('templates/css/register.css') }}">
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
                <p>Sudah punya akun? <a href="{{ route('login') }}"
                        style="color:var(--red-vivid);font-weight:600;text-decoration:none;">Masuk di sini</a></p>
            </div>

            @if ($errors->any())
                <div class="alert-auth error" ...>
            @endif

            <form action="{{ route('register') }}" method="POST">

                @csrf

                <!-- Nama -->
                <div class="field-group">
                    <label class="field-label" for="name">Nama Lengkap</label>
                    <div class="field-wrap">
                        <i class="bi bi-person field-icon"></i>
                        <input type="text" id="name" name="name" class="field-input"
                            placeholder="Nama sesuai identitas" autocomplete="name" value="{{ old('name') }}"
                            required />
                    </div>
                </div>

                <!-- Email -->
                <div class="field-group">
                    <label class="field-label" for="email">Alamat Email</label>
                    <div class="field-wrap">
                        <i class="bi bi-envelope field-icon"></i>
                        <input type="email" id="email" name="email" class="field-input"
                            placeholder="nama@sekolah.sch.id" autocomplete="email" value="{{ old('email') }}"
                            required />
                    </div>
                </div>

                <!-- Password -->
                <div class="field-group">
                    <label class="field-label" for="password">Password</label>
                    <div class="field-wrap">
                        <i class="bi bi-lock field-icon"></i>
                        <input type="password" id="password" name="password" class="field-input has-eye"
                            placeholder="Minimal 8 karakter" autocomplete="new-password"
                            oninput="checkStrength(this.value)" required />
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
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="field-input has-eye" placeholder="Ulangi password" autocomplete="new-password"
                            oninput="checkConfirm()" required />
                        <button type="button" class="btn-eye" onclick="togglePassword('password_confirmation', this)">
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
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye-slash';
            }
        }

        /* ─── Password strength ──────────── */
        function checkStrength(val) {
            const segs = [1, 2, 3, 4].map(i => document.getElementById('seg' + i));
            const label = document.getElementById('strengthLabel');
            const reset = () => segs.forEach(s => {
                s.className = 'strength-segment';
            });

            if (!val) {
                reset();
                label.textContent = '';
                return;
            }

            let score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            reset();
            if (score <= 1) {
                segs[0].classList.add('weak');
                label.textContent = 'Lemah';
                label.className = 'strength-label weak';
            } else if (score === 2) {
                segs[0].classList.add('medium');
                segs[1].classList.add('medium');
                label.textContent = 'Sedang';
                label.className = 'strength-label medium';
            } else if (score === 3) {
                [0, 1, 2].forEach(i => segs[i].classList.add('strong'));
                label.textContent = 'Kuat';
                label.className = 'strength-label strong';
            } else {
                segs.forEach(s => s.classList.add('strong'));
                label.textContent = 'Sangat Kuat';
                label.className = 'strength-label strong';
            }
        }

        /* ─── Confirm password check ─────── */
        function checkConfirm() {
            const pw = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            const errEl = document.getElementById('confirmError');
            const input = document.getElementById('password_confirmation');

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
