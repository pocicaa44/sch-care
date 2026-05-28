    <div wire:poll.5s class="row g-3 mb-4">
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

        {{-- tabel --}}
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
                <div class="filter-bar mb-3">
                    <div class="search-input-wrapper">
                        <i class="bi bi-search"></i>
                        <input type="text" wire:model.live="search" class="form-control form-control-dark"
                            placeholder="Cari judul laporan atau nama siswa...">
                    </div>

                    <select wire:model.live="statusFilter" class="form-select form-select-dark auto-submit"
                        style="width:auto; min-width:160px;">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending ({{ $stats['pending'] }})</option>
                        <option value="diproses">Diproses ({{ $stats['diproses'] }})</option>
                        <option value="selesai">Selesai ({{ $stats['selesai'] }})</option>
                        <option value="ditolak">Ditolak ({{ $stats['ditolak'] }})</option>
                    </select>

                    @if ($search || $statusFilter)
                        <button wire:click="resetFilters" class="btn-action btn-detail" wire:navigate style="white-space:nowrap;">
                            <i class="bi bi-x-lg"></i> Reset
                        </button>
                    @endif
                </div>

                {{-- Tabel --}}
                <div class="table-responsive">
                    <table class="table-dark-custom" id="reports-table">
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th>Nama Siswa</th>
                                <th>Judul Laporan</th>
                                <th>Status</th>
                                <th>Tanggal Dibuat</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports as $report)
                                <tr>
                                    <td>
                                        @if (!$report->is_read)
                                            <span class="badge bg-danger me-2">Baru</span>
                                        @endif
                                        <span
                                            style="color:var(--text-muted); font-weight:500;">#{{ $report->id }}</span>
                                    </td>
                                    <td>{{ $report->user->name ?? 'Akun Terhapus' }}</td>
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
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada laporan ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-3">
                    {{ $reports->links() }}
                </div>
            </div>
        </div>
