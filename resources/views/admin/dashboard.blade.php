@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<h3 class="mb-4">Recent Reports</h3>

<div class="card shadow">
    <div class="card-body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pelapor</th>
                    <th>Judul</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                <tr>
                    <td>{{ $report->id }}</td>
                    <td>
                        @if($report->is_anonymous)
                            <span class="text-muted fst-italic">Anonim</span>
                        @else
                            {{ $report->user->name ?? 'Akun Terhapus' }}
                        @endif
                    </td>
                    <td>{{ $report->title }}</td>
                    <td>
                        @if($report->status === 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($report->status === 'diproses')
                            <span class="badge bg-info">Diproses</span>
                        @elseif($report->status === 'selesai')
                            <span class="badge bg-success">Selesai</span>
                        @elseif($report->status === 'ditolak')
                            <span class="badge bg-danger">Ditolak</span>
                        @else
                            <span class="badge bg-secondary">Unknown</span>
                        @endif
                    </td>
                    <td>{{ $report->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.show', $report->id) }}" class="btn btn-sm btn-primary">Detail & Proses</a>
                        <form action="{{ route('admin.destroy', $report->id) }}" method="POST" class="mt-3" onsubmit="return confirm('Hapus laporan ini permanen?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm w-100">Hapus Laporan Ini</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">Belum ada laporan masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection