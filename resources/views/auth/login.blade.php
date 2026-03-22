<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCH Care - Login Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .login-card { max-width: 400px; width: 100%; border: none; box-shadow: 0 4px 8px rgba(0,0,0,0.1);}
        .login-card .card-header { background-color: #0d6efd; color: white; text-align: center; font-weight: bold; }
        .card {
             border-radius: none;
        }
    </style>
</head>
<body>

<div class="card login-card">
    <div class="card-header">
        <h4 class="mb-0">SCH Care</h4>
        <small>Login Siswa</small>
    </div>
    <div class="card-body p-4">
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

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
                <label class="form-label">Email Siswa</label>
                <input type="email" name="email" class="form-control" placeholder="masukkan@email.com" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>

        <hr>
        <p class="text-center mb-0">
            Belum punya akun? <a href="{{ route('register') }}">Register di sini</a>
        </p>
    </div>
</div>

</body>
</html>