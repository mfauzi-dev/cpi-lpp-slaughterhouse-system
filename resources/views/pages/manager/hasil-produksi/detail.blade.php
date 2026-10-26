@extends('layouts.master')

@section('title', 'Detail Hasil Produksi')

@section('content')
    <div class="page-section">
        <div class="section-header">
            <h1>Detail Hasil Produksi</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('manager.hasil-produksi.index') }}">Hasil Produksi</a>
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
                                <div class="batch-label">No. PO</div>
                                <div class="batch-number">{{ $hasilProduksi->no_po ?? '-' }}</div>
                                <div class="batch-product">
                                    Material: {{ $hasilProduksi->material->material_description ?? '-' }}
                                </div>
                            </div>
                            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                <div class="batch-label">Tanggal Produksi</div>
                                <div class="batch-number" style="font-size: 1.1rem;">
                                    {{ $hasilProduksi->productionBatch?->tanggal
                                        ? \Carbon\Carbon::parse($hasilProduksi->productionBatch->tanggal)->format('d M Y')
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
                                    {{ optional($hasilProduksi->productionBatch)->tanggal
                                        ? \Carbon\Carbon::parse($hasilProduksi->productionBatch->tanggal)->format('d M Y')
                                        : '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">Shift</div>
                                <div class="info-value">{{ $hasilProduksi->productionBatch->shift ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">Conveyor</div>
                                <div class="info-value">{{ $hasilProduksi->productionBatch->conveyor ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">No. PO</div>
                                <div class="info-value">{{ $hasilProduksi->no_po ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">Cost Center Name</div>
                                <div class="info-value">{{ $hasilProduksi->cost_center_name }}</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">Absen Worker</div>
                                <div class="info-value">{{ $hasilProduksi->productionBatch->absen_worker ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-box">
                                <div class="info-label">Kode Material</div>
                                <div class="info-value">{{ $hasilProduksi->material->material_code ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-box">
                                <div class="info-label">Deskripsi Material</div>
                                <div class="info-value">{{ $hasilProduksi->material->material_description ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-box">
                                <div class="info-label">Material Bahan Baku</div>
                                <div class="info-value">
                                    {{ $hasilProduksi->bahanBaku->material->material_description ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-box">
                                <div class="info-label">Jumlah KG Bahan Baku</div>
                                <div class="info-value">
                                    {{ $hasilProduksi->bahanBaku ? number_format($hasilProduksi->bahanBaku->jumlah_kg, 2, ',', '.') . ' kg' : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-box">
                                <div class="info-label">Tanggal Transfer</div>
                                <div class="info-value">
                                    {{ optional($hasilProduksi->productionBatch)->tanggal_transfer
                                        ? \Carbon\Carbon::parse($hasilProduksi->productionBatch->tanggal_transfer)->format('d M Y')
                                        : '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-box">
                                <div class="info-label">Tanggal Proses</div>
                                <div class="info-value">
                                    {{ optional($hasilProduksi->productionBatch)->tanggal_proses
                                        ? \Carbon\Carbon::parse($hasilProduksi->productionBatch->tanggal_proses)->format('d M Y')
                                        : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4><span class="step-badge">2</span> Hasil Produksi</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-box"></i></div>
                                <div>
                                    <div class="stat-label">Domestik</div>
                                    <div class="stat-value">
                                        {{ $hasilProduksi->domestik_kg !== null ? number_format((float) $hasilProduksi->domestik_kg, 2, ',', '.') . ' kg' : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-truck"></i></div>
                                <div>
                                    <div class="stat-label">Further</div>
                                    <div class="stat-value">
                                        {{ $hasilProduksi->further_kg !== null ? number_format((float) $hasilProduksi->further_kg, 2, ',', '.') . ' kg' : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-snowflake"></i></div>
                                <div>
                                    <div class="stat-label">Frozen</div>
                                    <div class="stat-value">
                                        {{ $hasilProduksi->frozen_kg !== null ? number_format((float) $hasilProduksi->frozen_kg, 2, ',', '.') . ' kg' : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-weight-hanging"></i></div>
                                <div>
                                    <div class="stat-label">RM</div>
                                    <div class="stat-value">
                                        {{ $hasilProduksi->rm !== null ? number_format((float) $hasilProduksi->rm, 2, ',', '.') . ' kg' : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-boxes"></i></div>
                                <div>
                                    <div class="stat-label">Total Bag</div>
                                    <div class="stat-value">
                                        {{ $hasilProduksi->total_bag !== null ? number_format((float) $hasilProduksi->total_bag, 0, ',', '.') . ' bag' : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-weight-hanging"></i></div>
                                <div>
                                    <div class="stat-label">Total Berat</div>
                                    <div class="stat-value">
                                        {{ $hasilProduksi->total_kg !== null ? number_format((float) $hasilProduksi->total_kg, 2, ',', '.') . ' kg' : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-percentage"></i></div>
                                <div>
                                    <div class="stat-label">Yield</div>
                                    <div class="stat-value">
                                        {{ $hasilProduksi->yield_persen !== null ? number_format((float) $hasilProduksi->yield_persen, 2, ',', '.') . '%' : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4><span class="step-badge">3</span> Loss & Pickup</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                                <div>
                                    <div class="stat-label">Loss</div>
                                    <div class="stat-value">
                                        {{ $hasilProduksi->loss_kg !== null ? number_format((float) $hasilProduksi->loss_kg, 2, ',', '.') . ' kg' : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-percent"></i></div>
                                <div>
                                    <div class="stat-label">Persentase Loss</div>
                                    <div class="stat-value">
                                        {{ $hasilProduksi->loss_persen !== null ? number_format((float) $hasilProduksi->loss_persen, 2, ',', '.') . '%' : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-hand-holding"></i></div>
                                <div>
                                    <div class="stat-label">Pickup</div>
                                    <div class="stat-value">
                                        {{ $hasilProduksi->pickup_kg !== null ? number_format((float) $hasilProduksi->pickup_kg, 2, ',', '.') . ' kg' : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-percent"></i></div>
                                <div>
                                    <div class="stat-label">Persentase Pickup</div>
                                    <div class="stat-value">
                                        {{ $hasilProduksi->pickup_persen !== null ? number_format((float) $hasilProduksi->pickup_persen, 2, ',', '.') . '%' : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4><span class="step-badge">4</span> Pengecekan Suhu & Packaging</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-box">
                                <div class="info-label">Jam Pengecekan Suhu</div>
                                <div class="info-value">
                                    {{ $hasilProduksi->pengecekan_suhu_jam ? substr($hasilProduksi->pengecekan_suhu_jam, 0, 5) : '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-box">
                                <div class="info-label">Suhu</div>
                                <div class="info-value">
                                    {{ $hasilProduksi->pengecekan_suhu_suhu !== null ? number_format((float) $hasilProduksi->pengecekan_suhu_suhu, 2, ',', '.') . ' °C' : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-box">
                                <div class="info-label">Nama Kemasan</div>
                                <div class="info-value">{{ $hasilProduksi->packaging_nama_kemasan ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-box">
                                <div class="info-label">Jumlah Packaging</div>
                                <div class="info-value">
                                    {{ $hasilProduksi->packaging_jumlah_kg !== null ? number_format((float) $hasilProduksi->packaging_jumlah_kg, 2, ',', '.') . ' kg' : '-' }}
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
                            Informasi detail hasil produksi.
                        </div>
                        <div class="action-buttons">
                            <a href="{{ route('manager.hasil-produksi.index') }}" class="btn btn-ghost">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
