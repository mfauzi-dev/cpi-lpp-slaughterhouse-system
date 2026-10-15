@extends('layouts.master')
@section('title', 'Detail Production Batch')

@push('addon-style')
    <style>
        .pb-page {
            --pb-primary: #1B4B43;
            --pb-primary-light: #E8F0EE;
            --pb-accent: #D98C3D;
            --pb-accent-light: #FFF3E5;
            --pb-border: #E3E7E1;
            --pb-text: #1F2A24;
            --pb-muted: #5B6A62;
            --pb-soft: #F7F9F7;
        }

        .pb-page .section-header h1 {
            font-weight: 700;
            letter-spacing: -0.01em;
            color: var(--pb-text);
        }

        .pb-page .section-lead {
            color: var(--pb-muted);
            font-size: 0.925rem;
            margin: 0.25rem 0 1.5rem;
        }

        .pb-page .card {
            border: none;
            border-left: 4px solid var(--pb-primary);
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(15, 30, 25, 0.06), 0 1px 2px rgba(15, 30, 25, 0.04);
            overflow: hidden;
        }

        .pb-page .card-header {
            background: #fff;
            border-bottom: 1px solid var(--pb-border);
            padding: 1rem 1.5rem;
        }

        .pb-page .card-header h4 {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--pb-text);
            display: flex;
            align-items: center;
        }

        .pb-page .card-header h4 i {
            color: var(--pb-primary);
        }

        .pb-page .card-body {
            padding: 1.5rem;
        }

        .pb-page .step-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: var(--pb-primary);
            color: #fff;
            font-size: 0.8rem;
            font-weight: 700;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }

        .pb-page .batch-highlight {
            background: linear-gradient(135deg, var(--pb-primary), #28665A);
            color: #fff;
            border-radius: 9px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.25rem;
        }

        .pb-page .batch-highlight .batch-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            opacity: 0.75;
            font-weight: 600;
        }

        .pb-page .batch-highlight .batch-number {
            font-size: 1.45rem;
            font-weight: 700;
            margin-top: 0.2rem;
        }

        .pb-page .batch-highlight .batch-product {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-top: 0.15rem;
        }

        .pb-page .info-box {
            height: 100%;
            background: var(--pb-soft);
            border: 1px solid var(--pb-border);
            border-radius: 8px;
            padding: 1rem 1.1rem;
        }

        .pb-page .info-label {
            color: var(--pb-muted);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.35rem;
        }

        .pb-page .info-value {
            color: var(--pb-text);
            font-size: 0.95rem;
            font-weight: 600;
            min-height: 22px;
        }

        .pb-page .stat-card {
            height: 100%;
            background: #fff;
            border: 1px solid var(--pb-border);
            border-radius: 9px;
            padding: 1rem;
            display: flex;
            align-items: center;
        }

        .pb-page .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--pb-primary-light);
            color: var(--pb-primary);
            font-size: 1.05rem;
            margin-right: 0.85rem;
            flex-shrink: 0;
        }

        .pb-page .stat-label {
            color: var(--pb-muted);
            font-size: 0.73rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .pb-page .stat-value {
            color: var(--pb-text);
            font-size: 1rem;
            font-weight: 700;
            margin-top: 0.15rem;
        }

        .pb-page .process-card {
            height: 100%;
            background: #fff;
            border: 1px solid var(--pb-border);
            border-radius: 9px;
            padding: 1rem 1.1rem;
        }

        .pb-page .process-label {
            color: var(--pb-muted);
            font-size: 0.73rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin-bottom: 0.3rem;
        }

        .pb-page .process-value {
            color: var(--pb-text);
            font-size: 0.95rem;
            font-weight: 700;
            min-height: 20px;
        }

        .pb-page .process-value.text-alert {
            color: var(--pb-accent);
        }

        .pb-page .process-empty {
            text-align: center;
            padding: 1.5rem 1rem;
            background: var(--pb-soft);
            border: 1px dashed var(--pb-border);
            border-radius: 8px;
            color: var(--pb-muted);
        }

        .pb-page .process-empty i {
            font-size: 1.4rem;
            opacity: .5;
            margin-bottom: .4rem;
        }

        .pb-page .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .pb-page .table {
            margin-bottom: 0;
        }

        .pb-page .table thead th {
            background: var(--pb-primary-light);
            color: var(--pb-primary);
            font-weight: 600;
            font-size: 0.78rem;
            border-bottom: none;
            vertical-align: middle;
            white-space: nowrap;
            padding: 0.8rem 0.9rem;
        }

        .pb-page .table td {
            vertical-align: middle;
            font-size: 0.85rem;
            color: var(--pb-text);
            padding: 0.8rem 0.9rem;
            white-space: nowrap;
        }

        .pb-page .table-bordered td,
        .pb-page .table-bordered th {
            border-color: var(--pb-border);
        }

        .pb-page .table tbody tr:hover {
            background: #FAFBF9;
        }

        .pb-page .badge-soft {
            display: inline-block;
            background: var(--pb-primary-light);
            color: var(--pb-primary);
            border-radius: 20px;
            padding: 0.3rem 0.65rem;
            font-size: 0.72rem;
            font-weight: 600;
        }

        .pb-page .btn-ghost {
            color: var(--pb-muted);
            background: transparent;
            border: 1px solid var(--pb-border);
        }

        .pb-page .btn-ghost:hover {
            background: #F3F5F3;
            color: var(--pb-text);
        }

        .pb-page .btn-excel {
            color: #1B4B43;
            background: var(--pb-primary-light);
            border: 1px solid #C9DCD7;
        }

        .pb-page .btn-excel:hover {
            background: #DCEAE6;
            color: #153B35;
        }

        .pb-page .btn-pdf {
            color: #A94442;
            background: #FCEEEE;
            border: 1px solid #F1D0D0;
        }

        .pb-page .btn-pdf:hover {
            background: #F8E1E1;
            color: #8C3735;
        }

        @media (max-width: 767.98px) {
            .pb-page .card-body {
                padding: 1rem;
            }

            .pb-page .card-header {
                padding: 0.9rem 1rem;
            }

            .pb-page .batch-highlight {
                padding: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="pb-page">
        <div class="section-header">
            <h1>Production Batch</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('manager.production-batch.index') }}">
                        Production Batch
                    </a>
                </div>

                <div class="breadcrumb-item active">
                    Detail
                </div>
            </div>
        </div>

        <div class="section-body">
            {{-- 1. INFORMASI PRODUCTION BATCH --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h4>
                        <span class="step-badge">1</span>
                        Informasi Production Batch
                    </h4>
                </div>

                <div class="card-body">
                    <div class="batch-highlight">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="batch-label">Production Batch</div>
                                <div class="batch-number">
                                    {{ $productionBatch->no_po }}
                                </div>
                                <div class="batch-product">
                                    Shift {{ $productionBatch->shift ?? '-' }} &middot;
                                    Conveyor {{ $productionBatch->conveyor ?? '-' }}
                                </div>
                            </div>

                            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                <div class="batch-label">Tanggal</div>
                                <div class="batch-number" style="font-size: 1.1rem;">
                                    {{ $productionBatch->tanggal ? $productionBatch->tanggal->format('d M Y') : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="info-box">
                                <div class="info-label">Tanggal</div>
                                <div class="info-value">
                                    {{ $productionBatch->tanggal ? $productionBatch->tanggal->format('d M Y') : '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="info-box">
                                <div class="info-label">Shift</div>
                                <div class="info-value">
                                    {{ $productionBatch->shift ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="info-box">
                                <div class="info-label">Conveyor</div>
                                <div class="info-value">
                                    {{ $productionBatch->conveyor ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="info-box">
                                <div class="info-label">Absen Worker</div>
                                <div class="info-value">
                                    {{ $productionBatch->absen_worker ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="info-box">
                                <div class="info-label">Cost Center Name</div>
                                <div class="info-value">
                                    @if ($productionBatch->cost_center_name)
                                        <span class="badge-soft">
                                            {{ $productionBatch->cost_center_name }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="info-box">
                                <div class="info-label">No. PO</div>
                                <div class="info-value">
                                    {{ $productionBatch->no_po ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="info-box">
                                <div class="info-label">Tanggal Transfer</div>
                                <div class="info-value">
                                    {{ $productionBatch->tanggal_transfer ? $productionBatch->tanggal_transfer->format('d M Y') : '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="info-box">
                                <div class="info-label">Tanggal Proses</div>
                                <div class="info-value">
                                    {{ $productionBatch->tanggal_proses ? $productionBatch->tanggal_proses->format('d M Y') : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. BAHAN BAKU --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h4>
                        <span class="step-badge">2</span>
                        Bahan Baku
                    </h4>
                </div>

                <div class="card-body p-0">
                    @if ($productionBatch->bahanBakus->count())
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Material</th>
                                        <th class="text-right">Jumlah Bag</th>
                                        <th class="text-right">Jumlah Kg</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productionBatch->bahanBakus as $bahanBaku)
                                        <tr>
                                            <td>
                                                {{ $bahanBaku->material->material_description ?? '-' }}
                                            </td>
                                            <td class="text-right">
                                                {{ $bahanBaku->jumlah_bag ?? '-' }}
                                            </td>
                                            <td class="text-right">
                                                {{ $bahanBaku->jumlah_kg !== null ? number_format((float) $bahanBaku->jumlah_kg, 2, ',', '.') . ' Kg' : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="process-empty">
                            <i class="fas fa-boxes d-block"></i>
                            Belum ada data Bahan Baku untuk production batch ini.
                        </div>
                    @endif
                </div>
            </div>

            {{-- 3. DATA HASIL PRODUKSI --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h4>
                        <span class="step-badge">3</span>
                        Data Hasil Produksi
                    </h4>
                </div>

                <div class="card-body">
                    @if ($productionBatch->dataHasilProduksis->count())
                        @foreach ($productionBatch->dataHasilProduksis as $dataHasil)
                            <div class="row {{ !$loop->last ? 'mb-4 pb-4' : '' }}"
                                style="{{ !$loop->last ? 'border-bottom: 1px dashed var(--pb-border);' : '' }}">
                                <div class="col-12 mb-2">
                                    <span class="badge-soft">
                                        {{ $dataHasil->material->material_description ?? 'Material -' }}
                                    </span>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="process-card">
                                        <div class="process-label">Domestik</div>
                                        <div class="process-value">
                                            {{ $dataHasil->domestik_kg !== null ? number_format((float) $dataHasil->domestik_kg, 2, ',', '.') . ' Kg' : '-' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="process-card">
                                        <div class="process-label">Further</div>
                                        <div class="process-value">
                                            {{ $dataHasil->further_kg !== null ? number_format((float) $dataHasil->further_kg, 2, ',', '.') . ' Kg' : '-' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="process-card">
                                        <div class="process-label">Frozen</div>
                                        <div class="process-value">
                                            {{ $dataHasil->frozen_kg !== null ? number_format((float) $dataHasil->frozen_kg, 2, ',', '.') . ' Kg' : '-' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="process-card">
                                        <div class="process-label">RM</div>
                                        <div class="process-value">
                                            {{ $dataHasil->rm !== null ? number_format((float) $dataHasil->rm, 2, ',', '.') : '-' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="process-card">
                                        <div class="process-label">Total Bag</div>
                                        <div class="process-value">
                                            {{ $dataHasil->total_bag ?? '-' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="process-card">
                                        <div class="process-label">Total Kg</div>
                                        <div class="process-value">
                                            {{ $dataHasil->total_kg !== null ? number_format((float) $dataHasil->total_kg, 2, ',', '.') . ' Kg' : '-' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="process-card">
                                        <div class="process-label">Loss</div>
                                        <div
                                            class="process-value {{ $dataHasil->loss_persen !== null && (float) $dataHasil->loss_persen > 0 ? 'text-alert' : '' }}">
                                            {{ $dataHasil->loss_kg !== null ? number_format((float) $dataHasil->loss_kg, 2, ',', '.') . ' Kg' : '-' }}
                                            @if ($dataHasil->loss_persen !== null)
                                                ({{ number_format((float) $dataHasil->loss_persen, 2, ',', '.') }}%)
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="process-card">
                                        <div class="process-label">Pickup</div>
                                        <div class="process-value">
                                            {{ $dataHasil->pickup_kg !== null ? number_format((float) $dataHasil->pickup_kg, 2, ',', '.') . ' Kg' : '-' }}
                                            @if ($dataHasil->pickup_persen !== null)
                                                ({{ number_format((float) $dataHasil->pickup_persen, 2, ',', '.') }}%)
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="process-card">
                                        <div class="process-label">Pengecekan Suhu (Jam)</div>
                                        <div class="process-value">
                                            {{ $dataHasil->pengecekan_suhu_jam ?? '-' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="process-card">
                                        <div class="process-label">Pengecekan Suhu</div>
                                        <div class="process-value">
                                            {{ $dataHasil->pengecekan_suhu_suhu !== null ? number_format((float) $dataHasil->pengecekan_suhu_suhu, 2, ',', '.') . ' &deg;C' : '-' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="process-card">
                                        <div class="process-label">Nama Kemasan</div>
                                        <div class="process-value">
                                            {{ $dataHasil->packaging_nama_kemasan ?? '-' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="process-card">
                                        <div class="process-label">Jumlah Kemasan</div>
                                        <div class="process-value">
                                            {{ $dataHasil->packaging_jumlah_kg !== null ? number_format((float) $dataHasil->packaging_jumlah_kg, 2, ',', '.') . ' Kg' : '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="process-empty">
                            <i class="fas fa-chart-pie d-block"></i>
                            Belum ada Data Hasil Produksi untuk production batch ini.
                        </div>
                    @endif
                </div>
            </div>

            {{-- 4. METAL DETECTOR --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h4>
                        <span class="step-badge">4</span>
                        Metal Detector
                    </h4>
                </div>

                <div class="card-body">
                    @forelse ($productionBatch->metalDetectors as $metalDetector)
                        <div class="table-responsive {{ !$loop->last ? 'mb-4' : '' }}">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Jenis Pemeriksaan</th>
                                        <th class="text-center">Depan Produk</th>
                                        <th class="text-center">Tengah Produk</th>
                                        <th class="text-center">Belakang Produk</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ([
            'FE' => ['fe_depan', 'fe_tengah', 'fe_belakang'],
            'Non FE' => ['non_fe_depan', 'non_fe_tengah', 'non_fe_belakang'],
            'SUS 316' => ['sus_316_depan', 'sus_316_tengah', 'sus_316_belakang'],
        ] as $label => $fields)
                                        <tr>
                                            <th>{{ $label }}</th>
                                            @foreach ($fields as $field)
                                                <td class="text-center">
                                                    @if ($metalDetector->$field === 'Terdeteksi')
                                                        <span class="badge badge-success">
                                                            <i class="fas fa-check-circle mr-1"></i>
                                                            Terdeteksi
                                                        </span>
                                                    @elseif ($metalDetector->$field === 'Tidak terdeteksi')
                                                        <span class="badge badge-danger">
                                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                                            Tidak terdeteksi
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @empty
                        <div class="process-empty">
                            <i class="fas fa-search d-block"></i>
                            Belum ada data Metal Detector untuk production batch ini.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- 5. PRODUCTIVITY --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h4>
                        <span class="step-badge">5</span>
                        Productivity
                    </h4>
                </div>

                <div class="card-body">
                    @if ($productionBatch->productivities->count())
                        @foreach ($productionBatch->productivities as $productivity)
                            <div class="row {{ !$loop->last ? 'mb-4 pb-4' : '' }}"
                                style="{{ !$loop->last ? 'border-bottom: 1px dashed var(--pb-border);' : '' }}">
                                <div class="col-12 mb-2">
                                    <span class="badge-soft">
                                        {{ $productivity->material->material_description ?? 'Material -' }}
                                    </span>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="stat-card">
                                        <div class="stat-icon">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div>
                                            <div class="stat-label">Worker Indirect</div>
                                            <div class="stat-value">
                                                {{ $productivity->worker_indirect ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="stat-card">
                                        <div class="stat-icon">
                                            <i class="fas fa-user-cog"></i>
                                        </div>
                                        <div>
                                            <div class="stat-label">Worker Direct</div>
                                            <div class="stat-value">
                                                {{ $productivity->worker_direct ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="stat-card">
                                        <div class="stat-icon">
                                            <i class="fas fa-user-friends"></i>
                                        </div>
                                        <div>
                                            <div class="stat-label">Worker Total</div>
                                            <div class="stat-value">
                                                {{ $productivity->worker_total ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="stat-card">
                                        <div class="stat-icon">
                                            <i class="fas fa-weight-hanging"></i>
                                        </div>
                                        <div>
                                            <div class="stat-label">Total Weight</div>
                                            <div class="stat-value">
                                                {{ $productivity->total_weight !== null ? number_format((float) $productivity->total_weight, 2, ',', '.') . ' Kg' : '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="process-card">
                                        <div class="process-label">Total Time</div>
                                        <div class="process-value">
                                            {{ $productivity->total_time !== null ? number_format((float) $productivity->total_time, 2, ',', '.') : '-' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="process-card">
                                        <div class="process-label">Productivity Indirect</div>
                                        <div class="process-value">
                                            {{ $productivity->productivity_indirect !== null ? number_format((float) $productivity->productivity_indirect, 2, ',', '.') : '-' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="process-card">
                                        <div class="process-label">Productivity Direct</div>
                                        <div class="process-value">
                                            {{ $productivity->productivity_direct !== null ? number_format((float) $productivity->productivity_direct, 2, ',', '.') : '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="process-empty">
                            <i class="fas fa-chart-line d-block"></i>
                            Belum ada data Productivity untuk production batch ini.
                        </div>
                    @endif
                </div>
            </div>

            {{-- 6. REKAPITULASI PRODUKSI --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h4>
                        <span class="step-badge">6</span>
                        Rekapitulasi Produksi
                    </h4>
                </div>

                <div class="card-body p-0">
                    @if ($productionBatch->rekapitulasiProduksis->count())
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Mesin</th>
                                        <th>Waktu Start</th>
                                        <th>Waktu Stop</th>
                                        <th class="text-right">Operation Time</th>
                                        <th class="text-right">Speed Kapasitas</th>
                                        <th class="text-right">Aktual</th>
                                        <th class="text-right">% Pencapaian</th>
                                        <th class="text-right">Output (Kg)</th>
                                        <th class="text-right">Downtime</th>
                                        <th>Penyebab Downtime</th>
                                        <th>Tindakan Perbaikan</th>
                                        <th>PIC / Operator</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productionBatch->rekapitulasiProduksis as $rekap)
                                        <tr>
                                            <td>{{ $rekap->mesin ?? '-' }}</td>
                                            <td>{{ $rekap->waktu_start ? substr($rekap->waktu_start, 0, 5) : '-' }}</td>
                                            <td>{{ $rekap->waktu_stop ? substr($rekap->waktu_stop, 0, 5) : '-' }}</td>
                                            <td class="text-right">
                                                {{ $rekap->operation_time !== null ? number_format((float) $rekap->operation_time, 2, ',', '.') : '-' }}
                                            </td>
                                            <td class="text-right">
                                                {{ $rekap->speed_kapasitas !== null ? number_format((float) $rekap->speed_kapasitas, 2, ',', '.') : '-' }}
                                            </td>
                                            <td class="text-right">
                                                {{ $rekap->aktual !== null ? number_format((float) $rekap->aktual, 2, ',', '.') : '-' }}
                                            </td>
                                            <td class="text-right">
                                                @if ($rekap->persen_pencapaian !== null)
                                                    <span class="badge-soft">
                                                        {{ number_format((float) $rekap->persen_pencapaian, 2, ',', '.') }}%
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                {{ $rekap->output_kg !== null ? number_format((float) $rekap->output_kg, 2, ',', '.') : '-' }}
                                            </td>
                                            <td class="text-right">
                                                {{ $rekap->downtime !== null ? number_format((float) $rekap->downtime, 2, ',', '.') : '-' }}
                                            </td>
                                            <td>{{ $rekap->penyebab_downtime ?? '-' }}</td>
                                            <td>{{ $rekap->tindakan_perbaikan ?? '-' }}</td>
                                            <td>{{ $rekap->pic_operator ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="process-empty">
                            <i class="fas fa-clipboard-list d-block"></i>
                            Belum ada data Rekapitulasi Produksi untuk production batch ini.
                        </div>
                    @endif
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="card card-final">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: .5rem;">
                        <a href="{{ route('manager.production-batch.index') }}" class="btn btn-ghost">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Kembali
                        </a>

                        <a href="{{ route('manager.production-batch.export', $productionBatch->id) }}"
                            class="btn btn-excel">
                            <i class="fas fa-file-excel mr-1"></i>
                            Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
