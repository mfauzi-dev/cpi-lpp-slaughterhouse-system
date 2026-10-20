@extends('layouts.master')

@section('title', 'Detail Produksi Bahan Baku')

@section('content')
    <div class="page-section">
        <div class="section-header">
            <h1>Produksi Bahan Baku</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('manager.bahan-baku.index') }}">Produksi Bahan Baku</a>
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

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @php
                $selectedPo = request('no_po');
                $selectedItems =
                    $selectedPo !== null
                        ? $production->bahanBakus->where('no_po', $selectedPo)
                        : $production->bahanBakus;
            @endphp

            <div class="card mb-4">
                <div class="card-header">
                    <h4>
                        <span class="step-badge">1</span>
                        Informasi Produksi
                    </h4>
                </div>

                <div class="card-body">
                    <div class="batch-highlight">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="batch-label">Tanggal Produksi</div>
                                <div class="batch-number">
                                    {{ $production->tanggal ? \Carbon\Carbon::parse($production->tanggal)->format('d M Y') : '-' }}
                                </div>
                                <div class="batch-product">
                                    Shift {{ $production->shift ?? '-' }}
                                </div>
                            </div>

                            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                <div class="batch-label">No. PO</div>
                                <div class="batch-number batch-date">
                                    {{ $selectedPo !== null ? ($selectedPo !== '' ? $selectedPo : '(Belum diisi)') : 'Semua PO' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">Tanggal Produksi</div>
                                <div class="info-value">
                                    {{ $production->tanggal ? \Carbon\Carbon::parse($production->tanggal)->format('d M Y') : '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">Shift</div>
                                <div class="info-value">{{ $production->shift ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">Jumlah Pekerja</div>
                                <div class="info-value">{{ $production->absen_worker ?? 0 }} orang</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">Tanggal Transfer</div>
                                <div class="info-value">
                                    {{ $production->tanggal_transfer ? \Carbon\Carbon::parse($production->tanggal_transfer)->format('d-m-Y') : '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">Tanggal Proses</div>
                                <div class="info-value">
                                    {{ $production->tanggal_proses ? \Carbon\Carbon::parse($production->tanggal_proses)->format('d-m-Y') : '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">Jumlah Baris Material</div>
                                <div class="info-value">{{ $selectedItems->count() }} baris</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-weight-hanging"></i>
                                </div>
                                <div>
                                    <div class="stat-label">Total Berat {{ $selectedPo !== null ? 'PO' : 'Seluruh PO' }}
                                    </div>
                                    <div class="stat-value">
                                        {{ number_format($selectedItems->sum('jumlah_kg'), 2, ',', '.') }} KG
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4>
                        <span class="step-badge">2</span>
                        Data Bahan Baku
                    </h4>
                </div>

                <div class="card-body">
                    @forelse ($poGroups as $poNumber => $poItems)
                        <div class="po-section mb-4">
                            <div class="po-heading d-flex justify-content-between align-items-center flex-wrap">
                                <div>
                                    <h5 class="font-weight-bold mb-1">
                                        No. PO: {{ $poNumber !== '' ? $poNumber : '(Belum diisi)' }}
                                    </h5>
                                    <small class="text-muted">
                                        {{ $poItems->count() }} material
                                        @if ($poItems->first()->cost_center_name)
                                            | Cost Center: {{ $poItems->first()->cost_center_name }}
                                        @endif
                                    </small>
                                </div>

                                <div class="mt-2 mt-md-0">
                                    <span class="font-weight-bold">
                                        Total: {{ number_format($poItems->sum('jumlah_kg'), 2, ',', '.') }} KG
                                    </span>
                                </div>
                            </div>

                            <div class="table-responsive mt-2">
                                <table class="table table-bordered table-wide mb-0">
                                    <thead>
                                        <tr>
                                            <th width="60" class="text-center">No</th>
                                            <th>Nama Material</th>
                                            <th>Jumlah Bag</th>
                                            <th>Berat (KG)</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($poItems as $index => $bahanBaku)
                                            <tr>
                                                <td class="text-center font-weight-bold">{{ $index + 1 }}</td>

                                                <td>
                                                    <div class="font-weight-bold">
                                                        {{ $bahanBaku->material->material_description ?? '-' }}
                                                    </div>

                                                    @if ($bahanBaku->material && $bahanBaku->material->material_code)
                                                        <small class="text-muted">
                                                            {{ $bahanBaku->material->material_code }}
                                                        </small>
                                                    @endif
                                                </td>

                                                <td>
                                                    {{ number_format($bahanBaku->jumlah_bag ?? 0, 0, ',', '.') }}
                                                </td>

                                                <td>
                                                    <strong>
                                                        {{ number_format($bahanBaku->jumlah_kg ?? 0, 2, ',', '.') }}
                                                    </strong>
                                                    KG
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-right font-weight-bold">Total Berat PO</td>
                                            <td class="font-weight-bold">
                                                {{ number_format($poItems->sum('jumlah_kg'), 2, ',', '.') }} KG
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-box-open d-block"></i>
                            <div>Belum ada data bahan baku.</div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="card card-final">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <div class="info-label mb-1">Total Berat
                                {{ $selectedPo !== null ? 'PO' : 'Seluruh Bahan Baku' }}</div>
                            <div class="stat-value">
                                {{ number_format($selectedItems->sum('jumlah_kg'), 2, ',', '.') }} KG
                            </div>
                        </div>

                        <div class="d-flex align-items-center flex-wrap mt-3 mt-md-0">
                            <a href="{{ route('manager.bahan-baku.index') }}" class="btn btn-ghost mr-2 mb-2">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .po-section {
            border: 1px solid #e3e8ef;
            border-radius: 6px;
            overflow: hidden;
        }

        .po-heading {
            padding: 16px 20px;
            background: #f8f9fc;
            border-bottom: 1px solid #e3e8ef;
        }

        .po-heading h5 {
            font-size: 15px;
        }

        .po-section .table {
            margin-bottom: 0;
        }

        .po-section .table thead th {
            vertical-align: middle;
            white-space: nowrap;
        }

        @media (max-width: 767px) {
            .po-heading {
                padding: 12px;
            }
        }
    </style>
@endpush
