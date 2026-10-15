@extends('layouts.master')

@section('title', 'Import Material')

@section('content')
    <div class="page-section">
        <div class="section-header">
            <h1>Material</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('operator.material.index') }}">
                        Material
                    </a>
                </div>
                <div class="breadcrumb-item active">
                    Import Material
                </div>
            </div>
        </div>

        <div class="section-body">
            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Import Material gagal.</strong>
                    <div class="mt-2">{{ session('error') }}</div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Periksa kembali file yang dipilih.</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('operator.material.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card mb-4">
                    <div class="card-header">
                        <h4>
                            <span class="step-badge">1</span>
                            File Import Material
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-1"></i>
                            Pastikan file Excel atau CSV memiliki kolom berikut:
                            <ul class="mb-0 mt-2">
                                <li><code>material_code</code></li>
                                <li><code>material_description</code></li>
                            </ul>
                        </div>

                        <div class="form-group mb-0">
                            <label class="filter-label">File Excel / CSV</label>
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror"
                                accept=".xlsx,.xls,.csv" required>

                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <small class="form-text text-muted">
                                Format yang didukung: XLSX, XLS, dan CSV.
                            </small>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="section-info mb-2 mb-md-0">
                                <i class="fas fa-info-circle"></i>
                                Periksa format dan isi file sebelum melakukan import.
                            </div>

                            <div class="action-buttons">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-file-import mr-1"></i>
                                    Import
                                </button>

                                <a href="{{ route('operator.material.index') }}" class="btn btn-ghost">
                                    <i class="fas fa-arrow-left mr-1"></i>
                                    Batal
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
