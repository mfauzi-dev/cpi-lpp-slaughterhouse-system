@extends('layouts.master')

@section('title', 'Edit Data Hasil Produksi')

@section('content')
    <div class="page-section">
        <div class="section-header">
            <h1>Data Hasil Produksi</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('operator.hasil-produksi.index') }}">Data Hasil Produksi</a>
                </div>
                <div class="breadcrumb-item active">Edit</div>
            </div>
        </div>

        <div class="section-lead">
            Perbarui hasil produksi berdasarkan batch, material, jumlah produksi, loss, pickup, suhu, dan informasi
            packaging.
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

            <form action="{{ route('operator.hasil-produksi.update', $hasilProduksi->id) }}" method="POST"
                id="hasilProduksiForm">
                @csrf
                @method('PUT')

                <input type="hidden" name="production_batch_id"
                    value="{{ old('production_batch_id', $hasilProduksi->production_batch_id) }}">

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
                                    <label class="filter-label">Tanggal Produksi</label>
                                    <input type="date" class="form-control"
                                        value="{{ optional($hasilProduksi->productionBatch?->tanggal)->format('Y-m-d') }}"
                                        disabled>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="filter-label">Shift</label>
                                    <input type="text" class="form-control"
                                        value="{{ $hasilProduksi->productionBatch?->shift ?? '-' }}" disabled>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="filter-label">No. PO</label>
                                    <input type="text" class="form-control"
                                        value="{{ $hasilProduksi->productionBatch?->no_po ?? '-' }}" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Cost Center Name</div>
                                    <div class="info-value">
                                        {{ $hasilProduksi->productionBatch?->cost_center_name ?? '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Tanggal Transfer</div>
                                    <div class="info-value">
                                        {{ $hasilProduksi->productionBatch?->tanggal_transfer
                                            ? $hasilProduksi->productionBatch->tanggal_transfer->format('d-m-Y')
                                            : '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Tanggal Proses</div>
                                    <div class="info-value">
                                        {{ $hasilProduksi->productionBatch?->tanggal_proses
                                            ? $hasilProduksi->productionBatch->tanggal_proses->format('d-m-Y')
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
                    </div>
                </div>

                <div class="card card-accent mb-4">
                    <div class="card-header">
                        <h4>
                            <span class="step-badge">2</span>
                            Detail Hasil Produksi
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="material_id">
                                Material <span class="text-danger">*</span>
                            </label>
                            <select name="material_id" id="material_id"
                                class="form-control select2 @error('material_id') is-invalid @enderror" required>
                                <option value="">Pilih Material</option>
                                @foreach ($materials as $material)
                                    <option value="{{ $material->id }}"
                                        {{ old('material_id', $hasilProduksi->material_id) == $material->id ? 'selected' : '' }}>
                                        {{ $material->material_code }} - {{ $material->material_description }}
                                    </option>
                                @endforeach
                            </select>
                            @error('material_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="domestik_kg">Domestik (KG)</label>
                                        <input type="number" step="0.01" min="0" name="domestik_kg"
                                            id="domestik_kg" class="form-control"
                                            value="{{ old('domestik_kg', $hasilProduksi->domestik_kg) }}"
                                            placeholder="0,00">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="further_kg">Further (KG)</label>
                                        <input type="number" step="0.01" min="0" name="further_kg"
                                            id="further_kg" class="form-control"
                                            value="{{ old('further_kg', $hasilProduksi->further_kg) }}"
                                            placeholder="0,00">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="frozen_kg">Frozen (KG)</label>
                                        <input type="number" step="0.01" min="0" name="frozen_kg"
                                            id="frozen_kg" class="form-control"
                                            value="{{ old('frozen_kg', $hasilProduksi->frozen_kg) }}" placeholder="0,00">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label for="rm">RM (KG)</label>
                            <input type="number" step="0.01" min="0" name="rm" id="rm"
                                class="form-control" value="{{ old('rm', $hasilProduksi->rm) }}"
                                placeholder="Masukkan berat RM">
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h4>
                            <span class="step-badge">3</span>
                            Total Produksi dan Loss
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total_bag">Total Bag</label>
                                    <input type="number" min="0" step="1" name="total_bag" id="total_bag"
                                        class="form-control" value="{{ old('total_bag', $hasilProduksi->total_bag) }}"
                                        placeholder="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total_kg">Total KG</label>
                                    <input type="number" step="0.01" min="0" name="total_kg" id="total_kg"
                                        class="form-control" value="{{ old('total_kg', $hasilProduksi->total_kg) }}"
                                        placeholder="0,00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="loss_kg">Loss (KG)</label>
                                    <input type="number" step="0.01" min="0" name="loss_kg" id="loss_kg"
                                        class="form-control" value="{{ old('loss_kg', $hasilProduksi->loss_kg) }}"
                                        placeholder="0,00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="loss_persen">Loss (%)</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" name="loss_persen"
                                            id="loss_persen" class="form-control"
                                            value="{{ old('loss_persen', $hasilProduksi->loss_persen) }}"
                                            placeholder="0,00">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pickup_kg">Pickup (KG)</label>
                                    <input type="number" step="0.01" min="0" name="pickup_kg" id="pickup_kg"
                                        class="form-control" value="{{ old('pickup_kg', $hasilProduksi->pickup_kg) }}"
                                        placeholder="0,00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pickup_persen">Pickup (%)</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" name="pickup_persen"
                                            id="pickup_persen" class="form-control"
                                            value="{{ old('pickup_persen', $hasilProduksi->pickup_persen) }}"
                                            placeholder="0,00">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
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
                            <span class="step-badge">4</span>
                            Pengecekan Suhu
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="section-info mb-3">
                            <i class="fas fa-thermometer-half"></i>
                            Catat waktu dan hasil pengecekan suhu produk.
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pengecekan_suhu_jam">Jam Pengecekan</label>
                                    <input type="time" name="pengecekan_suhu_jam" id="pengecekan_suhu_jam"
                                        class="form-control"
                                        value="{{ old('pengecekan_suhu_jam', $hasilProduksi->pengecekan_suhu_jam ? \Carbon\Carbon::parse($hasilProduksi->pengecekan_suhu_jam)->format('H:i') : '') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pengecekan_suhu_suhu">Suhu (°C)</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="pengecekan_suhu_suhu"
                                            id="pengecekan_suhu_suhu" class="form-control"
                                            value="{{ old('pengecekan_suhu_suhu', $hasilProduksi->pengecekan_suhu_suhu) }}"
                                            placeholder="Masukkan suhu">
                                        <div class="input-group-append">
                                            <span class="input-group-text">°C</span>
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
                            <span class="step-badge">5</span>
                            Packaging
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="packaging_nama_kemasan">Nama Kemasan</label>
                                    <input type="text" name="packaging_nama_kemasan" id="packaging_nama_kemasan"
                                        class="form-control"
                                        value="{{ old('packaging_nama_kemasan', $hasilProduksi->packaging_nama_kemasan) }}"
                                        placeholder="Masukkan nama kemasan">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="packaging_jumlah_kg">Jumlah Packaging (KG)</label>
                                    <input type="number" step="0.01" min="0" name="packaging_jumlah_kg"
                                        id="packaging_jumlah_kg" class="form-control"
                                        value="{{ old('packaging_jumlah_kg', $hasilProduksi->packaging_jumlah_kg) }}"
                                        placeholder="0,00">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap mt-4 mb-4">
                    <a href="{{ route('operator.hasil-produksi.index') }}" class="btn btn-ghost mb-2">
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

            const batchId = @json($hasilProduksi->production_batch_id);

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
