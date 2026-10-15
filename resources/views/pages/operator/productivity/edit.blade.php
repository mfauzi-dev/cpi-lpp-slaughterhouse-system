@extends('layouts.master')

@section('title', 'Edit Productivity')

@section('content')
    <div class="page-section">
        <div class="section-header">
            <h1>Edit Productivity</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('operator.productivity.index') }}">Productivity</a>
                </div>
                <div class="breadcrumb-item active">Edit</div>
            </div>
        </div>

        <div class="section-lead">
            Perbarui informasi produksi, bahan baku, jumlah tenaga kerja, berat, waktu, dan produktivitas.
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

            <form action="{{ route('operator.productivity.update', $productivity->id) }}" method="POST"
                id="productivityForm">
                @csrf
                @method('PUT')

                <input type="hidden" name="production_batch_id"
                    value="{{ old('production_batch_id', $productivity->production_batch_id) }}">

                <div class="card mb-4">
                    <div class="card-header">
                        <h4>
                            <span class="step-badge">1</span>
                            Informasi Produksi
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tanggal Produksi</label>
                                    <input type="text" class="form-control"
                                        value="{{ $productivity->productionBatch?->tanggal ? $productivity->productionBatch->tanggal->format('d-m-Y') : '-' }}"
                                        disabled>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Shift</label>
                                    <input type="text" class="form-control"
                                        value="{{ $productivity->productionBatch?->shift ?? '-' }}" disabled>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>No. PO</label>
                                    <input type="text" class="form-control"
                                        value="{{ $productivity->productionBatch?->no_po ?? '-' }}" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Cost Center Name</div>
                                    <div class="info-value">
                                        {{ $productivity->productionBatch?->cost_center_name ?? '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Tanggal Transfer</div>
                                    <div class="info-value">
                                        {{ $productivity->productionBatch?->tanggal_transfer
                                            ? $productivity->productionBatch->tanggal_transfer->format('d-m-Y')
                                            : '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Tanggal Proses</div>
                                    <div class="info-value">
                                        {{ $productivity->productionBatch?->tanggal_proses
                                            ? $productivity->productionBatch->tanggal_proses->format('d-m-Y')
                                            : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="batch-summary" id="production-summary">
                            <div class="row">
                                <div class="col-md-6 mb-2 mb-md-0">
                                    <i class="fas fa-boxes"></i>
                                    Jumlah Jenis Bahan Baku:
                                    <strong id="info_jumlah_produk">Memuat...</strong>
                                </div>
                                <div class="col-md-6">
                                    <i class="fas fa-weight-hanging"></i>
                                    Total Berat Bahan Baku:
                                    <strong id="info_total_kg">Memuat...</strong>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0 mt-3">
                            <label for="material_id">
                                Bahan Baku <span class="text-danger">*</span>
                            </label>
                            <select name="material_id" id="material_id"
                                class="form-control select2 @error('material_id') is-invalid @enderror" required>
                                <option value="">Pilih Bahan Baku</option>
                                @foreach ($materials as $material)
                                    <option value="{{ $material->id }}"
                                        {{ (string) old('material_id', $productivity->material_id) === (string) $material->id ? 'selected' : '' }}>
                                        {{ $material->material_description }}
                                    </option>
                                @endforeach
                            </select>
                            @error('material_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card card-accent mb-4">
                    <div class="card-header">
                        <h4>
                            <span class="step-badge">2</span>
                            Tenaga Kerja
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="worker_indirect">Worker Indirect</label>
                                    <input type="number" min="0" step="1" name="worker_indirect"
                                        id="worker_indirect"
                                        class="form-control @error('worker_indirect') is-invalid @enderror"
                                        value="{{ old('worker_indirect', $productivity->worker_indirect) }}"
                                        placeholder="Jumlah worker indirect">
                                    @error('worker_indirect')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="worker_direct">Worker Direct</label>
                                    <input type="number" min="0" step="1" name="worker_direct"
                                        id="worker_direct" class="form-control @error('worker_direct') is-invalid @enderror"
                                        value="{{ old('worker_direct', $productivity->worker_direct) }}"
                                        placeholder="Jumlah worker direct">
                                    @error('worker_direct')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="worker_total">Total Worker</label>
                                    <input type="number" min="0" step="1" name="worker_total"
                                        id="worker_total" class="form-control @error('worker_total') is-invalid @enderror"
                                        value="{{ old('worker_total', $productivity->worker_total) }}"
                                        placeholder="Total tenaga kerja">
                                    @error('worker_total')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h4>
                            <span class="step-badge">3</span>
                            Berat, Waktu, dan Produktivitas
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total_weight">Total Weight (KG)</label>
                                    <input type="number" min="0" step="0.01" name="total_weight"
                                        id="total_weight" class="form-control @error('total_weight') is-invalid @enderror"
                                        value="{{ old('total_weight', $productivity->total_weight) }}"
                                        placeholder="Masukkan total berat">
                                    @error('total_weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total_time">Total Time (Jam)</label>
                                    <input type="number" min="0" step="0.01" name="total_time"
                                        id="total_time" class="form-control @error('total_time') is-invalid @enderror"
                                        value="{{ old('total_time', $productivity->total_time) }}"
                                        placeholder="Masukkan total waktu">
                                    @error('total_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="productivity_indirect">Productivity Indirect</label>
                                    <input type="number" min="0" step="0.01" name="productivity_indirect"
                                        id="productivity_indirect"
                                        class="form-control @error('productivity_indirect') is-invalid @enderror"
                                        value="{{ old('productivity_indirect', $productivity->productivity_indirect) }}"
                                        placeholder="Masukkan productivity indirect">
                                    @error('productivity_indirect')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="productivity_direct">Productivity Direct</label>
                                    <input type="number" min="0" step="0.01" name="productivity_direct"
                                        id="productivity_direct"
                                        class="form-control @error('productivity_direct') is-invalid @enderror"
                                        value="{{ old('productivity_direct', $productivity->productivity_direct) }}"
                                        placeholder="Masukkan productivity direct">
                                    @error('productivity_direct')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap mt-4 mb-4">
                    <a href="{{ route('operator.productivity.index') }}" class="btn btn-ghost mb-2">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary mb-2">
                        <i class="fas fa-save mr-1"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
                placeholder: 'Pilih Bahan Baku',
                allowClear: true
            });

            const batchId = @json($productivity->production_batch_id);

            if (!batchId) {
                $('#info_jumlah_produk').text('0');
                $('#info_total_kg').text('0,00 KG');
                return;
            }

            $.ajax({
                url: '{{ route('operator.hasil-produksi.get-products') }}',
                type: 'GET',
                data: {
                    production_batch_id: batchId
                },
                success: function(response) {
                    $('#info_jumlah_produk').text(
                        (response.jumlah_produk ?? 0) + ' jenis'
                    );

                    $('#info_total_kg').text(
                        Number(response.total_kg || 0).toLocaleString('id-ID', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }) + ' KG'
                    );
                },
                error: function() {
                    $('#info_jumlah_produk').text('-');
                    $('#info_total_kg').text('-');
                }
            });
        });
    </script>
@endpush
