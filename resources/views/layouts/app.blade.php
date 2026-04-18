<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair:ital,opsz,wght@0,5..1200,300..900;1,5..1200,300..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('templates/css/app.css') }}">
    @stack('styles')
</head>

<body>
    {{-- sidebar --}}
    <aside class="sidebar" id="desktopSidebar">
        <div class="sidebar-brand">
            <div class="brand-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
            <h1>SCH CARE</h1>
            <span>Sistem Manajemen Laporan</span>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">Navigasi</div>
            @auth
                @if (Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                        class="{{ request()->routeIs('admin.dashboard') ? 'nav-link active' : 'nav-link' }}">
                        <i class="bi bi-grid-1x2-fill"></i> Reports
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="{{ request()->routeIs('admin.users.index') ? 'nav-link active' : 'nav-link' }}">
                        <i class="bi bi-person-fill"></i> Users
                    </a>
                @else
                    <a href="{{ route('siswa.dashboard') }}"
                        class="{{ request()->routeIs('siswa.dashboard') ? 'nav-link active' : 'nav-link' }}">
                        <i class="bi bi-grid-1x2-fill"></i> Laporan
                    </a>
                    <a href="{{ route('siswa.create') }}"
                        class="{{ request()->routeIs('siswa.create') ? 'nav-link active' : 'nav-link' }}">
                        <i class="bi bi-plus-square-fill"></i> Tambah Laporan
                    </a>
                    <a href="{{ route('siswa.settings.edit') }}"
                        class="{{ request()->routeIs('siswa.settings.edit') ? 'nav-link active' : 'nav-link' }}">
                        <i class="bi bi-gear-fill"></i> Pengaturan
                    </a>
                @endif
            @endauth

            <div class="nav-divider"></div>
            <div class="nav-label">Akun</div>
            <form method="POST" action="{{ route('logout') }}" class="">
                @csrf
                <button type="submit" class="nav-link w-100">
                    <i class="bi bi-box-arrow-left"></i>
                    Logout
                </button>
            </form>

        </nav>

        <div class="sidebar-footer">
            <div class="user-chip">
                <div class="user-avatar">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="user-info">
                    <div class="name">
                        @auth
                            {{ Auth::user()->name }}
                        </div>
                        <div class="role">
                            @if (Auth::user()->role === 'admin')
                                Administrator
                            @else
                                {{ auth()->user()->email }}
                            @endif
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </aside>

    {{-- offcanvas --}}
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileNav" aria-labelledby="mobileNavLabel">
        <div class="offcanvas-header">
            <div class="d-flex align-items-center gap-2">
                <div class="brand-icon"
                    style="width:36px;height:36px;background:var(--red-vivid);border-radius:9px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:15px;flex-shrink:0;">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>
                <div>
                    <div
                        style="font-family:'Playfair Display',serif;font-weight:700;font-size:1.1rem;color:var(--gray-800);">
                        SCH CARE</div>
                    <div style="font-size:.68rem;color:var(--gray-400);letter-spacing:.05em;text-transform:uppercase;">
                        Sistem Manajemen Laporan</div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <nav class="sidebar-nav">
                <div class="nav-label">Navigasi</div>
                @auth
                    @if (Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="{{ request()->routeIs('admin.dashboard') ? 'nav-link active' : 'nav-link' }}">
                            <i class="bi bi-grid-1x2-fill"></i> Reports
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                            class="{{ request()->routeIs('admin.users.index') ? 'nav-link active' : 'nav-link' }}">
                            <i class="bi bi-person-fill"></i> Users
                        </a>
                    @else
                        <a href="{{ route('siswa.dashboard') }}"
                            class="{{ request()->routeIs('siswa.dashboard') ? 'nav-link active' : 'nav-link' }}">
                            <i class="bi bi-grid-1x2-fill"></i> Laporan
                        </a>
                        <a href="{{ route('siswa.create') }}"
                            class="{{ request()->routeIs('siswa.create') ? 'nav-link active' : 'nav-link' }}">
                            <i class="bi bi-plus-square-fill"></i> Tambah Laporan
                        </a>
                        <a href="{{ route('siswa.settings.edit') }}"
                            class="{{ request()->routeIs('siswa.settings.edit') ? 'nav-link active' : 'nav-link' }}">
                            <i class="bi bi-gear-fill"></i> Pengaturan
                        </a>
                    @endif
                @endauth
                <div class="nav-divider"></div>
                <div class="nav-label">Akun</div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link logout">
                        <i class="bi bi-box-arrow-left"></i>
                        Logout
                    </button>
                </form>
            </nav>
            <div class="sidebar-footer mt-auto" style="position:absolute;bottom:0;left:0;right:0;">
                <div class="user-chip">
                    <div class="user-avatar">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="user-info">
                        <div class="name">
                            @auth
                                {{ Auth::user()->name }}
                            </div>
                            <div class="role">
                                @if (Auth::user()->role === 'admin')
                                    Administrator
                                @else
                                    {{ auth()->user()->email }}
                                @endif
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- top bar --}}
    <div class="main-content">
        <header class="topbar">
            <div class="topbar-title">
                <h2>
                    @if (request()->routeIs('admin.dashboard') || request()->routeIs('siswa.dashboard'))
                        Main Menu
                    @elseif (request()->routeIs('siswa.create'))
                        Tambah Laporan
                    @elseif (request()->routeIs('siswa.show') || request()->routeIs('admin.show'))
                        Detail Laporan
                    @elseif (request()->routeIs('siswa.settings.edit'))
                        Pengaturan
                    @elseif (request()->routeIs('siswa.edit'))
                        Edit Laporan
                    @elseif (request()->routeIs('admin.users.index'))
                        List User Account
                    @endif
                </h2>
                <p>{{ now('Asia/Jakarta')->format('d M Y') }}</p>
            </div>
            <div class="topbar-actions">
                <button class="btn-topbar d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#mobileNav"
                    aria-controls="mobileNav">
                    <i class="bi bi-list"></i>
                </button>
                @if (auth()->user()->role !== 'admin' && request()->routeIs('siswa.dashboard'))
                    <a href="{{ route('siswa.create') }}" class="btn-tambah d-none d-md-inline-flex">
                        <i class="bi bi-plus-lg"></i> Tambah Laporan
                    </a>
                @endif
            </div>
        </header>

        @yield('content')
    </div>

    @if (request()->routeIs('siswa.dashboard'))
        <a class="fab" href="{{ route('siswa.create') }}" aria-label="Tambah Laporan">
            <i class="bi bi-plus-lg"></i>
        </a>
    @elseif (request()->routeIs('siswa.show'))
        <a class="fab" href="{{ route('siswa.edit', $report->id) }}" aria-label="Edit Laporan">
            <i class="bi bi-pencil-square"></i>
        </a>
    @endif

    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>

    <script>
        document.getElementById('mobileNav')
            ?.querySelectorAll('.nav-link:not(.logout)')
            .forEach(link => {
                link.addEventListener('click', function(e) {
                    const offcanvasEl = document.getElementById('mobileNav');
                    const instance = bootstrap.Offcanvas.getInstance(offcanvasEl);

                    if (instance) {
                        e.preventDefault();
                        const href = this.getAttribute('href');
                        instance.hide();
                        offcanvasEl.addEventListener(
                            'hidden.bs.offcanvas',
                            () => {
                                window.location.href = href;
                            }, {
                                once: true
                            }
                        );
                    }
                });
            });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: "success",
                title: "BERHASIL",
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000
            });
        @elseif (session('error'))
            Swal.fire({
                icon: "error",
                title: "GAGAL!",
                text: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 2000
            });
        @endif
    </script>
</body>

</html>
