@extends('layouts.app')

@section('title', 'Detail Laporan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('templates/css/show.css') }}">
    <link rel="stylesheet" href="{{ asset('templates/css/create.css') }}">
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
                            <div class="section-label"><i class="bi bi-images"></i> Foto Bukti (3)</div>
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

                {{-- admin tulis komentar disini --}}
                @if (auth()->user()->role === 'admin' || $report->status == 'diproses')
                    <form action="{{ route('admin.comment', $report->id) }}" method="POST" class="mt-4">
                        @csrf

                        @if ($report->status == 'diproses')
                            <div class="section-label mt-2"><i class="bi bi-reply-fill"></i> Tulis Komentar</div>

                            <textarea name="content" class="input-custom @error('content') is-invalid @enderror" rows="3"
                                placeholder="Tulis tindak lanjut atau keterangan untuk pelapor..." maxlength="1000" required>{{ old('content') }}</textarea>

                            @error('isi')
                                <div style="font-size:.74rem;color:var(--red-vivid);margin-top:4px;">{{ $message }}
                                </div>
                            @enderror

                            <div class="d-flex justify-content-end mt-2">
                                <button type="submit" class="btn-submit" style="padding:9px 22px;font-size:.84rem;">
                                    <i class="bi bi-send-fill"></i> Kirim Komentar
                                </button>
                            </div>
                        @else
                            <span>Can't post comments</span>
                        @endif
                    </form>

                @endif

                {{-- admin ubah status disini --}}
                @if (auth()->user()->role === 'admin')
                    <div class="info-row" style="flex-direction:column;gap:8px;align-items:stretch;">
                        <span class="label mb-1">Ubah Status</span>

                        @php
                            $nextStatuses = [];
                            if ($report->status === 'pending' || $report->status === 'diproses') {
                                $nextStatuses = ['selesai' => 'Tandai Selesai', 'ditolak' => 'Tolak Laporan'];
                            }
                        @endphp
                        @if (count($nextStatuses) > 0)
                            <form action="{{ route('admin.status', $report->id) }}" method="POST">
                                @csrf
                                <div class="d-flex gap-2">
                                    @foreach ($nextStatuses as $value => $label)
                                        <button type="submit" name="status" value="{{ $value }}"
                                            class="btn-status-action {{ $value === 'selesai' ? 'selesai' : 'ditolak' }}">
                                            <i
                                                class="bi {{ $value === 'selesai' ? 'bi-check-circle-fill' : 'x-circle-fill' }}"></i>
                                            {{ $label }}
                                        </button>
                                    @endforeach
                                </div>
                            </form>
                        @else
                            <span>Cannot change status</span>
                        @endif
                    </div>
                @endif
            </div><!-- /.col kanan -->

        </div><!-- /.row -->

    </main>

@endsection

@push('scripts')
    <script src="{{ asset('templates/js/show.js') }}"></script>
@endpush
