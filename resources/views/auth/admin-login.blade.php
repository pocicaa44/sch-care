<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - SCH Care</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      body {
        height: 100dvh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #f2f2f2;
        flex-direction: column;
      }

      .card {
        width: 450px;
      }

      .alert {
        max-width: 450px;
      }
    </style>
</head>
<body>

<div class="alert alert-warning">
  <strong>Akses Khusus Admin!</strong> 
   Area ini hanya untuk administrator yang berwenang.
</div>

<div class="card shadow-sm login-card">
    <div class="card-header text-center">
        <h4 class="mb-0 fw-bolder">SCH Care</h4>
        <small>Panel Administrator</small>
    </div>
    <div class="card-body p-4">

        @if (session('error'))
        <div class="alert alert-danger mb-3">
            {{ session('error') }}
        </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email Admin</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-dark">Login</button>
            </div>
        </form>
        
        <hr>
        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary w-100">&larr; Kembali ke Halaman Siswa</a>
    </div>
</div>

</body>
</html>