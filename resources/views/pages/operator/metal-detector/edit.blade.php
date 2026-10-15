@extends('layouts.master')

@section('title', 'Edit Metal Detector')

@section('content')
    <div class="page-section">
        <div class="section-header">
            <h1>Edit Metal Detector</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('operator.metal-detector.index') }}">Metal Detector</a>
                </div>
                <div class="breadcrumb-item active">Edit</div>
            </div>
        </div>

        <div class="section-lead">
            Perbarui informasi produksi, product, pemeriksaan Metal Detector, dan keterangan.
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

            <form action="{{ route('operator.metal-detector.update', $metalDetector->id) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="production_batch_id"
                    value="{{ old('production_batch_id', $metalDetector->production_batch_id) }}">

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
                                        value="{{ $metalDetector->productionBatch?->tanggal ? $metalDetector->productionBatch->tanggal->format('d-m-Y') : '-' }}"
                                        disabled>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Shift</label>
                                    <input type="text" class="form-control"
                                        value="{{ $metalDetector->productionBatch?->shift ?? '-' }}" disabled>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>No. PO</label>
                                    <input type="text" class="form-control"
                                        value="{{ $metalDetector->productionBatch?->no_po ?? '-' }}" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Cost Center Name</div>
                                    <div class="info-value">
                                        {{ $metalDetector->productionBatch?->cost_center_name ?? '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Tanggal Transfer</div>
                                    <div class="info-value">
                                        {{ $metalDetector->productionBatch?->tanggal_transfer
                                            ? $metalDetector->productionBatch->tanggal_transfer->format('d-m-Y')
                                            : '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Tanggal Proses</div>
                                    <div class="info-value">
                                        {{ $metalDetector->productionBatch?->tanggal_proses
                                            ? $metalDetector->productionBatch->tanggal_proses->format('d-m-Y')
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
                                Nama Product <span class="text-danger">*</span>
                            </label>
                            <select name="material_id" id="material_id"
                                class="form-control select2 @error('material_id') is-invalid @enderror" required>
                                <option value="">Pilih Product</option>
                                @foreach ($materials as $material)
                                    <option value="{{ $material->id }}"
                                        {{ (string) old('material_id', $metalDetector->material_id) === (string) $material->id ? 'selected' : '' }}>
                                        {{ $material->material_code }} - {{ $material->material_description }}
                                    </option>
                                @endforeach
                            </select>
                            @error('material_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-0 mt-3">
                            <label for="no_program">No Program</label>
                            <input type="text" name="no_program" id="no_program"
                                class="form-control @error('no_program') is-invalid @enderror"
                                value="{{ old('no_program', $metalDetector->no_program) }}"
                                placeholder="Masukkan nomor program">
                            @error('no_program')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card card-accent mb-4">
                    <div class="card-header">
                        <h4>
                            <span class="step-badge">2</span>
                            Pemeriksaan Metal Detector
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Jenis Pemeriksaan</th>
                                        <th class="text-center">Depan Produk</th>
                                        <th class="text-center">Tengah Produk</th>
                                        <th class="text-center">Belakang Produk</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>FE</th>
                                        @foreach (['fe_depan', 'fe_tengah', 'fe_belakang'] as $field)
                                            <td>
                                                <select name="{{ $field }}"
                                                    class="form-control @error($field) is-invalid @enderror">
                                                    <option value="">Pilih Hasil</option>
                                                    <option value="Terdeteksi"
                                                        {{ old($field, $metalDetector->$field) === 'Terdeteksi' ? 'selected' : '' }}>
                                                        Terdeteksi
                                                    </option>
                                                    <option value="Tidak terdeteksi"
                                                        {{ old($field, $metalDetector->$field) === 'Tidak terdeteksi' ? 'selected' : '' }}>
                                                        Tidak terdeteksi
                                                    </option>
                                                </select>
                                                @error($field)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                        @endforeach
                                    </tr>

                                    <tr>
                                        <th>Non FE</th>
                                        @foreach (['non_fe_depan', 'non_fe_tengah', 'non_fe_belakang'] as $field)
                                            <td>
                                                <select name="{{ $field }}"
                                                    class="form-control @error($field) is-invalid @enderror">
                                                    <option value="">Pilih Hasil</option>
                                                    <option value="Terdeteksi"
                                                        {{ old($field, $metalDetector->$field) === 'Terdeteksi' ? 'selected' : '' }}>
                                                        Terdeteksi
                                                    </option>
                                                    <option value="Tidak terdeteksi"
                                                        {{ old($field, $metalDetector->$field) === 'Tidak terdeteksi' ? 'selected' : '' }}>
                                                        Tidak terdeteksi
                                                    </option>
                                                </select>
                                                @error($field)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                        @endforeach
                                    </tr>

                                    <tr>
                                        <th>SUS 316</th>
                                        @foreach (['sus_316_depan', 'sus_316_tengah', 'sus_316_belakang'] as $field)
                                            <td>
                                                <select name="{{ $field }}"
                                                    class="form-control @error($field) is-invalid @enderror">
                                                    <option value="">Pilih Hasil</option>
                                                    <option value="Terdeteksi"
                                                        {{ old($field, $metalDetector->$field) === 'Terdeteksi' ? 'selected' : '' }}>
                                                        Terdeteksi
                                                    </option>
                                                    <option value="Tidak terdeteksi"
                                                        {{ old($field, $metalDetector->$field) === 'Tidak terdeteksi' ? 'selected' : '' }}>
                                                        Tidak terdeteksi
                                                    </option>
                                                </select>
                                                @error($field)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h4>
                            <span class="step-badge">3</span>
                            Informasi IN / OUT dan Keterangan
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="in">IN</label>
                                    <input type="text" name="in" id="in"
                                        class="form-control @error('in') is-invalid @enderror"
                                        value="{{ old('in', $metalDetector->in) }}" placeholder="Masukkan informasi IN">
                                    @error('in')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="out">OUT</label>
                                    <input type="text" name="out" id="out"
                                        class="form-control @error('out') is-invalid @enderror"
                                        value="{{ old('out', $metalDetector->out) }}"
                                        placeholder="Masukkan informasi OUT">
                                    @error('out')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="3"
                                class="form-control @error('keterangan') is-invalid @enderror" placeholder="Masukkan keterangan">{{ old('keterangan', $metalDetector->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap mt-4 mb-4">
                    <a href="{{ route('operator.metal-detector.index') }}" class="btn btn-ghost mb-2">
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
            if ($.fn.select2) {
                $('.select2').select2({
                    width: '100%',
                    placeholder: 'Pilih opsi',
                    allowClear: true
                });
            }

            const batchId = @json(old('production_batch_id', $metalDetector->production_batch_id));

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
