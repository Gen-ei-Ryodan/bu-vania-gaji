@extends('layouts.app')

@section('title', 'Manajemen Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Manajemen Admin</h1>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Tambah Admin</h5>
        <form method="POST" action="{{ route('admin-users.store') }}" class="row g-3">
            @csrf
            <div class="col-md-4">
                <label class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Username (Email)</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Ulangi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Admin
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Daftar Admin</h5>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Dibuat</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $admin)
                    <tr>
                        <td>{{ $admins->firstItem() + $loop->index }}</td>
                        <td>{{ $admin->name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>{{ optional($admin->created_at)->format('d/m/Y H:i') }}</td>
                        <td>
                            <form action="{{ route('admin-users.destroy', $admin) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus admin ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada admin</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $admins->links() }}
        </div>
    </div>
</div>
@endsection
