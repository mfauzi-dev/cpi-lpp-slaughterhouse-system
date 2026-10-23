@extends('layouts.master')

@section('title', 'Detail Productivity')

@section('content')
    <div class="page-section">
        <div class="section-header">
            <h1>Detail Productivity</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('operator.productivity.index') }}">Productivity</a>
                </div>
                <div class="breadcrumb-item active">Detail</div>
            </div>
        </div>

        <div class="section-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">
                    <h4><span class="step-badge">1</span> Informasi Produksi</h4>
                </div>
                <div class="card-body">
                    <div class="batch-highlight">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="batch-label">Production Batch</div>
                                <div class="batch-number">
                                    Shift {{ $productivity->productionBatch->shift ?? '-' }}
                                </div>
                                <div class="batch-product">
                                    Product:
                                    {{ $productivity->material->material_code ?? '-' }}
                                    -
                                    {{ $productivity->material->material_description ?? '-' }}
                                </div>
                            </div>
                            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                <div class="batch-label">Tanggal Produksi</div>
                                <div class="batch-number" style="font-size: 1.1rem;">
                                    {{ $productivity->productionBatch?->tanggal
                                        ? \Carbon\Carbon::parse($productivity->productionBatch->tanggal)->format('d M Y')
                                        : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">Tanggal Produksi</div>
                                <div class="info-value">
                                    {{ $productivity->productionBatch?->tanggal
                                        ? \Carbon\Carbon::parse($productivity->productionBatch->tanggal)->format('d M Y')
                                        : '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">Shift</div>
                                <div class="info-value">
                                    {{ $productivity->productionBatch->shift ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">Nama Product</div>
                                <div class="info-value">
                                    {{ $productivity->material->material_description ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-box">
                                <div class="info-label">Material Bahan Baku</div>
                                <div class="info-value">
                                    {{ $productivity->bahanBaku->material->material_description ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-box">
                                <div class="info-label">Jumlah KG Bahan Baku</div>
                                <div class="info-value">
                                    {{ $productivity->bahanBaku ? number_format($productivity->bahanBaku->jumlah_kg, 2, ',', '.') . ' kg' : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-accent mb-4">
                <div class="card-header">
                    <h4><span class="step-badge">2</span> Jumlah Tenaga Kerja</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-user-friends"></i>
                                </div>
                                <div>
                                    <div class="stat-label">Worker Indirect</div>
                                    <div class="stat-value">
                                        {{ $productivity->worker_indirect !== null
                                            ? number_format((float) $productivity->worker_indirect, 0, ',', '.')
                                            : '-' }}
                                        <small>orang</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div>
                                    <div class="stat-label">Worker Direct</div>
                                    <div class="stat-value">
                                        {{ $productivity->worker_direct !== null
                                            ? number_format((float) $productivity->worker_direct, 0, ',', '.')
                                            : '-' }}
                                        <small>orang</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <div>
                                    <div class="stat-label">Worker Total</div>
                                    <div class="stat-value">
                                        {{ $productivity->worker_total !== null ? number_format((float) $productivity->worker_total, 0, ',', '.') : '-' }}
                                        <small>orang</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4><span class="step-badge">3</span> Data Produktivitas</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-weight-hanging"></i>
                                </div>
                                <div>
                                    <div class="stat-label">Total Weight</div>
                                    <div class="stat-value">
                                        {{ $productivity->total_weight !== null
                                            ? number_format((float) $productivity->total_weight, 2, ',', '.') . ' kg'
                                            : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <div class="stat-label">Total Time</div>
                                    <div class="stat-value">
                                        {{ $productivity->total_time !== null
                                            ? number_format((float) $productivity->total_time, 2, ',', '.') . ' jam'
                                            : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-box">
                                <div class="info-label">Productivity Indirect</div>
                                <div class="info-value">
                                    {{ $productivity->productivity_indirect !== null
                                        ? number_format((float) $productivity->productivity_indirect, 2, ',', '.')
                                        : '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-box">
                                <div class="info-label">Productivity Direct</div>
                                <div class="info-value">
                                    {{ $productivity->productivity_direct !== null
                                        ? number_format((float) $productivity->productivity_direct, 2, ',', '.')
                                        : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-final">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="section-info mb-2 mb-md-0">
                            <i class="fas fa-info-circle"></i>
                            Pastikan data productivity sudah sesuai.
                        </div>
                        <div class="action-buttons">
                            <a href="{{ route('operator.productivity.edit', $productivity->id) }}"
                                class="btn btn-outline-warning" title="Edit">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>

                            <form action="{{ route('operator.productivity.destroy', $productivity->id) }}" method="POST"
                                style="display: inline;"
                                onsubmit="return confirm('Yakin ingin menghapus data productivity ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            </form>

                            <a href="{{ route('operator.productivity.index') }}" class="btn btn-ghost">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
