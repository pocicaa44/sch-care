<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - SCH Care</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #343a40; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .login-card { max-width: 400px; width: 100%; border: none; box-shadow: 0 4px 8px rgba(0,0,0,0.5); }
        .login-card .card-header { background-color: #212529; color: white; text-align: center; font-weight: bold; border-bottom: 2px solid #ffcc00; }
        .login-card .card-body { background-color: #fff; }
    </style>
</head>
<body>

<div class="card login-card">
    <div class="card-header">
        <h4 class="mb-0">SCH Care</h4>
        <small>Panel Administrator</small>
    </div>
    <div class="card-body p-4">
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email Admin</label>
                <input type="email" name="email" class="form-control" placeholder="admin@schcare.com" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-dark">Login sebagai Admin</button>
            </div>
        </form>
        
        <hr>
        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary w-100">&larr; Kembali ke Halaman Siswa</a>
    </div>
</div>

</body>
</html>