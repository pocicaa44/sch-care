@extends('layouts.admin')

@section('pageTitle', 'Dashboard Laporan')

@section('content')
    <!-- ========================================
             Kartu Statistik
             ======================================== -->
    <div class="row g-3 mb-4">
        {{-- Total Laporan --}}
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card-dark stat-card stat-total">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon icon-total">
                            <i class="bi bi-file-earmark-bar-graph-fill"></i>
                        </div>
                        <div>
                            <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
                            <div class="stat-label">Total Laporan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending --}}
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card-dark stat-card stat-pending">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon icon-pending">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div>
                            <div class="stat-value">{{ $stats['pending'] ?? 0 }}</div>
                            <div class="stat-label">Pending</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Diproses --}}
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card-dark stat-card stat-diproses">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon icon-diproses">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <div>
                            <div class="stat-value">{{ $stats['diproses'] ?? 0 }}</div>
                            <div class="stat-label">Diproses</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Selesai --}}
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card-dark stat-card stat-selesai">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon icon-selesai">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div>
                            <div class="stat-value">{{ $stats['selesai'] ?? 0 }}</div>
                            <div class="stat-label">Selesai</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ditolak --}}
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card-dark stat-card stat-ditolak">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon icon-ditolak">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                        <div>
                            <div class="stat-value">{{ $stats['ditolak'] ?? 0 }}</div>
                            <div class="stat-label">Ditolak</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================
             Tabel Daftar Laporan
             ======================================== -->
        <div class="card-dark" style="cursor:default;" onmouseover="this.style.transform='none'"
            onmouseout="this.style.transform='none'">
            <div class="card-body">
                {{-- Header: Judul + Filter --}}
                <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-3">
                    <div>
                        <h5 class="mb-1" style="font-size:16px; font-weight:600;">Daftar Laporan</h5>
                        <p style="font-size:13px; color:var(--text-muted); margin:0;">
                            Menampilkan semua laporan dari siswa
                        </p>
                    </div>
                </div>

                {{-- Baris Filter & Pencarian --}}
                <form method="GET" action="{{ request()->fullUrlWithoutQuery(['search', 'status']) }}"
                    class="filter-bar mb-3">
                    <div class="search-input-wrapper">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" class="form-control form-control-dark"
                            placeholder="Cari judul laporan atau nama siswa..." value="{{ $searchTerm }}"
                            autocomplete="off">
                    </div>

                    <select name="status" class="form-select form-select-dark auto-submit"
                        style="width:auto; min-width:160px;">
                        <option value="all" {{ $statusFilter == 'all' || is_null($statusFilter) ? 'selected' : '' }}>
                            Semua Laporan</option>
                        <option value="pending" {{ $statusFilter == 'pending' ? 'selected' : '' }}>Pending
                            ({{ $stats['pending'] }})</option>
                        <option value="diproses" {{ $statusFilter == 'diproses' ? 'selected' : '' }}>Diproses
                            ({{ $stats['diproses'] }})</option>
                        <option value="selesai" {{ $statusFilter == 'selesai' ? 'selected' : '' }}>Selesai
                            ({{ $stats['selesai'] }})</option>
                        <option value="ditolak" {{ $statusFilter == 'ditolak' ? 'selected' : '' }}>Ditolak
                            ({{ $stats['ditolak'] }})</option>
                    </select>

                    @if (request('search') || request('status'))
                        <a href="{{ request()->fullUrlWithoutQuery(['search', 'status']) }}" class="btn-action btn-detail"
                            style="white-space:nowrap;">
                            <i class="bi bi-x-lg"></i> Reset
                        </a>
                    @endif
                </form>

                {{-- Tabel --}}
                <div class="table-responsive">
                    @if ($reports->count() > 0)
                        <table class="table-dark-custom" id="reports-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Siswa</th>
                                    <th>Judul Laporan</th>
                                    <th>Status</th>
                                    <th>Tanggal Dibuat</th>
                                    <th style="text-align:center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $report)
                                    <tr>
                                        <td>
                                            <span
                                                style="color:var(--text-muted); font-weight:500;">#{{ $report->id }}</span>
                                        </td>
                                        <td>{{ $report->user->name ?? '-' }}</td>
                                        <td>
                                            <span style="font-weight:500;">
                                                {{ Str::limit($report->title, 50) }}
                                            </span>
                                        </td>
                                        <td>
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
                                            <span class="badge-status {{ $statusClass }}">
                                                <span class="dot"></span>
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                        <td style="color:var(--text-secondary); white-space:nowrap;">
                                            {{ $report->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td style="text-align:center; white-space:nowrap;">
                                            <a href="{{ route('admin.show', $report->id) }}" class="btn-action btn-detail"
                                                title="Detail Laporan">
                                                <i class="bi bi-eye"></i>
                                                <span class="d-none d-md-inline">Detail</span>
                                            </a>

                                            @if ($report->deleted_at)
                                                <button type="button" class="btn-action btn-delete" title="Hapus Permanen"
                                                    onclick="confirmDelete(
                                                        '{{ route('admin.destroy', $report->id) }}',
                                                        'Hapus laporan "{{ Str::limit($report->title, 30) }}"
                                                    secara permanen?' )">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-inbox d-block"></i>
                            <p>Tidak ada laporan ditemukan.</p>
                        </div>
                    @endif
                </div>

                {{-- Paginasi --}}
                @if ($reports->hasPages())
                    <div class="pagination-wrapper">
                        <div class="pagination-info">
                            Menampilkan {{ $reports->firstItem() }}–{{ $reports->lastItem() }}
                            dari {{ $reports->total() }} laporan
                        </div>
                        <div>
                            {{ $reports->onEachSide(1)->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endsection

    @push('scripts')
        {{-- Override style pagination links agar cocok dengan dark theme --}}
        <style>
            .pagination-dark li a,
            .pagination-dark li span {
                display: inline-flex;
            }
        </style>

        <script>
            // Terapkan class pagination-dark ke elemen pagination Laravel
            document.querySelectorAll('.pagination').forEach(el => {
                el.classList.remove('pagination');
                el.classList.add('pagination-dark');
            });

            // Hapus link wrapper yang tidak perlu (prev/next text Laravel)
            document.querySelectorAll('.pagination-dark a, .pagination-dark span').forEach(el => {
                // Bersihkan HTML entities yang mungkin ada
                el.innerHTML = el.innerHTML.replace(/&laquo;/g, '‹').replace(/&raquo;/g, '›');
            });
        </script>
    @endpush

    @push('scripts')
<script>
    (function() {
        // Konfigurasi
        const APP_KEY = 'nd9zbdezxcblnm0cqfha';
        const WS_URL = `ws://localhost:8080/app/${APP_KEY}`;
        let ws = null;
        let reconnectTimer = null;

        function connectWebSocket() {
            if (ws && ws.readyState === WebSocket.OPEN) return;
            ws = new WebSocket(WS_URL);
            ws.onopen = function() {
                console.log('✅ WebSocket connected');
                ws.send(JSON.stringify({
                    event: 'pusher:subscribe',
                    data: { channel: 'reports' }
                }));
                if (reconnectTimer) clearTimeout(reconnectTimer);
            };
            ws.onmessage = function(e) {
                const msg = JSON.parse(e.data);
                if (msg.event === 'new-report') {
                    const data = JSON.parse(msg.data);
                    const report = data.report;
                    const tbody = document.querySelector('#reports-table tbody');
                    if (!tbody) return;
                    const newRow = `
                        <tr id="report-row-${report.id}">
                            <td><span style="color:var(--text-muted);font-weight:500;">#${report.id}</span></td>
                            <td>${escapeHtml(report.user?.name ?? 'Unknown')} </td>
                            <td>${escapeHtml(report.title)}</td>
                            <td><span class="badge-status badge-pending"><span class="dot"></span> Pending</span></td>
                            <td style="color:var(--text-secondary); white-space:nowrap;">${new Date(report.created_at).toLocaleString()}</td>
                            <td><a href="/panel/report/${report.id}" class="btn-action btn-detail">Detail</a></td>
                        </tr>
                    `;
                    tbody.insertAdjacentHTML('afterbegin', newRow);
                    // Update statistik
                    const totalSpan = document.querySelector('.stat-total .stat-value');
                    const pendingSpan = document.querySelector('.stat-pending .stat-value');
                    if (totalSpan) totalSpan.innerText = parseInt(totalSpan.innerText) + 1;
                    if (pendingSpan) pendingSpan.innerText = parseInt(pendingSpan.innerText) + 1;
                }
            };
            ws.onclose = function() {
                reconnectTimer = setTimeout(connectWebSocket, 3000);
            };
            ws.onerror = function(e) {
                console.error('WebSocket error', e);
            };
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', connectWebSocket);
        } else {
            connectWebSocket();
        }
    })();
</script>
@endpush
