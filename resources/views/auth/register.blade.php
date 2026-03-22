<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SCH Care</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #f0f2f5; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            height: 100vh; 
        }
        .register-card { 
            max-width: 450px; /* Sedikit lebih lebar dari login */ 
            width: 100%; 
            border: none; 
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); 
        }
        .register-card .card-header { 
            background-color: #198754; /* Warna hijau untuk pendaftaran baru */
            color: white; 
            text-align: center; 
            font-weight: bold; 
        }
    </style>
</head>
<body>

<div class="card register-card">
    <div class="card-header">
        <h4 class="mb-0">Daftar Akun Baru</h4>
        <small>Khusus Siswa</small>
    </div>
    <div class="card-body p-4">
        
        <!-- Notifikasi Error -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" placeholder="Nama siswa" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="email@example.com" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success">Daftar Sekarang</button>
            </div>
        </form>

        <hr>
        <p class="text-center mb-0">
            Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>