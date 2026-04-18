@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
    <main class="page-body">
        <div class="row g-4">
            <div class="col-12 col-xl-8">

                <!-- ─── PROFIL ──────────────────────────────────── -->
                <div class="settings-card fade-up">
                    <div class="settings-card-header">
                        <div class="settings-header-icon gray"><i class="bi bi-person-fill"></i></div>
                        <div>
                            <h3>Informasi Profil</h3>
                            <p>Nama dan email yang terdaftar di akun kamu</p>
                        </div>
                    </div>
                    <div class="settings-card-body">

                        <div class="profile-avatar-row">
                            <div class="profile-avatar-lg">{{ substr(auth()->user()->name, 0, 1) }}</div>
                            <div class="profile-info">
                                <div class="pname">{{ auth()->user()->name }}</div>
                                <div class="pemail">{{ auth()->user()->email }}</div>
                                <div class="prole"><i class="bi bi-mortarboard-fill"></i> {{ auth()->user()->role }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ─── AUTO DELETE ─────────────────────────────── -->
                <div class="settings-card fade-up">
                    <div class="settings-card-header">
                        <div class="settings-header-icon red"><i class="bi bi-trash3-fill"></i></div>
                        <div>
                            <h3>Hapus Otomatis Laporan</h3>
                            <p>Laporan yang sudah selesai atau ditolak akan terhapus secara otomatis</p>
                        </div>
                    </div>
                    <div class="settings-card-body">

                        <!-- Pilihan hari -->
                        <form action="{{ route('siswa.settings.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="section-label"><i class="bi bi-calendar-week"></i> Hapus setelah</div>

                            <div class="radio-group" id="radioGroup">

                                <label class="radio-option">
                                    <input type="radio" name="auto_delete_days" value="3"
                                        {{ $user->auto_delete_days == 3 ? 'checked' : '' }} />
                                    <div class="radio-card">
                                        <div class="radio-dot"></div>
                                        <div class="radio-content">
                                            <div class="radio-title">3 Hari</div>
                                            <div class="radio-desc">Laporan dihapus 3 hari setelah status berubah</div>
                                        </div>
                                    </div>
                                </label>

                                <label class="radio-option">
                                    <input type="radio" name="auto_delete_days" value="7"
                                        {{ $user->auto_delete_days == 7 ? 'checked' : '' }} />
                                    <div class="radio-card">
                                        <div class="radio-dot"></div>
                                        <div class="radio-content">
                                            <div class="radio-title">7 Hari</div>
                                            <div class="radio-desc">Laporan dihapus 7 hari setelah status berubah</div>
                                        </div>
                                    </div>
                                </label>

                                <label class="radio-option">
                                    <input type="radio" name="auto_delete_days" value="14"
                                        {{ $user->auto_delete_days == 14 ? 'checked' : '' }} />
                                    <div class="radio-card">
                                        <div class="radio-dot"></div>
                                        <div class="radio-content">
                                            <div class="radio-title">14 Hari</div>
                                            <div class="radio-desc">Laporan dihapus 14 hari setelah status berubah</div>
                                        </div>
                                    </div>
                                </label>

                                <label class="radio-option">
                                    <input type="radio" name="auto_delete_days" value="30"
                                        {{ $user->auto_delete_days == 30 ? 'checked' : '' }} />
                                    <div class="radio-card">
                                        <div class="radio-dot"></div>
                                        <div class="radio-content">
                                            <div class="radio-title">30 Hari</div>
                                            <div class="radio-desc">Laporan dihapus 30 hari setelah status berubah</div>
                                        </div>
                                        <span class="radio-badge recommended">Default</span>
                                    </div>
                                </label>

                            </div>
                            <div class="settings-card-footer mt-3 pt-2">
                                <button type="submit" class="btn-save ms-auto" onclick="showToast()">
                                    <i class="bi bi-check-lg"></i> Simpan Pengaturan
                                </button>
                            </div>
                        </form>

                        <!-- Info note -->
                        <div id="autoDeleteNote" style="display:none;margin-top:16px;">
                            <div
                                style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:12px 16px;display:flex;align-items:flex-start;gap:10px;">
                                <i class="bi bi-info-circle-fill"
                                    style="color:#2563eb;font-size:.9rem;margin-top:1px;flex-shrink:0;"></i>
                                <div style="font-size:.8rem;color:#1e40af;line-height:1.55;">
                                    Laporan yang terhapus otomatis <strong>tidak dapat dipulihkan</strong>. Pastikan kamu
                                    sudah tidak membutuhkan laporan tersebut sebelum fitur ini aktif.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ─── DANGER ZONE ──────────────────────────────── -->
                <div class="settings-card fade-up" style="border-color:#fca5a5;">
                    <div class="settings-card-header" style="background:#fff8f8;border-bottom-color:#fee2e2;">
                        <div class="settings-header-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div>
                        <div>
                            <h3 style="color:var(--red-deep);">Zona Berbahaya</h3>
                            <p>Tindakan berikut bersifat permanen dan tidak dapat dibatalkan</p>
                        </div>
                    </div>
                    <div class="settings-card-body">
                        <div class="danger-zone">
                            <div class="danger-zone-header">
                                <i class="bi bi-shield-exclamation"></i>
                                <span>Tindakan Permanen</span>
                            </div>
                            <div class="danger-item">
                                <div class="danger-item-text">
                                    <div class="dtitle">Hapus Akun</div>
                                    <div class="ddesc">Akun dan seluruh data kamu akan dihapus secara permanen</div>
                                </div>
                                <button class="btn btn-danger px-3 text-white shadow rounded-3" data-bs-toggle="modal" data-bs-target="#confirmPassword">
                                    <i class="bi bi-person-x me-1"></i> Hapus Akun
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="confirmPassword" aria-labelledby="confirmPassworLabel" aria-hidden="true"
                    tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5">Confirm Password</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('siswa.settings.destroy') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <label for="password" class="text-secondary mb-2">Masukkan password untuk
                                        konfirmasi</label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="password"
                                            class="form-control @error('password') is-invalid @enderror" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="bi bi-eye" id="eyeIcon"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-hapus" data-bs-dismiss="modal">Keluar</button>
                                <button type="button" class="btn-detail">Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    @endsection

    @push('scripts')
        <script>
            const togglePassword = document.querySelector('#togglePassword');
            const passwordField = document.querySelector('#password');
            const eyeIcon = document.querySelector('#eyeIcon');

            togglePassword.addEventListener('click', function() {
                // Toggle the type attribute
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);

                // Toggle the eye / eye-slash icon
                eyeIcon.classList.toggle('bi-eye');
                eyeIcon.classList.toggle('bi-eye-slash');
            });
        </script>
    @endpush
