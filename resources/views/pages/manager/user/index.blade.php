@extends('layouts.master')

@section('title', 'Data User')

@section('content')
    <div class="page-section">
        <div class="section-header">
            <h1>Data User</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="{{ route('manager.user.index') }}">Data User</a>
                </div>
                <div class="breadcrumb-item">Table</div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('danger'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('danger') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="mb-4">
            <a href="{{ route('manager.user.create') }}" class="btn btn-add">
                <i class="fas fa-plus mr-1"></i>
                Tambah User
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h4>
                    <i class="fas fa-filter mr-2"></i>
                    Filter Data
                </h4>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('manager.user.index') }}">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="filter-label">Pencarian User</label>
                                <input type="text" name="search" class="form-control"
                                    placeholder="Cari nama, email, atau role..." value="{{ request('search') }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="filter-label">Jumlah Data</label>
                                <select name="size" class="form-control">
                                    <option value="10" {{ request('size', 10) == 10 ? 'selected' : '' }}>10 Data
                                    </option>
                                    <option value="25" {{ request('size') == 25 ? 'selected' : '' }}>25 Data</option>
                                    <option value="50" {{ request('size') == 50 ? 'selected' : '' }}>50 Data</option>
                                    <option value="100" {{ request('size') == 100 ? 'selected' : '' }}>100 Data</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center" style="gap: .5rem;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search mr-1"></i>
                            Tampilkan
                        </button>

                        <a href="{{ route('manager.user.index') }}" class="btn btn-ghost">
                            <i class="fas fa-sync-alt mr-1"></i>
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-accent">
            <div class="card-header">
                <h4>
                    <i class="fas fa-users mr-2"></i>
                    Data User
                </h4>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-wide">
                        <thead>
                            <tr>
                                <th class="text-center" width="70">No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th class="text-center" width="150">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($users as $index => $user)
                                <tr>
                                    <td class="text-center">
                                        {{ $users->firstItem() + $index }}
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $user->role?->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="action-buttons">
                                            {{-- <a href="{{ route('manager.user.edit', $user->id) }}"
                                                class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a> --}}

                                            <form action="{{ route('manager.user.destroy', $user->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin hapus data user ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <i class="fas fa-users d-block"></i>
                                            <div>Belum ada data user.</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white border-top">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
