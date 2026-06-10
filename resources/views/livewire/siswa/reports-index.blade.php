<main wire:poll.5s.keep-alive wire:poll.on-error="resetPolling" class="page-body">

    <div class="row g-3 mb-3">
        <div class="col-12 col-xl-6">
            <div class="filter-bar mb-3">
                    <div class="search-input-wrapper">
                        <i class="bi bi-search"></i>
                        <input type="text" wire:model.live.debounce.500ms="search" class="form-control"
                            placeholder="Cari judul laporan">
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
        </div>
    </div>

    <!-- CARDS GRID -->
    <div class="row g-3">

        @forelse ($reports as $index => $report)
            <div class="col-12 col-md-6 col-xl-4">
                <div class="report-card fade-up">
                    <div
                        class="card-accent 
                            @if ($report->status == 'pending') pending
                            @elseif ($report->status == 'diproses') diproses                                
                            @elseif ($report->status == 'ditolak') ditolak                                
                            @else selesai @endif">
                    </div>
                    <div class="card-body-custom">
                        <div class="card-meta">
                            <span class="report-id">
                                @if ($report->is_anonymous)
                                    Anonim
                                @else
                                    {{ $report->user->name ?? 'Akun Terhapus' }}
                                @endif
                            </span>
                            @if ($report->status === 'pending')
                                <span class="badge-status badge-pending"><i class="bi bi-clock me-1"></i>Pending</span>
                            @elseif ($report->status === 'diproses')
                                <span class="badge-status badge-diproses"><i
                                        class="bi bi-arrow-repeat me-1"></i>Diproses</span>
                            @elseif ($report->status === 'selesai')
                                <span class="badge-status badge-selesai"><i
                                        class="bi bi-check-circle me-1"></i>Selesai</span>
                            @elseif ($report->status === 'ditolak')
                                <span class="badge-status badge-ditolak"><i
                                        class="bi bi-x-circle me-1"></i>Ditolak</span>
                            @endif
                        </div>
                        <div class="report-title">{{ $report->title }}</div>
                        <div class="report-desc">
                            {{ $report->description }}
                        </div>
                        <div class="report-location">
                            <i class="bi bi-geo-alt-fill"></i> {{ $report->location }}
                        </div>
                        <div class="card-date">
                            <i class="bi bi-calendar3"></i>
                            {{ $report->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}
                        </div>
                    </div>
                    <div class="card-footer d-flex gap-2 w-100 p-3">
                        <a href="{{ route('siswa.show', $report->id) }}"
                            class="btn btn-danger {{ $report->status !== 'diproses' ? 'w-50' : 'w-100' }}">
                            <i class="bi bi-eye-fill me-2"></i>
                            <span class="fs-6 playfair">Detail</span>
                        </a>
                        @if ($report->status !== 'diproses')
                            <form action="{{ route('siswa.destroy', $report->id) }}" method="POST" class="w-50">
                                @csrf @method('DELETE')
                                <button class="btn btn-light w-100 text-secondary" style="pointer: disabled;">
                                    <i class="bi bi-trash3 me-2"></i>
                                    <span class="fs-6 text-secondary playfair">Hapus</span>
                                </button>
                            </form>
                            {{-- @else
                                <button class="btn btn-light w-50" disabled><i class="bi bi-trash3"></i> Hapus</button> --}}
                        @endif
                    </div>
                </div>
            </div>
        @empty
            @if($search || $statusFilter)
            <p class="mt-2 text-secondary fst-italic text-center">
                <i class="bi bi-search me-2"></i>
                Laporan tidak ditemukan
            </p>
            @else
                <p class="mt-2 text-secondary fst-italic text-center"><i class="bi bi-inbox me-2"></i>Anda belum memiliki laporan.</p>
            @endif
        @endforelse

        <div class="">
            {{ $reports->links() }}
        </div>
</main>

@push('scripts')
    <script src="{{ asset('templates/js/script.js') }}"></script>
    <script>
        if (window.history.length > 1) {
            window.history.replaceState({}, document.title, window.location.href);
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
@endpush
