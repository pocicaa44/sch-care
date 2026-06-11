<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - SCH Care</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('templates/css/login.css') }}">
    <style>
        .brand-section span {
            text-align: center;

        }
    </style>
</head>

<body style="height: 100dvh">

    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-3 bg-body-tertiary h-100 d-none d-md-flex p-5">
                <div class="brand-section">
                    <img src="{{ asset('images/schcare_logo.svg')}}" alt="logo" width="100%" height="250">
                    <p class="fs-6 text-center text-uppercase text-body-tertiary">Sistem Manajemen Laporan Sekolah</p>
                </div>
            </div>
    
            <div class="col-12 col-md-9 h-100">
                <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center">
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
                </div>
            </div>
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
