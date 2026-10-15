@extends('layouts.master')

@section('title', 'Material')

@section('content')
    <div class="page-section">
        <div class="section-header">
            <h1>Material</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="{{ route('operator.material.index') }}">
                        Material
                    </a>
                </div>
                <div class="breadcrumb-item">
                    Table
                </div>
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

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="mb-4 d-flex align-items-center flex-wrap" style="gap: .5rem;">
            <a href="{{ route('operator.material.create') }}" class="btn btn-add">
                <i class="fas fa-plus mr-1"></i>
                Tambah Material
            </a>

            <a href="{{ route('operator.material.import') }}" class="btn btn-add">
                <i class="fas fa-file-import mr-1"></i>
                Import Material
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
                <form method="GET" action="{{ route('operator.material.index') }}">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="filter-label">
                                Material Code
                            </label>
                            <input type="text" name="search" class="form-control"
                                placeholder="Cari kode atau deskripsi material" value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="d-flex align-items-center" style="gap: .5rem;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search mr-1"></i>
                            Tampilkan
                        </button>

                        <a href="{{ route('operator.material.index') }}" class="btn btn-ghost">
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
                    <i class="fas fa-boxes mr-2"></i>
                    Data Material
                </h4>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-wide">
                        <thead>
                            <tr>
                                <th width="60">No.</th>
                                <th>Material Code</th>
                                <th>Material Description</th>
                                <th width="150" class="text-center">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($materials as $index => $material)
                                <tr>
                                    <td>
                                        {{ $materials->firstItem() + $index }}
                                    </td>

                                    <td>
                                        @if ($material->material_code)
                                            <span class="badge-soft">
                                                {{ $material->material_code }}
                                            </span>
                                        @else
                                            <span class="value-empty">-</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($material->material_description)
                                            {{ $material->material_description }}
                                        @else
                                            <span class="value-empty">-</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <div class="action-buttons">
                                            <a href="{{ route('operator.material.edit', $material->id) }}"
                                                class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('operator.material.destroy', $material->id) }}"
                                                method="POST" style="display: inline;"
                                                onsubmit="return confirm('Yakin ingin menghapus data Material ini?');">
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
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <i class="fas fa-boxes d-block"></i>
                                            <div>
                                                Belum ada data Material.
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white border-top">
                {{ $materials->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
