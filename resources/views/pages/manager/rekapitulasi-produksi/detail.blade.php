@extends('layouts.master')

@section('title', 'Detail Rekapitulasi Produksi')

@section('content')
    <div class="page-section">
        <div class="section-header">
            <h1>Detail Rekapitulasi Produksi dan Monitoring Mesin</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('manager.rekapitulasi-produksi.index') }}">Rekapitulasi Produksi</a>
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
                                    Shift {{ $rekapitulasiProduksi->productionBatch->shift ?? '-' }}
                                </div>
                                <div class="batch-product">
                                    Mesin: {{ $rekapitulasiProduksi->mesin ?? '-' }}
                                </div>
                            </div>
                            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                <div class="batch-label">Tanggal Produksi</div>
                                <div class="batch-number" style="font-size: 1.1rem;">
                                    {{ optional($rekapitulasiProduksi->productionBatch)->tanggal
                                        ? \Carbon\Carbon::parse($rekapitulasiProduksi->productionBatch->tanggal)->format('d M Y')
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
                                    {{ optional($rekapitulasiProduksi->productionBatch)->tanggal
                                        ? \Carbon\Carbon::parse($rekapitulasiProduksi->productionBatch->tanggal)->format('d M Y')
                                        : '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">Shift</div>
                                <div class="info-value">
                                    {{ $rekapitulasiProduksi->productionBatch->shift ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">Nama Mesin</div>
                                <div class="info-value">
                                    {{ $rekapitulasiProduksi->mesin ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4><span class="step-badge">2</span> Waktu Operasional dan Kapasitas</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-play-circle"></i></div>
                                <div>
                                    <div class="stat-label">Waktu Start</div>
                                    <div class="stat-value">
                                        {{ $rekapitulasiProduksi->waktu_start ? \Carbon\Carbon::parse($rekapitulasiProduksi->waktu_start)->format('H:i') : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-stop-circle"></i></div>
                                <div>
                                    <div class="stat-label">Waktu Stop</div>
                                    <div class="stat-value">
                                        {{ $rekapitulasiProduksi->waktu_stop ? \Carbon\Carbon::parse($rekapitulasiProduksi->waktu_stop)->format('H:i') : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                                <div>
                                    <div class="stat-label">Operation Time</div>
                                    <div class="stat-value">
                                        {{ $rekapitulasiProduksi->operation_time !== null ? number_format((float) $rekapitulasiProduksi->operation_time, 2, ',', '.') . ' jam' : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-tachometer-alt"></i></div>
                                <div>
                                    <div class="stat-label">Speed / Kapasitas</div>
                                    <div class="stat-value">
                                        {{ $rekapitulasiProduksi->speed_kapasitas !== null ? number_format((float) $rekapitulasiProduksi->speed_kapasitas, 2, ',', '.') : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">Aktual</div>
                                <div class="info-value">
                                    {{ $rekapitulasiProduksi->aktual !== null ? number_format((float) $rekapitulasiProduksi->aktual, 2, ',', '.') : '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">Pencapaian</div>
                                <div class="info-value">
                                    {{ $rekapitulasiProduksi->persen_pencapaian !== null ? number_format((float) $rekapitulasiProduksi->persen_pencapaian, 2, ',', '.') . '%' : '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">Output</div>
                                <div class="info-value">
                                    {{ $rekapitulasiProduksi->output_kg !== null ? number_format((float) $rekapitulasiProduksi->output_kg, 2, ',', '.') . ' kg' : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4><span class="step-badge">3</span> Downtime dan Penyebab</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                                <div>
                                    <div class="stat-label">Downtime</div>
                                    <div class="stat-value">
                                        {{ $rekapitulasiProduksi->downtime !== null ? number_format((float) $rekapitulasiProduksi->downtime, 2, ',', '.') . ' menit' : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-box">
                                <div class="info-label">Penyebab Downtime</div>
                                <div class="info-value" style="white-space: pre-line;">
                                    {{ $rekapitulasiProduksi->penyebab_downtime ?: '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4><span class="step-badge">4</span> Tindakan Perbaikan dan PIC</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <div class="info-box">
                                <div class="info-label">Tindakan Perbaikan</div>
                                <div class="info-value" style="white-space: pre-line;">
                                    {{ $rekapitulasiProduksi->tindakan_perbaikan ?: '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">PIC Operator</div>
                                <div class="info-value">{{ $rekapitulasiProduksi->pic_operator ?? '-' }}</div>
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
                            Pastikan data rekapitulasi produksi dan monitoring mesin sudah sesuai.
                        </div>
                        <div class="action-buttons">
                            <a href="{{ route('manager.rekapitulasi-produksi.index') }}" class="btn btn-ghost">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
