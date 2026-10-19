@extends('layouts.master')

@section('title', 'Edit Production Batch')

@section('content')
    <div class="page-section">
        <div class="section-header">
            <h1>Production Batch</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('operator.production-batch.index') }}">
                        Production Batch
                    </a>
                </div>
                <div class="breadcrumb-item active">
                    Edit
                </div>
            </div>
        </div>

        <div class="section-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Terjadi kesalahan.</strong>
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

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('operator.production-batch.update', $productionBatch->id) }}" method="POST"
                id="form-production-batch">
                @csrf
                @method('PUT')

                <div class="card mb-4">
                    <div class="card-header">
                        <h4>
                            <span class="step-badge">1</span>
                            Informasi Production Batch
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="filter-label">
                                        Tanggal <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="tanggal"
                                        class="form-control @error('tanggal') is-invalid @enderror"
                                        value="{{ old('tanggal', optional($productionBatch->tanggal)->format('Y-m-d')) }}"
                                        required>
                                    @error('tanggal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="filter-label">
                                        Shift <span class="text-danger">*</span>
                                    </label>
                                    <select name="shift" class="form-control @error('shift') is-invalid @enderror"
                                        required>
                                        <option value="">Pilih Shift</option>
                                        @foreach ([1, 2, 3] as $shift)
                                            <option value="{{ $shift }}"
                                                {{ old('shift', $productionBatch->shift) == $shift ? 'selected' : '' }}>
                                                Shift {{ $shift }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('shift')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="filter-label">
                                        Conveyor <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="conveyor"
                                        class="form-control @error('conveyor') is-invalid @enderror"
                                        value="{{ old('conveyor', $productionBatch->conveyor) }}"
                                        placeholder="Masukkan nama conveyor" maxlength="100" required>
                                    @error('conveyor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="filter-label">Cost Center Name</label>
                                    <select name="cost_center_name"
                                        class="form-control @error('cost_center_name') is-invalid @enderror">
                                        <option value="">Pilih Cost Center</option>
                                        @foreach (['FCHO', 'FCHS', 'FCHM', 'FCHT', 'FCHR', 'FCH1', 'FCH2', 'FCH3', 'FCH4', 'FCH5'] as $costCenter)
                                            <option value="{{ $costCenter }}"
                                                {{ old('cost_center_name', $productionBatch->cost_center_name) == $costCenter ? 'selected' : '' }}>
                                                {{ $costCenter }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cost_center_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="filter-label">No. PO</label>
                                    <input type="text" name="no_po"
                                        class="form-control @error('no_po') is-invalid @enderror"
                                        value="{{ old('no_po', $productionBatch->no_po) }}" placeholder="Masukkan nomor PO"
                                        maxlength="255">
                                    @error('no_po')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="filter-label">Absen Worker</label>
                                    <input type="number" name="absen_worker"
                                        class="form-control @error('absen_worker') is-invalid @enderror"
                                        value="{{ old('absen_worker', $productionBatch->absen_worker) }}"
                                        placeholder="Masukkan jumlah pekerja yang absen" min="0">
                                    @error('absen_worker')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Masukkan jumlah pekerja yang absen. Kosongkan jika belum ada data.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h4>
                            <span class="step-badge">2</span>
                            Informasi Transfer dan Proses
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="filter-label">Tanggal Transfer</label>
                                    <input type="date" name="tanggal_transfer"
                                        class="form-control @error('tanggal_transfer') is-invalid @enderror"
                                        value="{{ old('tanggal_transfer', optional($productionBatch->tanggal_transfer)->format('Y-m-d')) }}">
                                    @error('tanggal_transfer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Tanggal transfer data atau barang.
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="filter-label">Tanggal Proses</label>
                                    <input type="date" name="tanggal_proses"
                                        class="form-control @error('tanggal_proses') is-invalid @enderror"
                                        value="{{ old('tanggal_proses', optional($productionBatch->tanggal_proses)->format('Y-m-d')) }}">
                                    @error('tanggal_proses')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Tanggal pelaksanaan proses produksi.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-final mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="total-card mb-2 mb-md-0">
                                <div class="stat-icon">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <div>
                                    <div class="stat-label">Production Batch</div>
                                    <div class="stat-value">
                                        {{ $productionBatch->conveyor ?: 'Belum ada conveyor' }}
                                    </div>
                                </div>
                            </div>

                            <div class="action-buttons">
                                <a href="{{ route('operator.production-batch.index') }}" class="btn btn-ghost">
                                    <i class="fas fa-arrow-left mr-1"></i>
                                    Batal
                                </a>
                                <button type="submit" class="btn btn-add">
                                    <i class="fas fa-save mr-1"></i>
                                    Update Production Batch
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
