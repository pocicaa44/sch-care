@extends('layouts.admin')

@section('pageTitle', 'Detail Laporan' . $report->id)

@push('styles')
<style>
    /* ============================================
       LIGHTBOX — Fullscreen Overlay
       ============================================ */
    .lb-overlay .modal-dialog {
        max-width: 100vw;
        margin: 0;
        height: 100vh;
        height: 100dvh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    .lb-overlay .modal-content {
        background: transparent;
        border: none;
        box-shadow: none;
        width: 100%;
        height: 100vh;
        height: 100dvh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .lb-overlay .modal-backdrop {
        background: rgba(4, 4, 8, 0.92) !important;
        backdrop-filter: blur(28px) saturate(1.3);
        -webkit-backdrop-filter: blur(28px) saturate(1.3);
    }

    /* Gambar utama */
    .lb-img-wrap {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        max-width: 86vw;
        max-height: 76vh;
        z-index: 1;
    }

    .lb-img-wrap img {
        max-width: 86vw;
        max-height: 76vh;
        object-fit: contain;
        border-radius: 10px;
        box-shadow: 0 24px 64px rgba(0,0,0,0.55);
        opacity: 0;
        transform: scale(0.93);
        transition: opacity 0.35s ease, transform 0.4s cubic-bezier(.22,.68,0,1.08);
        user-select: none;
        -webkit-user-drag: none;
    }

    .lb-img-wrap img.lb-visible {
        opacity: 1;
        transform: scale(1);
    }

    /* Loading spinner */
    .lb-loader {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
        z-index: 0;
    }

    .lb-loader .spinner-border {
        width: 32px;
        height: 32px;
        border-width: 3px;
        color: var(--accent);
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .lb-loader .spinner-border.lb-loading {
        opacity: 1;
    }

    /* Tombol navigasi */
    .lb-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 1px solid rgba(255,255,255,0.1);
        background: rgba(255,255,255,0.06);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: #fff;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 5;
        transition: all 0.25s ease;
        outline: none;
    }

    .lb-nav-btn:hover {
        background: var(--accent);
        border-color: var(--accent);
        box-shadow: 0 0 24px rgba(232, 87, 42, 0.4);
        transform: translateY(-50%) scale(1.08);
    }

    .lb-nav-btn:active {
        transform: translateY(-50%) scale(0.94);
    }

    .lb-nav-btn.lb-disabled {
        opacity: 0.2;
        pointer-events: none;
        cursor: default;
    }

    .lb-prev { left: max(14px, 2.5vw); }
    .lb-next { right: max(14px, 2.5vw); }

    /* Tombol tutup */
    .lb-close-btn {
        position: absolute;
        top: 18px;
        right: 20px;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        border: 1px solid rgba(255,255,255,0.1);
        background: rgba(255,255,255,0.05);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: #fff;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 5;
        transition: all 0.25s ease;
        outline: none;
    }

    .lb-close-btn:hover {
        background: #c0392b;
        border-color: #c0392b;
        transform: rotate(90deg) scale(1.08);
    }

    /* Info bar bawah */
    .lb-info-bar {
        position: absolute;
        bottom: 22px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        align-items: center;
        gap: 16px;
        background: rgba(255,255,255,0.05);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 100px;
        padding: 9px 24px;
        z-index: 5;
        white-space: nowrap;
    }

    .lb-counter {
        font-size: 13px;
        color: var(--text-muted, #888);
        font-weight: 500;
    }

    .lb-counter .lb-cur {
        color: var(--accent, #e8572a);
        font-weight: 700;
    }

    .lb-info-divider {
        width: 1px;
        height: 16px;
        background: rgba(255,255,255,0.1);
    }

    .lb-info-label {
        font-size: 13px;
        color: rgba(255,255,255,0.75);
        font-weight: 500;
    }

    /* Thumbnail strip */
    .lb-thumbs {
        position: absolute;
        bottom: 72px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 6px;
        padding: 7px 12px;
        background: rgba(0,0,0,0.4);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.05);
        max-width: 75vw;
        overflow-x: auto;
        z-index: 5;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .lb-thumbs::-webkit-scrollbar { display: none; }

    .lb-thumb-item {
        width: 48px;
        height: 36px;
        border-radius: 5px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        opacity: 0.4;
        transition: all 0.25s ease;
        flex-shrink: 0;
    }

    .lb-thumb-item:hover { opacity: 0.75; }

    .lb-thumb-item.lb-active {
        border-color: var(--accent, #e8572a);
        opacity: 1;
        box-shadow: 0 0 10px rgba(232, 87, 42, 0.35);
    }

    .lb-thumb-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        pointer-events: none;
    }

    /* Hint navigasi keyboard (muncul singkat) */
    .lb-keyboard-hint {
        position: absolute;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 12px;
        color: rgba(255,255,255,0.3);
        z-index: 5;
        opacity: 0;
        transition: opacity 0.5s ease;
        pointer-events: none;
    }

    .lb-keyboard-hint.lb-show-hint {
        opacity: 1;
    }

    /* Responsif */
    @media (max-width: 576px) {
        .lb-nav-btn {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
        .lb-prev { left: 8px; }
        .lb-next { right: 8px; }
        .lb-img-wrap { max-width: 94vw; max-height: 68vh; }
        .lb-img-wrap img { max-width: 94vw; max-height: 68vh; }
        .lb-thumbs { max-width: 88vw; bottom: 66px; }
        .lb-thumb-item { width: 40px; height: 30px; }
        .lb-info-bar { padding: 7px 16px; gap: 10px; bottom: 16px; }
        .lb-keyboard-hint { display: none; }
    }

    /* Reduced motion */
    @media (prefers-reduced-motion: reduce) {
        .lb-img-wrap img { transition-duration: 0.01ms !important; }
        .lb-nav-btn, .lb-close-btn, .lb-thumb-item { transition-duration: 0.01ms !important; }
    }
</style>
@endpush

@section('content')
    {{-- Tombol Kembali --}}
    <a href="{{ route('admin.dashboard') }}" class="d-inline-flex align-items-center gap-2 text-decoration-none mb-4"
        style="color:var(--text-secondary); font-size:13.5px; font-weight:500; transition: color 0.2s ease;"
        onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-secondary)'">
        <i class="bi bi-arrow-left" style="font-size:16px;"></i>
        Kembali ke Daftar Laporan
    </a>

    <div class="row g-3">

        <!-- ========================================
                                             Kolom Kiri: Detail Laporan (8/12)
                                             ======================================== -->
        <div class="col-lg-8">

            {{-- Card 1: Judul & Status --}}
            <div class="card-dark mb-3" style="cursor:default;" onmouseover="this.style.transform='none'"
                onmouseout="this.style.transform='none'">
                <div class="card-body" style="padding:24px;">
                    <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-3">
                        <div>
                            <span
                                style="color:var(--text-muted); font-size:12.5px; font-weight:500; letter-spacing:0.5px; text-transform:uppercase;">
                                Laporan #{{ $report->id }}
                            </span>
                            <h4
                                style="font-size:20px; font-weight:700; margin:4px 0 0; color:var(--text-primary); line-height:1.4;">
                                {{ $report->title }}
                            </h4>
                        </div>

                        @php
                            $statusClass = match ($report->status) {
                                'pending' => 'badge-pending',
                                'diproses' => 'badge-diproses',
                                'selesai' => 'badge-selesai',
                                'ditolak' => 'badge-ditolak',
                                default => 'badge-dihapus-user',
                            };
                            $statusLabel = match ($report->status) {
                                'pending' => 'Pending',
                                'diproses' => 'Diproses',
                                'selesai' => 'Selesai',
                                'ditolak' => 'Ditolak',
                                default => 'Dihapus',
                            };
                        @endphp
                        <span class="badge-status {{ $statusClass }}" style="font-size:13px; padding:6px 14px;">
                            <span class="dot"></span>
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <div class="d-flex flex-wrap gap-4" style="font-size:13px; color:var(--text-secondary);">
                        <span>
                            <i class="bi bi-calendar3 me-1" style="color:var(--text-muted);"></i>
                            {{ $report->created_at->format('d M Y, H:i') }}
                        </span>
                        @if ($report->category)
                            <span>
                                <i class="bi bi-tag me-1" style="color:var(--text-muted);"></i>
                                {{ $report->category }}
                            </span>
                        @endif
                        @if ($report->updated_at->ne($report->created_at))
                            <span>
                                <i class="bi bi-pencil-square me-1" style="color:var(--text-muted);"></i>
                                Diperbarui {{ $report->updated_at->diffForHumans() }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Card 2: Deskripsi --}}
            <div class="card-dark mb-3" style="cursor:default;" onmouseover="this.style.transform='none'"
                onmouseout="this.style.transform='none'">
                <div class="card-body" style="padding:24px;">
                    <h6 style="font-size:14px; font-weight:600; color:var(--text-secondary); margin-bottom:14px;">
                        <i class="bi bi-file-text me-2" style="color:var(--accent);"></i>
                        Deskripsi
                    </h6>
                    <div style="color:var(--text-primary); font-size:14px; line-height:1.8; word-break:break-word;">
                        {{ $report->description }}
                    </div>
                </div>
            </div>

            {{-- Card 3: Lokasi --}}
            <div class="card-dark mb-3" style="cursor:default;" onmouseover="this.style.transform='none'"
                onmouseout="this.style.transform='none'">
                <div class="card-body" style="padding:24px;">
                    <h6 style="font-size:14px; font-weight:600; color:var(--text-secondary); margin-bottom:14px;">
                        <i class="bi bi-geo-alt-fill me-2" style="color:var(--accent);"></i>
                        Lokasi
                    </h6>
                    @if ($report->location)
                        <div class="d-flex align-items-start gap-3 p-3"
                            style="background:var(--bg-input); border:1px solid var(--border-color); border-radius:10px;">
                            <div
                                style="width:38px; height:38px; border-radius:10px; background:var(--accent-subtle); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <i class="bi bi-pin-map-fill" style="color:var(--accent); font-size:18px;"></i>
                            </div>
                            <div>
                                <div style="font-size:14px; font-weight:500; color:var(--text-primary); line-height:1.5;">
                                    {{ $report->location }}
                                </div>
                            </div>
                        </div>
                    @else
                        <span style="font-size:13.5px; color:var(--text-muted); font-style:italic;">
                            Tidak ada lokasi yang ditambahkan.
                        </span>
                    @endif
                </div>
            </div>

            {{-- Card 4: Foto Bukti --}}
            <div class="card-dark mb-3" style="cursor:default;" onmouseover="this.style.transform='none'"
                onmouseout="this.style.transform='none'">
                <div class="card-body" style="padding:24px;">
                    <h6 style="font-size:14px; font-weight:600; color:var(--text-secondary); margin-bottom:14px;">
                        <i class="bi bi-images me-2" style="color:var(--accent);"></i>
                        Foto Bukti
                        @if ($report->images && $report->images->count() > 0)
                            <span style="font-weight:400; color:var(--text-muted);">({{ $report->images->count() }})</span>
                        @endif
                    </h6>

                    @if ($report->images && $report->images->count() > 0)
                        @php
                            $imageUrls = $report->images->map(fn($img) => asset('storage/' . $img->path))->toArray();
                            $imageThumbs = $report->images->map(fn($img) => asset('storage/' . $img->path))->toArray();
                        @endphp

                        <div class="row g-3">
                            @foreach ($report->images as $index => $img)
                                <div class="col-6 col-sm-4 col-md-3">
                                    <div style="position:relative; border-radius:12px; overflow:hidden; border:1px solid var(--border-color); aspect-ratio:1; background:var(--bg-input); cursor:pointer; transition: border-color 0.2s ease, transform 0.2s ease;"
                                        onmouseover="this.style.borderColor='var(--accent)'; this.style.transform='scale(1.02)'"
                                        onmouseout="this.style.borderColor='var(--border-color)'; this.style.transform='scale(1)'"
                                        onclick="openLightbox({{ $index }})">
                                        <img src="{{ asset('storage/' . $img->path) }}"
                                            alt="Foto bukti {{ $index + 1 }}"
                                            style="width:100%; height:100%; object-fit:cover;" loading="lazy">
                                        <div style="position:absolute; inset:0; background:rgba(0,0,0,0.35); display:flex; align-items:center; justify-content:center; opacity:0; transition: opacity 0.2s ease;"
                                            onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                                            <div
                                                style="width:36px; height:36px; border-radius:50%; background:rgba(255,255,255,0.9); display:flex; align-items:center; justify-content:center;">
                                                <i class="bi bi-zoom-in"
                                                    style="color:var(--text-primary); font-size:16px;"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        style="font-size:11px; color:var(--text-muted); margin-top:6px; text-align:center;">
                                        Foto {{ $index + 1 }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        @php $imageUrls = []; $imageThumbs = []; @endphp
                        <div class="text-center py-4" style="color:var(--text-muted); font-size:13.5px;">
                            <i class="bi bi-image d-block mb-2" style="font-size:32px; opacity:0.3;"></i>
                            Tidak ada bukti foto.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Card: Form Tanggapan --}}
            <div class="card-dark mb-3" style="cursor: default;" onmouseover="this.style.transform='none'">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.response', $report->id) }}" enctype="multipart/form-data"
                        id="responseForm" multiple accept="image/*">
                        @csrf
                        <h5>Beri Tanggapan</h5>
                        <textarea name="content" id="adminResponse" class="form-control form-control-dark mb-3" rows="6"
                            placeholder="Tulis tanggapan Anda di sini..." required style="resize:vertical; min-height:120px;">{{ old('content') }}</textarea>

                        <button class="btn-action btn-detail" type="button" id="uploadTriggerBtn">
                            <i class="bi bi-paperclip me-1"></i>Upload file
                        </button>
                        <input type="file" name="attachments[]" id="fileInput" multiple accept="image/*"
                            class="d-none">

                        <div id="attachmentPreviews" class="mt-3 d-flex flex-column gap-2"></div>
                        <div id="attachmentCount" class="mt-2 small text-muted"></div>

                        <div class="mt-4">
                            <button type="submit" class="btn-confirm-delete" style="background:var(--accent);"
                                onmouseover="this.style.background='var(--accent-hover)'"
                                onmouseout="this.style.background='var(--accent)'">
                                <i class="bi bi-send me-1"></i> Kirim Tanggapan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Card 5: Tanggapan Admin --}}
            <div class="card-dark" style="cursor:default;" onmouseover="this.style.transform='none'"
                onmouseout="this.style.transform='none'">
                <div class="card-body" style="padding:24px;">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                        <h6 style="font-size:14px; font-weight:600; color:var(--text-secondary); margin:0;">
                            <i class="bi bi-reply-fill me-2" style="color:var(--accent);"></i>
                            Tanggapan Admin
                        </h6>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        @forelse ($report->responses as $comment)
                            <div
                                style="background:var(--bg-input); border:1px solid var(--border-color); border-radius:10px; padding:16px; position:relative;">
                                <div
                                    style="position:absolute; top:-8px; left:16px; background:var(--accent); color:#fff; font-size:10px; font-weight:600; padding:2px 8px; border-radius:4px; text-transform:uppercase; letter-spacing:0.5px;">
                                    Admin
                                </div>
                                <div
                                    style="color:var(--text-primary); font-size:14px; line-height:1.8; word-break:break-word; margin-top:4px;">
                                    {{ $comment->content }}
                                    @if ($comment->attachments && $comment->attachments->count())
                                        <div class="mt-2">
                                            <div class="d-flex flex-wrap gap-2 mt-1">
                                                @foreach ($comment->attachments as $attachment)
                                                    <img src="{{ asset('storage/' . $attachment->path) }}" width="100"
                                                        class="img-thumbnail rounded-2 p-0 m-0">
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @if ($comment->created_at)
                                    <div
                                        style="font-size:11.5px; color:var(--text-muted); margin-top:12px; padding-top:10px; border-top:1px solid var(--border-color);">
                                        <i class="bi bi-clock me-1"></i>
                                        Ditanggapi {{ $comment->created_at->format('d M Y, H:i') }}
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-4" style="color:var(--text-muted); font-size:13.5px;">
                                <i class="bi bi-chat-square-text d-block mb-2" style="font-size:28px; opacity:0.35;"></i>
                                @if (in_array($report->status, ['selesai', 'ditolak']))
                                    Laporan sudah ditutup ({{ $statusLabel }}).
                                @else
                                    Belum ada tanggapan dari admin.
                                @endif
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-4">
            {{-- Info Siswa --}}
            <div class="card-dark mb-3" style="cursor:default;" onmouseover="this.style.transform='none'"
                onmouseout="this.style.transform='none'">
                <div class="card-body" style="padding:24px;">
                    <h6 style="font-size:14px; font-weight:600; color:var(--text-secondary); margin-bottom:16px;">
                        <i class="bi bi-person-fill me-2" style="color:var(--accent);"></i>
                        Informasi Siswa
                    </h6>

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div
                            style="width:48px; height:48px; border-radius:12px; background:linear-gradient(135deg, var(--accent), #3b82f6); display:flex; align-items:center; justify-content:center; color:#fff; font-size:18px; font-weight:700; flex-shrink:0;">
                            {{ strtoupper(substr($report->user->name ?? 'S', 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-size:15px; font-weight:600; color:var(--text-primary);">
                                {{ $report->user->name ?? '-' }}
                            </div>
                            <div style="font-size:12.5px; color:var(--text-muted);">Siswa</div>
                        </div>
                    </div>

                    <div style="display:flex; flex-direction:column; gap:12px;">
                        <div class="d-flex align-items-start gap-3">
                            <div
                                style="width:32px; height:32px; border-radius:8px; background:var(--bg-input); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <i class="bi bi-envelope" style="color:var(--text-muted); font-size:14px;"></i>
                            </div>
                            <div style="min-width:0;">
                                <div
                                    style="font-size:11px; color:var(--text-muted); font-weight:500; text-transform:uppercase; letter-spacing:0.5px;">
                                    Email</div>
                                <div style="font-size:13.5px; color:var(--text-primary); word-break:break-all;">
                                    {{ $report->user->email ?? '-' }}</div>
                            </div>
                        </div>

                        @if ($report->student->nis ?? null)
                            <div class="d-flex align-items-start gap-3">
                                <div
                                    style="width:32px; height:32px; border-radius:8px; background:var(--bg-input); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <i class="bi bi-card-text" style="color:var(--text-muted); font-size:14px;"></i>
                                </div>
                                <div style="min-width:0;">
                                    <div
                                        style="font-size:11px; color:var(--text-muted); font-weight:500; text-transform:uppercase; letter-spacing:0.5px;">
                                        NIS</div>
                                    <div style="font-size:13.5px; color:var(--text-primary);">{{ $report->student->nis }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($report->student->kelas ?? null)
                            <div class="d-flex align-items-start gap-3">
                                <div
                                    style="width:32px; height:32px; border-radius:8px; background:var(--bg-input); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <i class="bi bi-mortarboard-fill"
                                        style="color:var(--text-muted); font-size:14px;"></i>
                                </div>
                                <div style="min-width:0;">
                                    <div
                                        style="font-size:11px; color:var(--text-muted); font-weight:500; text-transform:uppercase; letter-spacing:0.5px;">
                                        Kelas</div>
                                    <div style="font-size:13.5px; color:var(--text-primary);">
                                        {{ $report->student->kelas }}</div>
                                </div>
                            </div>
                        @endif

                        <div class="d-flex align-items-start gap-3">
                            <div
                                style="width:32px; height:32px; border-radius:8px; background:var(--bg-input); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <i class="bi bi-shield-check" style="color:var(--text-muted); font-size:14px;"></i>
                            </div>
                            <div style="min-width:0;">
                                <div
                                    style="font-size:11px; color:var(--text-muted); font-weight:500; text-transform:uppercase; letter-spacing:0.5px;">
                                    Status Akun</div>
                                <div style="margin-top:2px;">
                                    @if ($report->deleted_by_user_at)
                                        <span class="badge-status badge-dihapus-user"
                                            style="font-size:11.5px; padding:3px 8px;"><span class="dot"></span>
                                            Dihapus</span>
                                    @else
                                        <span class="badge-status badge-aktif"
                                            style="font-size:11.5px; padding:3px 8px;"><span class="dot"></span>
                                            Aktif</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail Laporan --}}
            <div class="card-dark mb-3" style="cursor:default;" onmouseover="this.style.transform='none'"
                onmouseout="this.style.transform='none'">
                <div class="card-body" style="padding:24px;">
                    <h6 style="font-size:14px; font-weight:600; color:var(--text-secondary); margin-bottom:16px;">
                        <i class="bi bi-info-circle-fill me-2" style="color:var(--accent);"></i>
                        Detail Laporan
                    </h6>

                    <div style="display:flex; flex-direction:column; gap:14px;">
                        <div class="d-flex justify-content-between align-items-center"
                            style="padding-bottom:12px; border-bottom:1px solid var(--border-color);">
                            <span style="font-size:13px; color:var(--text-muted);">Status</span>
                            <span class="badge-status {{ $statusClass }}"
                                style="font-size:11.5px; padding:3px 8px;"><span class="dot"></span>
                                {{ $statusLabel }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center"
                            style="padding-bottom:12px; border-bottom:1px solid var(--border-color);">
                            <span style="font-size:13px; color:var(--text-muted);">Dibuat</span>
                            <span
                                style="font-size:13px; color:var(--text-primary);">{{ $report->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center"
                            style="padding-bottom:12px; border-bottom:1px solid var(--border-color);">
                            <span style="font-size:13px; color:var(--text-muted);">Diperbarui</span>
                            <span
                                style="font-size:13px; color:var(--text-primary);">{{ $report->updated_at->format('d M Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center"
                            style="padding-bottom:12px; border-bottom:1px solid var(--border-color);">
                            <span style="font-size:13px; color:var(--text-muted);">Foto Bukti</span>
                            <span
                                style="font-size:13px; font-weight:500; color:var(--text-primary);">{{ $report->images && $report->images->count() ? $report->images->count() . ' foto' : '—' }}</span>
                        </div>
                        @if ($report->deleted_by_user_at)
                            <div class="d-flex justify-content-between align-items-center">
                                <span style="font-size:13px; color:var(--text-muted);">Dihapus Siswa</span>
                                <span
                                    style="font-size:13px; color:var(--danger);">{{ $report->deleted_by_user_at }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Aksi Cepat --}}
            <div class="card-dark" style="cursor:default;" onmouseover="this.style.transform='none'"
                onmouseout="this.style.transform='none'">
                <div class="card-body" style="padding:24px;">
                    <h6 style="font-size:14px; font-weight:600; color:var(--text-secondary); margin-bottom:16px;">
                        <i class="bi bi-lightning-fill me-2" style="color:var(--accent);"></i>
                        Aksi Cepat
                    </h6>

                    @php
                        $canChangeStatus =
                            !in_array($report->status, ['selesai', 'ditolak']) && !$report->deleted_by_user_at;
                        $canDelete = (bool) $report->deleted_by_user_at;
                    @endphp

                    <div style="display:flex; flex-direction:column; gap:8px;">
                        @if ($canChangeStatus)
                            <div class="dropdown w-100">
                                <button type="button" class="btn-action btn-detail w-100 justify-content-center"
                                    style="padding:10px 16px; font-size:13.5px; border-radius:8px;"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-arrow-repeat"></i>
                                    Ubah Status
                                    <i class="bi bi-chevron-down ms-1" style="font-size:11px;"></i>
                                </button>
                                <ul class="dropdown-menu w-100"
                                    style="background:var(--bg-card); border-color:var(--border-color); border-radius:10px; padding:6px; box-shadow:0 8px 24px rgba(0,0,0,0.1);">
                                    <li>
                                        <form method="POST" action="{{ route('admin.update-status', $report->id) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="selesai">
                                            <button type="submit" class="dropdown-item d-flex align-items-center gap-2"
                                                style="color:var(--success); font-size:13px; border-radius:6px; padding:8px 12px;"
                                                onmouseover="this.style.background='var(--success-bg)'"
                                                onmouseout="this.style.background='transparent'">
                                                <i class="bi bi-check-circle-fill"></i> Selesai
                                                @if ($report->status === 'selesai')
                                                    <i class="bi bi-check-lg ms-auto"></i>
                                                @endif
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('admin.update-status', $report->id) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="ditolak">
                                            <button type="submit" class="dropdown-item d-flex align-items-center gap-2"
                                                style="color:var(--danger); font-size:13px; border-radius:6px; padding:8px 12px;"
                                                onmouseover="this.style.background='var(--danger-bg)'"
                                                onmouseout="this.style.background='transparent'">
                                                <i class="bi bi-x-circle-fill"></i> Ditolak
                                                @if ($report->status === 'ditolak')
                                                    <i class="bi bi-check-lg ms-auto"></i>
                                                @endif
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <button type="button" class="btn-action btn-detail w-100 justify-content-center"
                                style="padding:10px 16px; font-size:13.5px; border-radius:8px; opacity:0.4; cursor:not-allowed;"
                                disabled>
                                <i class="bi bi-arrow-repeat"></i> Ubah Status
                            </button>
                            <div style="font-size:11.5px; color:var(--text-muted); text-align:center; margin-top:-4px;">
                                @if (in_array($report->status, ['selesai', 'ditolak']))
                                    Laporan sudah ditutup.
                                @elseif ($report->deleted_by_user_at)
                                    Laporan sudah dihapus siswa.
                                @endif
                            </div>
                        @endif

                        <div style="border-top:1px solid var(--border-color); margin:4px 0;"></div>

                        @if ($canDelete)
                            <button type="button" class="btn-action btn-delete w-100 justify-content-center"
                                style="padding:10px 16px; font-size:13.5px; border-radius:8px;"
                                onclick="confirmDelete('{{ route('admin.destroy', $report->id) }}', 'Hapus laporan ini secara permanen? Data tidak akan bisa dikembalikan.')">
                                <i class="bi bi-trash3"></i> Hapus Permanen
                            </button>
                            <div style="font-size:11.5px; color:var(--text-muted); text-align:center; margin-top:-4px;">
                                Dihapus siswa pada {{ $report->deleted_by_user_at }}
                            </div>
                        @else
                            <button type="button" class="btn-action btn-delete w-100 justify-content-center"
                                style="padding:10px 16px; font-size:13.5px; border-radius:8px; opacity:0.4; cursor:not-allowed;"
                                disabled>
                                <i class="bi bi-trash3"></i> Hapus Permanen
                            </button>
                            <div style="font-size:11.5px; color:var(--text-muted); text-align:center; margin-top:-4px;">
                                Hanya bisa dihapus setelah siswa menghapus laporannya.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade lb-overlay" id="lightboxModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                {{-- Tutup --}}
                <button type="button" class="lb-close-btn" id="lbCloseBtn" aria-label="Tutup">
                    <i class="bi bi-x-lg"></i>
                </button>

                {{-- Hint keyboard --}}
                <div class="lb-keyboard-hint" id="lbHint">
                    <i class="bi bi-keyboard me-1"></i> Gunakan panah kiri/kanan untuk navigasi
                </div>

                {{-- Navigasi kiri --}}
                <button type="button" class="lb-nav-btn lb-prev" id="lbPrevBtn" aria-label="Gambar sebelumnya">
                    <i class="bi bi-chevron-left"></i>
                </button>

                {{-- Gambar + Loader --}}
                <div class="lb-img-wrap">
                    <div class="lb-loader">
                        <div class="spinner-border" id="lbSpinner" role="status"></div>
                    </div>
                    <img id="lbImage" src="" alt="Preview bukti foto">
                </div>

                {{-- Navigasi kanan --}}
                <button type="button" class="lb-nav-btn lb-next" id="lbNextBtn" aria-label="Gambar selanjutnya">
                    <i class="bi bi-chevron-right"></i>
                </button>

                {{-- Thumbnail strip --}}
                <div class="lb-thumbs" id="lbThumbs"></div>

                {{-- Info bar --}}
                <div class="lb-info-bar">
                    <div class="lb-counter">
                        <span class="lb-cur" id="lbCur">1</span> / <span id="lbTotal">1</span>
                    </div>
                    <div class="lb-info-divider"></div>
                    <div class="lb-info-label" id="lbLabel">Foto Bukti</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // ========================================
        // Upload Attachments
        // ========================================
        const form = document.getElementById('responseForm');
        const fileInput = document.getElementById('fileInput');
        const previewContainer = document.getElementById('attachmentPreviews');
        const countDisplay = document.getElementById('attachmentCount');
        const uploadButton = document.getElementById('uploadTriggerBtn');

        if (form && fileInput && uploadButton) {
            let selectedFiles = [];

            function renderPreviews() {
                previewContainer.innerHTML = '';
                if (selectedFiles.length === 0) {
                    countDisplay.textContent = '';
                    return;
                }
                countDisplay.textContent = selectedFiles.length + ' file dipilih';

                selectedFiles.forEach(function(file, index) {
                    const item = document.createElement('div');
                    item.className = 'd-flex align-items-center gap-2 p-2 rounded border';
                    item.style.backgroundColor = 'var(--bg-input)';
                    item.style.borderColor = 'var(--border-color)';

                    const nameSpan = document.createElement('span');
                    nameSpan.textContent = file.name;
                    nameSpan.style.flex = '1';
                    nameSpan.style.overflow = 'hidden';
                    nameSpan.style.textOverflow = 'ellipsis';
                    nameSpan.style.whiteSpace = 'nowrap';
                    nameSpan.style.color = 'var(--text-primary)';

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn btn-sm btn-outline-danger border-0';
                    removeBtn.innerHTML = '<i class="bi bi-x-lg"></i>';
                    removeBtn.addEventListener('click', function() {
                        selectedFiles.splice(index, 1);
                        renderPreviews();
                    });

                    item.appendChild(nameSpan);
                    item.appendChild(removeBtn);
                    previewContainer.appendChild(item);
                });
            }

            uploadButton.addEventListener('click', function(e) {
                e.preventDefault();
                fileInput.click();
            });

            fileInput.addEventListener('change', function(e) {
                var newFiles = Array.from(e.target.files);
                newFiles.forEach(function(file) {
                    var isDuplicate = selectedFiles.some(function(f) {
                        return f.name === file.name && f.size === file.size && f.lastModified === file.lastModified;
                    });
                    if (!isDuplicate) selectedFiles.push(file);
                });
                renderPreviews();
                fileInput.value = '';
            });

            form.addEventListener('submit', function() {
                if (selectedFiles.length === 0) return;
                var dt = new DataTransfer();
                selectedFiles.forEach(function(file) { dt.items.add(file); });
                fileInput.files = dt.files;
            });

            window.addEventListener('pageshow', function() {
                selectedFiles = [];
                renderPreviews();
            });
        }
    });

    // ========================================
    // Lightbox — Navigasi Multi Gambar
    // ========================================
    (function() {
        // Data gambar dari Blade
        var lbImages = @json($imageUrls ?? []);
        var lbThumbs = @json($imageThumbs ?? []);
        var lbIndex = 0;
        var lbInstance = null;
        var lbTransitioning = false;
        var hintTimer = null;

        // Elemen
        var modalEl = document.getElementById('lightboxModal');
        var imgEl = document.getElementById('lbImage');
        var spinnerEl = document.getElementById('lbSpinner');
        var curEl = document.getElementById('lbCur');
        var totalEl = document.getElementById('lbTotal');
        var labelEl = document.getElementById('lbLabel');
        var thumbsEl = document.getElementById('lbThumbs');
        var prevBtn = document.getElementById('lbPrevBtn');
        var nextBtn = document.getElementById('lbNextBtn');
        var closeBtn = document.getElementById('lbCloseBtn');
        var hintEl = document.getElementById('lbHint');

        if (!modalEl) return;

        // Inisialisasi modal Bootstrap
        lbInstance = new bootstrap.Modal(modalEl);

        // === Fungsi utama ===

        // Buka lightbox dari gallery
        window.openLightbox = function(index) {
            if (lbImages.length === 0) return;
            lbIndex = index;
            lbInstance.show();
            buildThumbs();
            setImage(false);
            showHint();
        };

        // Set gambar (animate = true untuk transisi halus)
        function setImage(animate) {
            if (lbTransitioning && animate) return;
            var item = lbImages[lbIndex];

            if (animate) {
                lbTransitioning = true;
                imgEl.classList.remove('lb-visible');
                setTimeout(function() {
                    loadAndShow(item, false);
                }, 200);
            } else {
                loadAndShow(item, true);
            }

            updateInfo();
            updateNavState();
            updateActiveThumb();
        }

        // Load gambar dengan spinner
        function loadAndShow(src, immediate) {
            spinnerEl.classList.add('lb-loading');
            imgEl.src = src;

            // Jika gambar sudah di-cache, langsung tampilkan
            if (imgEl.complete && imgEl.naturalWidth > 0) {
                spinnerEl.classList.remove('lb-loading');
                if (immediate) {
                    // Sedikit delay agar transisi awal terlihat
                    requestAnimationFrame(function() {
                        void imgEl.offsetWidth;
                        imgEl.classList.add('lb-visible');
                    });
                } else {
                    void imgEl.offsetWidth;
                    imgEl.classList.add('lb-visible');
                    lbTransitioning = false;
                }
            } else {
                imgEl.onload = function() {
                    spinnerEl.classList.remove('lb-loading');
                    void imgEl.offsetWidth;
                    imgEl.classList.add('lb-visible');
                    lbTransitioning = false;
                };
                imgEl.onerror = function() {
                    spinnerEl.classList.remove('lb-loading');
                    lbTransitioning = false;
                };
            }
        }

        // Update info bar
        function updateInfo() {
            curEl.textContent = lbIndex + 1;
            totalEl.textContent = lbImages.length;
            labelEl.textContent = 'Foto ' + (lbIndex + 1);
        }

        // Update state tombol navigasi (disable di ujung)
        function updateNavState() {
            if (lbImages.length <= 1) {
                prevBtn.classList.add('lb-disabled');
                nextBtn.classList.add('lb-disabled');
            } else {
                prevBtn.classList.toggle('lb-disabled', lbIndex === 0);
                nextBtn.classList.toggle('lb-disabled', lbIndex === lbImages.length - 1);
            }
        }

        // Navigasi
        function goNext() {
            if (lbIndex < lbImages.length - 1) {
                lbIndex++;
                setImage(true);
            }
        }

        function goPrev() {
            if (lbIndex > 0) {
                lbIndex--;
                setImage(true);
            }
        }

        // === Thumbnail strip ===
        function buildThumbs() {
            thumbsEl.innerHTML = '';
            if (lbThumbs.length <= 1) {
                thumbsEl.style.display = 'none';
                return;
            }
            thumbsEl.style.display = 'flex';

            lbThumbs.forEach(function(src, i) {
                var thumb = document.createElement('div');
                thumb.className = 'lb-thumb-item' + (i === lbIndex ? ' lb-active' : '');
                thumb.innerHTML = '<img src="' + src + '" alt="Thumbnail ' + (i + 1) + '" loading="lazy">';
                thumb.addEventListener('click', function() {
                    if (lbTransitioning) return;
                    lbIndex = i;
                    setImage(true);
                });
                thumbsEl.appendChild(thumb);
            });
        }

        function updateActiveThumb() {
            var items = thumbsEl.querySelectorAll('.lb-thumb-item');
            items.forEach(function(t, i) {
                t.classList.toggle('lb-active', i === lbIndex);
            });
            // Scroll thumbnail aktif ke tengah
            var active = thumbsEl.querySelector('.lb-thumb-item.lb-active');
            if (active) {
                active.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
            }
        }

        // === Keyboard hint ===
        function showHint() {
            if (!hintEl || lbImages.length <= 1) return;
            clearTimeout(hintTimer);
            hintEl.classList.add('lb-show-hint');
            hintTimer = setTimeout(function() {
                hintEl.classList.remove('lb-show-hint');
            }, 3000);
        }

        // === Event listeners ===

        // Tombol navigasi
        prevBtn.addEventListener('click', goPrev);
        nextBtn.addEventListener('click', goNext);
        closeBtn.addEventListener('click', function() { lbInstance.hide(); });

        // Keyboard
        document.addEventListener('keydown', function(e) {
            if (!lbInstance._isShown) return;
            if (e.key === 'ArrowRight' || e.key === 'd') { e.preventDefault(); goNext(); }
            else if (e.key === 'ArrowLeft' || e.key === 'a') { e.preventDefault(); goPrev(); }
            else if (e.key === 'Escape') { e.preventDefault(); lbInstance.hide(); }
        });

        // Touch / swipe
        var touchStartX = 0;
        var touchStartY = 0;
        var isSwiping = false;

        modalEl.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
            touchStartY = e.changedTouches[0].screenY;
            isSwiping = true;
        }, { passive: true });

        modalEl.addEventListener('touchend', function(e) {
            if (!isSwiping) return;
            isSwiping = false;
            var touchEndX = e.changedTouches[0].screenX;
            var touchEndY = e.changedTouches[0].screenY;
            var diffX = touchStartX - touchEndX;
            var diffY = touchStartY - touchEndY;

            // Hanya proses swipe horizontal jika lebih dominan dari vertikal
            if (Math.abs(diffX) > 50 && Math.abs(diffX) > Math.abs(diffY)) {
                if (diffX > 0) goNext();
                else goPrev();
            }
        }, { passive: true });

        // Reset saat modal ditutup
        modalEl.addEventListener('hidden.bs.modal', function() {
            imgEl.classList.remove('lb-visible');
            spinnerEl.classList.remove('lb-loading');
            lbTransitioning = false;
            if (hintEl) hintEl.classList.remove('lb-show-hint');
        });

    })();
    </script>
@endpush