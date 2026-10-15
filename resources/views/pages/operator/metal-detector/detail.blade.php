@extends('layouts.master')

@section('title', 'Detail Metal Detector')

@section('content')
    <div class="page-section">
        <div class="section-header">
            <h1>Detail Metal Detector</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('operator.metal-detector.index') }}">Metal Detector</a>
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
                                    {{ $metalDetector->productionBatch->no_po ?? '-' }}
                                </div>
                                <div class="batch-product">
                                    Product:
                                    {{ $metalDetector->material->material_code ?? '-' }}
                                    -
                                    {{ $metalDetector->material->material_description ?? '-' }}
                                </div>
                            </div>
                            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                <div class="batch-label">Tanggal Produksi</div>
                                <div class="batch-number" style="font-size: 1.1rem;">
                                    {{ $metalDetector->productionBatch?->tanggal
                                        ? \Carbon\Carbon::parse($metalDetector->productionBatch->tanggal)->format('d M Y')
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
                                    {{ $metalDetector->productionBatch?->tanggal
                                        ? \Carbon\Carbon::parse($metalDetector->productionBatch->tanggal)->format('d M Y')
                                        : '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">Shift</div>
                                <div class="info-value">
                                    {{ $metalDetector->productionBatch->shift ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">Nama Product</div>
                                <div class="info-value">
                                    {{ $metalDetector->material->material_description ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-box">
                                <div class="info-label">Kode Product</div>
                                <div class="info-value">
                                    {{ $metalDetector->material->material_code ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-box">
                                <div class="info-label">No Program</div>
                                <div class="info-value">
                                    {{ $metalDetector->no_program ?: '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-accent mb-4">
                <div class="card-header">
                    <h4><span class="step-badge">2</span> Hasil Pemeriksaan Metal Detector</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
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
                    <div class="small text-muted mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Hasil pemeriksaan ditampilkan sesuai data yang tersimpan.
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4><span class="step-badge">3</span> Informasi IN / OUT dan Keterangan</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-box">
                                <div class="info-label">IN</div>
                                <div class="info-value">
                                    {{ $metalDetector->in ?: '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-box">
                                <div class="info-label">OUT</div>
                                <div class="info-value">
                                    {{ $metalDetector->out ?: '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-box">
                                <div class="info-label">Keterangan</div>
                                <div class="info-value" style="white-space: pre-line;">
                                    {{ $metalDetector->keterangan ?: '-' }}
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
                            Pastikan hasil pemeriksaan Metal Detector sudah sesuai.
                        </div>
                        <div class="action-buttons">
                            <a href="{{ route('operator.metal-detector.edit', $metalDetector->id) }}"
                                class="btn btn-outline-warning" title="Edit">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>

                            <form action="{{ route('operator.metal-detector.destroy', $metalDetector->id) }}"
                                method="POST" style="display: inline;"
                                onsubmit="return confirm('Yakin ingin menghapus data Metal Detector ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            </form>

                            <a href="{{ route('operator.metal-detector.index') }}" class="btn btn-ghost">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
