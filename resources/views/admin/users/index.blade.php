@extends('layouts.app')

@section('title', 'List User')

@section('content')
    <main class="page-body">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->trashed())
                                <span class="badge badge-dihapus">Dihapus</span>
                            @else
                                <span class="badge badge-selesai">Aktif</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn-hapus"
                                    onclick="return confirm('Hapus akun ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $users->links() }}

    </main>
@endsection
