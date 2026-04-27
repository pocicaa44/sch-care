@extends('layouts.admin')

@section('pageTitle', 'Detail Laporan' . $report->id)

@section('content')
    {{-- Tombol Kembali --}}
    <a href="{{ URL::previous() }}" class="d-inline-flex align-items-center gap-2 text-decoration-none mb-4"
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
                        {{-- Menyimpan semua URL gambar untuk lightbox --}}
                        @php
                            $imageUrls = $report->images->map(fn($img) => asset('storage/' . $img->path))->toArray();
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
                                        {{-- Overlay ikon zoom --}}
                                        <div style="position:absolute; inset:0; background:rgba(0,0,0,0.35); display:flex; align-items:center; justify-content:center; opacity:0; transition: opacity 0.2s ease;"
                                            onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                                            <div
                                                style="width:36px; height:36px; border-radius:50%; background:rgba(255,255,255,0.9); display:flex; align-items:center; justify-content:center;">
                                                <i class="bi bi-zoom-in"
                                                    style="color:var(--text-primary); font-size:16px;"></i>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Label nomor --}}
                                    <div
                                        style="font-size:11px; color:var(--text-muted); margin-top:6px; text-align:center;">
                                        Foto {{ $index + 1 }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4" style="color:var(--text-muted); font-size:13.5px;">
                            <i class="bi bi-image d-block mb-2" style="font-size:32px; opacity:0.3;"></i>
                            Tidak ada bukti foto.
                        </div>
                    @endif
                </div>
            </div>

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

                        {{-- Container preview dan jumlah file --}}
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

        <!-- ========================================
                                                 Kolom Kanan: Sidebar Info (4/12)
                                                 ======================================== -->
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

    <!-- ========================================
                                             Modal: Lightbox Foto Bukti
                                             ======================================== -->
    <div class="modal fade modal-dark" id="lightboxModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width:90vw;">
            <div class="modal-content" style="background:transparent; border:none; box-shadow:none;">
                <div class="modal-body" style="padding:0; text-align:center; position:relative;">
                    {{-- Tombol tutup --}}
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"
                        style="position:absolute; top:-40px; right:0; opacity:0.7; z-index:2;"></button>

                    {{-- Navigasi kiri --}}
                    <button type="button" id="lightboxPrev" onclick="navigateLightbox(-1)"
                        style="position:absolute; left:-50px; top:50%; transform:translateY(-50%); width:40px; height:40px; border-radius:50%; background:var(--bg-card); border:1px solid var(--border-color); color:var(--text-primary); display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 2px 8px rgba(0,0,0,0.1); z-index:2; transition: background 0.2s ease;"
                        onmouseover="this.style.background='var(--bg-input)'"
                        onmouseout="this.style.background='var(--bg-card)'">
                        <i class="bi bi-chevron-left" style="font-size:18px;"></i>
                    </button>

                    {{-- Gambar --}}
                    <img id="lightboxImage" src="" alt="Preview"
                        style="max-width:100%; max-height:80vh; border-radius:12px; object-fit:contain; transition: opacity 0.2s ease;">

                    {{-- Navigasi kanan --}}
                    <button type="button" id="lightboxNext" onclick="navigateLightbox(1)"
                        style="position:absolute; right:-50px; top:50%; transform:translateY(-50%); width:40px; height:40px; border-radius:50%; background:var(--bg-card); border:1px solid var(--border-color); color:var(--text-primary); display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 2px 8px rgba(0,0,0,0.1); z-index:2; transition: background 0.2s ease;"
                        onmouseover="this.style.background='var(--bg-input)'"
                        onmouseout="this.style.background='var(--bg-card)'">
                        <i class="bi bi-chevron-right" style="font-size:18px;"></i>
                    </button>

                    {{-- Counter --}}
                    <div id="lightboxCounter"
                        style="margin-top:12px; font-size:13px; color:var(--text-muted); font-weight:500;">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // === Elemen ===
        const form = document.getElementById('responseForm');
        const fileInput = document.getElementById('fileInput');
        const previewContainer = document.getElementById('attachmentPreviews');
        const countDisplay = document.getElementById('attachmentCount');
        const uploadButton = document.getElementById('uploadTriggerBtn');

        if (!form || !fileInput || !uploadButton) return;

        let selectedFiles = []; // Array penampung File object

        // === Render preview nama file & tombol hapus ===
        function renderPreviews() {
            previewContainer.innerHTML = '';
            if (selectedFiles.length === 0) {
                countDisplay.textContent = '';
                return;
            }
            countDisplay.textContent = `${selectedFiles.length} file dipilih`;

            selectedFiles.forEach((file, index) => {
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
                removeBtn.addEventListener('click', () => {
                    selectedFiles.splice(index, 1);
                    renderPreviews();
                });

                item.appendChild(nameSpan);
                item.appendChild(removeBtn);
                previewContainer.appendChild(item);
            });
        }

        // === Buka dialog file ===
        uploadButton.addEventListener('click', (e) => {
            e.preventDefault();
            fileInput.click();
        });

        // === Tangani file yang dipilih ===
        fileInput.addEventListener('change', (e) => {
            const newFiles = Array.from(e.target.files);
            newFiles.forEach(file => {
                // Cegah duplikat (opsional)
                const isDuplicate = selectedFiles.some(f =>
                    f.name === file.name &&
                    f.size === file.size &&
                    f.lastModified === file.lastModified
                );
                if (!isDuplicate) {
                    selectedFiles.push(file);
                }
            });
            renderPreviews();
            fileInput.value = ''; // Reset agar bisa memilih file yang sama lagi
        });

        // === Sebelum submit, isi input file dengan DataTransfer ===
        form.addEventListener('submit', function(e) {
            if (selectedFiles.length === 0) return; // Tidak ada file, lanjutkan submit biasa

            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            fileInput.files = dt.files;

            // Lanjutkan submit native (tidak perlu preventDefault)
        });

        // === Opsional: Bersihkan state saat halaman dimuat ulang (setelah submit) ===
        window.addEventListener('pageshow', function() {
            selectedFiles = [];
            renderPreviews();
        });
    });

    // ========================================
    // Lightbox — Navigasi Multi Gambar (tetap)
    // ========================================
    const lightboxImages = @json($imageUrls ?? []);
    let lightboxIndex = 0;
    let lightboxInstance = null;

    const lightboxModalEl = document.getElementById('lightboxModal');
    if (lightboxModalEl) {
        lightboxInstance = new bootstrap.Modal(lightboxModalEl);
    }

    function openLightbox(index) {
        if (lightboxImages.length === 0) return;
        lightboxIndex = index;
        updateLightboxImage();
        updateLightboxNav();
        if (lightboxInstance) lightboxInstance.show();
    }

    function navigateLightbox(direction) {
        lightboxIndex += direction;
        if (lightboxIndex < 0) lightboxIndex = lightboxImages.length - 1;
        if (lightboxIndex >= lightboxImages.length) lightboxIndex = 0;

        const img = document.getElementById('lightboxImage');
        img.style.opacity = '0';
        setTimeout(() => {
            updateLightboxImage();
            img.style.opacity = '1';
        }, 150);
    }

    function updateLightboxImage() {
        const img = document.getElementById('lightboxImage');
        const counter = document.getElementById('lightboxCounter');
        if (img) img.src = lightboxImages[lightboxIndex];
        if (counter) counter.textContent = (lightboxIndex + 1) + ' / ' + lightboxImages.length;
    }

    function updateLightboxNav() {
        const prev = document.getElementById('lightboxPrev');
        const next = document.getElementById('lightboxNext');
        const show = lightboxImages.length > 1;
        if (prev) prev.style.display = show ? 'flex' : 'none';
        if (next) next.style.display = show ? 'flex' : 'none';
    }

    document.addEventListener('keydown', function(e) {
        if (!lightboxInstance || !lightboxInstance._isShown) return;
        if (e.key === 'ArrowLeft') navigateLightbox(-1);
        if (e.key === 'ArrowRight') navigateLightbox(1);
        if (e.key === 'Escape') lightboxInstance.hide();
    });
</script>
@endpush
