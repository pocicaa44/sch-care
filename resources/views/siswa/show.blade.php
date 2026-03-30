@extends('layouts.app')

@section('title', 'Detail Laporan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('templates/css/show.css') }}">
    <link rel="stylesheet" href="{{ asset('templates/css/create.css') }}">
    <link rel="stylesheet" href="{{ asset('templates/css/dashboard.css') }}">

@endpush

@section('content')
    <main class="page-body">
      
        <div class="row g-4">

            <!-- ═══ KOLOM KIRI (konten utama) ═══════════════════ -->
            <div class="col-12 col-lg-8">

                <!-- CARD UTAMA -->
                <div class="detail-card fade-up">
                    <div
                        class="detail-accent 
                        @if ($report->status == 'pending') pending
                        @elseif ($report->status == 'diproses') diproses
                        @elseif ($report->status == 'selesai') selesai 
                        @else ditolak @endif
                        ">
                    </div>

                    <div class="detail-card-header">
                        <div class="report-id-row">
                            <span class="report-id-tag">{{ $report->id }}</span>
                            <span
                                class="badge-status 
                                @if ($report->status == 'pending') badge-pending
                                @elseif ($report->status == 'diproses') badge-diproses
                                @elseif ($report->status == 'selesai') badge-selesai
                                @else badge-ditolak @endif"><i
                                    class="bi 
                                        @if ($report->status == 'pending') bi-clock
                                        @elseif ($report->status == 'diproses') bi-arrow-repeat
                                        @elseif ($report->status == 'selesai') bi-check-circle
                                        @else bi-x-circle @endif
                                        me-1"
                                    ></i> {{ ucfirst($report->status) }}</span>
                        </div>
                        <h1 class="detail-title">{{ $report->title }}</h1>
                        <div class="detail-meta-row">
                            <span class="meta-chip"><i class="bi bi-calendar3"></i>
                                {{ $report->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</span>
                            <span class="meta-chip"><i class="bi bi-geo-alt-fill"></i> {{ $report->location }}</span>
                        </div>
                    </div>

                    <!-- DESKRIPSI -->
                    <div class="detail-section">
                        <div class="section-label"><i class="bi bi-align-left"></i> Deskripsi Laporan</div>
                        <p class="deskripsi-text">{{ $report->description }}</p>
                    </div>

                    <!-- LOKASI -->
                    <div class="detail-section">
                        <div class="section-label"><i class="bi bi-geo-alt-fill"></i> Lokasi Kejadian</div>
                        <div class="location-box">
                            <div class="loc-icon"><i class="bi bi-map-fill"></i></div>
                            <div class="loc-text">
                                <strong>Lokasi</strong>
                                {{ $report->location }}
                            </div>
                        </div>
                    </div>

                    <!-- FOTO BUKTI -->
                    <div class="detail-section">
                        @if ($report->images->count() > 0)
                            <div class="section-label"><i class="bi bi-images"></i> Foto Bukti</div>
                            <div class="photo-gallery">
                                @foreach ($report->images as $img)
                                    <div class="photo-thumb" onclick="openLightbox(0)">
                                        <img src="{{ asset('storage/' . $img->path) }}" />
                                        <div class="photo-overlay"><i class="bi bi-zoom-in"></i></div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <span>Tidak Ada Bukti Foto</span>
                        @endif
                    </div>

                    <!-- IDENTITAS PELAPOR -->
                    <div class="detail-section">
                        <div class="section-label"><i class="bi bi-person-fill"></i> Identitas Pelapor</div>
                        <div class="pelapor-card">
                            <div class="pelapor-avatar">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div>
                                <div class="pelapor-name">
                                    @if ($report->is_anonymous)
                                        Anonim
                                    @else
                                        {{ $report->user->name ?? 'Akun Terhapus' }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div><!-- /.detail-card -->

                <!-- ─── KOMENTAR ADMIN ─────────────────────────── -->
                <div class="detail-card fade-up">
                    <div class="detail-section" style="border-bottom:none;">
                        <div class="section-label"><i class="bi bi-chat-dots-fill"></i> Komentar & Tindak Lanjut Admin
                        </div>

                        <div class="comment-list">

                            <!-- Komentar 1 (admin) -->
                            @forelse ($report->comments as $comment)
                                <div class="comment-item">
                                    <div class="comment-avatar admin">AD</div>
                                    <div class="comment-bubble admin-bubble">
                                        <div class="comment-meta">
                                            <span class="comment-author">{{ $comment->user->name }}</span>
                                            <span class="comment-role admin">Admin</span>
                                            <span class="comment-time">{{ $comment->created_at->format('H:i d/m') }}</span>
                                        </div>
                                        <div class="comment-text">
                                            {{ $comment->content }}
                                        </div>
                                    </div>
                                </div>

                            @empty
                                <div style="text-align:center;padding:30px 0;color:var(--gray-400);">
                                    <i class="bi bi-chat-slash" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                                    <span style="font-size:.85rem;">Belum ada komentar dari admin</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div><!-- /.komentar card -->


                <!-- TOMBOL AKSI BAWAH -->
                <div class="d-flex justify-content-between mt-2 fade-up">
                    <a href="{{ route(auth()->user()->role === 'admin' ? 'admin.dashboard' : 'siswa.dashboard') }}"
                        class="btn-back"><i class="bi bi-arrow-left"></i>
                        Kembali</a>
                    <form
                        action="{{ route(auth()->user()->role === 'admin' ? 'admin.destroy' : 'siswa.destroy', $report->id) }}"
                        method="POST" class="{{ $report->status == 'diproses' ? 'd-none' : '' }}">
                        @csrf @method('DELETE')
                        <button class="btn-hapus-detail ms-auto" onclick="confirmHapus()">
                            <i class="bi bi-trash3"></i> Hapus Laporan
                        </button>
                    </form>
                </div>

            </div><!-- /.col kiri -->

            <!-- ═══ KOLOM KANAN (sidebar info) ══════════════════ -->
            <div class="col-12 col-lg-4">

                <!-- Info Status -->
                {{-- <div class="info-sidebar-card fade-up">
                              <div class="iscard-header"><i class="bi bi-info-circle-fill"></i> Informasi Laporan</div>
                        <div class="iscard-body">
                          <div class="info-row">
                            <span class="label">ID Laporan</span>
                            <span class="value" style="font-family:monospace;">#RPT-2026-001</span>
                          </div>
                          <div class="info-row">
                            <span class="label">Status</span>
                            <span class="badge-status badge-diproses" style="font-size:.7rem;"><i
                              class="bi bi-arrow-repeat"></i> Diproses</span>
                            </div>
                            <div class="info-row">
                              <span class="label">Tanggal Kirim</span>
                              <span class="value">17 Mar 2026</span>
                            </div>
                            <div class="info-row">
                              <span class="label">Terakhir Update</span>
                              <span class="value">19 Mar 2026</span>
                            </div>
                            <div class="info-row">
                              <span class="label">Foto Dilampirkan</span>
                              <span class="value">3 foto</span>
                            </div>
                            <div class="info-row">
                              <span class="label">Mode Pengiriman</span>
                              <span class="value">Identitas Diketahui</span>
                            </div>
                          </div>
                        </div>
                     --}}
                <!-- Timeline Status -->
                {{-- <div class="info-sidebar-card fade-up">
                        <div class="iscard-header"><i class="bi bi-clock-history"></i> Riwayat Status</div>
                        <div class="iscard-body">
                          <div class="timeline">
                            
                            <div class="timeline-item">
                              <div class="timeline-dot done"></div>
                              <div class="timeline-label">Laporan Dikirim</div>
                              <div class="timeline-date">17 Mar 2026, 09:42</div>
                            </div>
                            
                                <div class="timeline-item">
                                    <div class="timeline-dot done"></div>
                                    <div class="timeline-label">Diterima Admin</div>
                                    <div class="timeline-date">18 Mar 2026, 08:15</div>
                                    <div class="timeline-note">Laporan diteruskan ke Dinas Sumber Daya Air Jakarta Utara.
                                    </div>
                                </div>

                                <div class="timeline-item">
                                    <div class="timeline-dot active"></div>
                                    <div class="timeline-label">Sedang Diproses</div>
                                    <div class="timeline-date">19 Mar 2026, 14:30</div>
                                    <div class="timeline-note">Tim survei sudah ke lokasi. Pengadaan pompa darurat sedang
                                        berjalan.</div>
                                </div>

                                <div class="timeline-item">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-label" style="color:var(--gray-400);font-weight:500;">Selesai
                                    </div>
                                    <div class="timeline-date" style="color:var(--gray-300);">Menunggu...</div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Bantuan -->
                    <div class="info-sidebar-card fade-up" style="border-color:var(--red-mid);">
                        <div class="iscard-header" style="background:var(--red-light);">
                            <i class="bi bi-question-circle-fill" style="color:var(--red-vivid);"></i>
                            <span style="color:var(--red-deep);">Butuh Bantuan?</span>
                        </div>
                        <div class="iscard-body">
                            <p style="font-size:.82rem;color:var(--gray-600);line-height:1.6;margin:0 0 10px;">
                                Jika laporan Anda tidak mendapat respons dalam 3 hari kerja, hubungi kami langsung.
                            </p>
                            <a href="#"
                                style="display:inline-flex;align-items:center;gap:6px;font-size:.8rem;font-weight:600;color:var(--red-vivid);text-decoration:none;">
                                <i class="bi bi-telephone-fill"></i> Hubungi Admin
                            </a>
                        </div>
                    </div> --}}

            </div><!-- /.col kanan -->

        </div><!-- /.row -->

    </main>

@endsection

@push('scripts')
    <script src="{{ asset('templates/js/show.js') }}"></script>
@endpush
