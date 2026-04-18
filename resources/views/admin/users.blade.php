@extends('layouts.admin')

@section('pageTitle', 'Manajemen User')

@section('content')
    <!-- ========================================
         Tabel Daftar User
         ======================================== -->
    <div class="card-dark" style="cursor:default;" onmouseover="this.style.transform='none'" onmouseout="this.style.transform='none'">
        <div class="card-body">
            {{-- Header --}}
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-3">
                <div>
                    <h5 class="mb-1" style="font-size:16px; font-weight:600;">Daftar User (Siswa)</h5>
                    <p style="font-size:13px; color:var(--text-muted); margin:0;">
                        Menampilkan semua akun siswa yang terdaftar
                    </p>
                </div>
            </div>

            {{-- Pencarian --}}
            <form method="GET" action="{{ request()->fullUrlWithoutQuery(['search']) }}" class="filter-bar mb-3">
                <div class="search-input-wrapper">
                    <i class="bi bi-search"></i>
                    <input type="text"
                           name="search"
                           class="form-control form-control-dark"
                           placeholder="Cari nama atau email siswa..."
                           value="{{ request('search') }}"
                           autocomplete="off">
                </div>

                @if (request('search'))
                    <a href="{{ request()->fullUrlWithoutQuery(['search']) }}"
                       class="btn-action btn-detail"
                       style="white-space:nowrap;">
                        <i class="bi bi-x-lg"></i> Reset
                    </a>
                @endif
            </form>

            {{-- Tabel --}}
            <div class="table-responsive">
                @if ($users->count() > 0)
                    <table class="table-dark-custom">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Auto Delete</th>
                                <th>Status</th>
                                <th>Tanggal Registrasi</th>
                                <th style="text-align:center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <span style="color:var(--text-muted); font-weight:500;">#{{ $user->id }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span style="font-weight:500;">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td style="color:var(--text-secondary);">
                                        {{ $user->email }}
                                    </td>
                                    <td>
                                        @if ($user->auto_delete_days !== null)
                                            <span style="font-weight:600; color:var(--warning);">
                                                {{ $user->auto_delete_days }} hari
                                            </span>
                                        @else
                                            <span style="color:var(--text-muted);">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->deleted_at)
                                            <span class="badge-status badge-dihapus-user">
                                                <span class="dot"></span>
                                                Dihapus
                                            </span>
                                        @else
                                            <span class="badge-status badge-aktif">
                                                <span class="dot"></span>
                                                Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td style="color:var(--text-secondary); white-space:nowrap;">
                                        {{ $user->created_at->format('d M Y') }}
                                    </td>
                                    <td style="text-align:center; white-space:nowrap;">
                                        @unless ($user->deleted_at)
                                            <button type="button"
                                                    class="btn-action btn-delete"
                                                    title="Hapus Akun"
                                                    onclick="confirmDelete(
                                                        '{{ route('admin.users.destroy', $user->id) }}',
                                                        'Hapus akun siswa "{{ $user->name }}"? Akun akan di-soft delete.'
                                                    )">
                                                <i class="bi bi-person-x"></i>
                                                <span class="d-none d-md-inline">Hapus</span>
                                            </button>
                                        @else
                                            <span style="color:var(--text-muted); font-size:12px; font-style:italic;">
                                                —
                                            </span>
                                        @endunless
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <i class="bi bi-people d-block"></i>
                        <p>Tidak ada user ditemukan.</p>
                    </div>
                @endif
            </div>

            {{-- Paginasi --}}
            @if ($users->hasPages())
                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }}
                        dari {{ $users->total() }} user
                    </div>
                    <div>
                        {{ $users->onEachSide(1)->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Terapkan class pagination-dark ke elemen pagination Laravel
        document.querySelectorAll('.pagination').forEach(el => {
            el.classList.remove('pagination');
            el.classList.add('pagination-dark');
        });

        // Bersihkan HTML entities pada pagination
        document.querySelectorAll('.pagination-dark a, .pagination-dark span').forEach(el => {
            el.innerHTML = el.innerHTML.replace(/&laquo;/g, '‹').replace(/&raquo;/g, '›');
        });
    </script>
@endpush