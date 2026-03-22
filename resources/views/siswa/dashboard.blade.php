@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@push('styles')
    <link rel="stylesheet" href="{{ asset('templates/css/dashboard.css') }}">
@endpush

@section('content')

<div class="main-content">

    <!-- TOPBAR -->
      <header class="topbar">
        <div class="topbar-title">
          <h2>Recent Reports</h2>
          <p>Kamis, 19 Maret 2026</p>
        </div>
        <div class="topbar-actions">
          <div class="search-box d-none d-md-block">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Cari laporan..." />
          </div>
          <button class="btn-topbar d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#mobileNav" aria-controls="mobileNav">
            <i class="bi bi-list"></i>
          </button>
          <button class="btn-topbar"><i class="bi bi-bell"></i></button>
          <a href="#" class="btn-tambah d-none d-md-inline-flex">
            <i class="bi bi-plus-lg"></i> Tambah Laporan
          </a>
        </div>
      </header>

    <main class="page-body">
        <!-- CARDS GRID -->
        <div class="row g-3">


          @forelse ($reports as $index => $report)
            
            
          <div class="col-12 col-md-6 col-xl-4">
            <div class="report-card fade-up">
              <div class="card-accent pending"></div>
              <div class="card-body-custom">
                <div class="card-meta">
                  <span class="report-id">{{ $index + 1 }}</span>
                  {{-- @switch($report->status)
                    @case('pending')
                      <span class="badge-status badge-pending"><i class="bi bi-clock me-1"></i>Pending</span>
                    @case('diproses')
                      <span class="badge-status badge-diproses"><i class="bi bi-clock me-1"></i>Diproses</span>
                    @case('selesai')
                      <span class="badge-status badge-selesai"><i class="bi bi-clock me-1"></i>Selesai</span>
                    @case('ditolak')
                      <span class="badge-status badge-"><i class="bi bi-clock me-1"></i>Ditolak</span>
                  @endswitch --}}
                  @if ($report->status === 'pending')
                    <span class="badge-status badge-pending"><i class="bi bi-clock me-1"></i>Pending</span>
                  @elseif ($report->status === 'diproses')
                    <span class="badge-status badge-diproses"><i class="bi bi-clock me-1"></i>Diproses</span>
                  @elseif ($report->status === 'selesai')
                    <span class="badge-status badge-selesai"><i class="bi bi-clock me-1"></i>Selesai</span>
                  @elseif ($report->status === 'ditolak')
                    <span class="badge-status badge-ditolak"><i class="bi bi-clock me-1"></i>Ditolak</span>
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
                  <i class="bi bi-calendar3"></i> {{ $report->created_at->format('d M Y') }}
                </div>
              </div>
              <div class="card-footer-custom">
                @if ($report->status === 'pending')
                  <form action="{{ route('siswa.destroy', $report->id) }}" method="POST" onsubmit="return confirm('Yakin hapus laporan ini?')" style="display:inline">
                      @csrf @method('DELETE')
                      <button class="btn-hapus"><i class="bi bi-trash3"></i> Hapus</button>
                  </form>
                @else
                  <button class="btn-hapus" disabled><i class="bi bi-trash3"></i> Hapus</button>
                @endif
              </div>
            </div>
          </div>

          @empty

          <span class="text-center">Anda belum memiliki laporan</span>

          @endforelse

        {{-- <!-- PAGINATION -->
        <div class="d-flex justify-content-center mt-4">
          <nav>
            <ul class="pagination gap-1">
              <li class="page-item disabled"><a class="page-link" href="{{ $reports->links() }}"><i class="bi bi-chevron-left"></i></a></li>
              <li class="page-item active"><a class="page-link" href="{{ $reports->links() }}">1</a></li>
              <li class="page-item"><a class="page-link" href="{{ $reports->links() }}">2</a></li>
              <li class="page-item"><a class="page-link" href="{{ $reports->links() }}">3</a></li>
              <li class="page-item"><a class="page-link" href="{{ $reports->links() }}">4</a></li>
              <li class="page-item"><a class="page-link" href="{{ $reports->links() }}"><i class="bi bi-chevron-right"></i></a></li>
            </ul>
          </nav>
        </div> --}}
    </main>
</div>

<a class="fab" href="{{ route('siswa.create') }}" aria-label="Tambah Laporan">
    <i class="bi bi-plus-lg"></i>
</a>



@endsection

@push('scripts')
  <script src="{{ asset('templates/js/script.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
@endpush