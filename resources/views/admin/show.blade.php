@extends('layouts.app')

@section('title', 'Detail Laporan')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow mb-3">
            <div class="card-header">
                <h5>{{ $report->title }}</h5>
                <small class="text-muted">Lokasi: {{ $report->location }} | Tanggal: {{ $report->created_at }}</small>
            </div>
            <div class="card-body">
                <p><strong>Status:</strong> 
                    <span class="badge 
                        @if($report->status == 'pending') bg-warning text-dark
                        @elseif($report->status == 'diproses') bg-info
                        @elseif($report->status == 'selesai') bg-success
                        @else bg-danger
                        @endif">
                        {{ ucfirst($report->status) }}
                    </span>
                </p>
                <hr>
                <h6>Deskripsi:</h6>
                <p>{{ $report->description }}</p>
                
                @if($report->images->count() > 0)
                    <h6>Bukti Foto:</h6>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach($report->images as $img)
                            <a href="{{ asset('storage/'.$img->path) }}" target="_blank">
                                <img src="{{ asset('storage/'.$img->path) }}" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Tidak ada bukti foto.</p>
                @endif
            </div>
        </div>

        {{-- Form Ubah Status --}}
        <div class="card shadow mb-3">
            <div class="card-header">Ubah Status</div>
            <div class="card-body">
                @php 
                    $nextStatuses = [];
                    if ($report->status === 'pending' || $report->status === 'diproses') {
                        $nextStatuses = ['selesai' => 'Tandai Selesai', 'ditolak' => 'Tolak Laporan'];
                    }
                @endphp
                
                @if(count($nextStatuses) > 0)
                    <form action="{{ route('admin.status', $report->id) }}" method="POST">
                        @csrf
                        <div class="btn-group w-100">
                            @foreach($nextStatuses as $value => $label)
                                <button type="submit" name="status" value="{{ $value }}" 
                                    class="btn {{ $value === 'selesai' ? 'btn-success' : 'btn-danger' }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </form>
                @else
                    <p class="text-muted text-center mb-0">Status sudah final ({{ ucfirst($report->status) }}). Tidak dapat diubah.</p> 
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header">Tanggapan</div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                @forelse($report->comments as $comment)
                    <div class="border-bottom pb-2 mb-2">
                        <small class="fw-bold">{{ $comment->user->name }}</small>
                        <small class="text-muted float-end">{{ $comment->created_at->format('H:i d/m') }}</small>
                        <p class="mb-0 small">{{ $comment->content }}</p>
                    </div>
                @empty
                    <p class="text-muted small">Belum ada tanggapan.</p>
                @endforelse
            </div>
            <div class="card-footer">
                <form action="{{ route('admin.comment', $report->id) }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="content" class="form-control" placeholder="Tulis tanggapan..." required>
                        <button class="btn btn-primary" type="submit">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
        
        {{-- Tombol Hapus Laporan --}}
        <form action="{{ route('admin.destroy', $report->id) }}" method="POST" class="mt-3" onsubmit="return confirm('Hapus laporan ini permanen?')">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger btn-sm w-100">Hapus Laporan Ini</button>
        </form>
    </div>
</div>
@endsection