@extends('layouts.master')

@section('title', 'Edit Rekapitulasi Produksi')

@section('content')
    <div class="page-section">
        <div class="section-header">
            <h1>Rekapitulasi Produksi dan Monitoring Mesin</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('operator.rekapitulasi-produksi.index') }}">Rekapitulasi Produksi</a>
                </div>
                <div class="breadcrumb-item active">Edit</div>
            </div>
        </div>

        <div class="section-lead">
            Perbarui informasi produksi, waktu operasional, kapasitas mesin, output, downtime, dan tindakan perbaikan.
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

            <form action="{{ route('operator.rekapitulasi-produksi.update', $rekapitulasiProduksi->id) }}" method="POST"
                id="rekapitulasiProduksiForm">
                @csrf
                @method('PUT')

                <input type="hidden" name="production_batch_id"
                    value="{{ old('production_batch_id', $rekapitulasiProduksi->production_batch_id) }}">

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
                                    <input type="text" class="form-control"
                                        value="{{ $rekapitulasiProduksi->productionBatch?->tanggal ? $rekapitulasiProduksi->productionBatch->tanggal->format('d-m-Y') : '-' }}"
                                        disabled>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="filter-label">Shift</label>
                                    <input type="text" class="form-control"
                                        value="{{ $rekapitulasiProduksi->productionBatch?->shift ?? '-' }}" disabled>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="filter-label">No. PO</label>
                                    <input type="text" class="form-control"
                                        value="{{ $rekapitulasiProduksi->productionBatch?->no_po ?? '-' }}" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Cost Center Name</div>
                                    <div class="info-value">
                                        {{ $rekapitulasiProduksi->productionBatch?->cost_center_name ?? '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Tanggal Transfer</div>
                                    <div class="info-value">
                                        {{ $rekapitulasiProduksi->productionBatch?->tanggal_transfer
                                            ? $rekapitulasiProduksi->productionBatch->tanggal_transfer->format('d-m-Y')
                                            : '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Tanggal Proses</div>
                                    <div class="info-value">
                                        {{ $rekapitulasiProduksi->productionBatch?->tanggal_proses
                                            ? $rekapitulasiProduksi->productionBatch->tanggal_proses->format('d-m-Y')
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
                            <label for="mesin">
                                Nama Mesin <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="mesin" id="mesin"
                                class="form-control @error('mesin') is-invalid @enderror"
                                value="{{ old('mesin', $rekapitulasiProduksi->mesin) }}" placeholder="Masukkan nama mesin"
                                required>
                            @error('mesin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card card-accent mb-4">
                    <div class="card-header">
                        <h4>
                            <span class="step-badge">2</span>
                            Waktu Operasional dan Kapasitas
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="waktu_start">Waktu Start</label>
                                    <input type="time" name="waktu_start" id="waktu_start"
                                        class="form-control @error('waktu_start') is-invalid @enderror"
                                        value="{{ old(
                                            'waktu_start',
                                            $rekapitulasiProduksi->waktu_start ? \Carbon\Carbon::parse($rekapitulasiProduksi->waktu_start)->format('H:i') : '',
                                        ) }}">
                                    @error('waktu_start')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="waktu_stop">Waktu Stop</label>
                                    <input type="time" name="waktu_stop" id="waktu_stop"
                                        class="form-control @error('waktu_stop') is-invalid @enderror"
                                        value="{{ old(
                                            'waktu_stop',
                                            $rekapitulasiProduksi->waktu_stop ? \Carbon\Carbon::parse($rekapitulasiProduksi->waktu_stop)->format('H:i') : '',
                                        ) }}">
                                    @error('waktu_stop')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="operation_time">Operation Time (Jam)</label>
                                    <input type="number" step="0.01" min="0" name="operation_time"
                                        id="operation_time"
                                        class="form-control @error('operation_time') is-invalid @enderror"
                                        value="{{ old('operation_time', $rekapitulasiProduksi->operation_time) }}"
                                        placeholder="Masukkan operation time">
                                    @error('operation_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="speed_kapasitas">Speed / Kapasitas</label>
                                    <input type="number" step="0.01" min="0" name="speed_kapasitas"
                                        id="speed_kapasitas"
                                        class="form-control @error('speed_kapasitas') is-invalid @enderror"
                                        value="{{ old('speed_kapasitas', $rekapitulasiProduksi->speed_kapasitas) }}"
                                        placeholder="Masukkan speed / kapasitas">
                                    @error('speed_kapasitas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="aktual">Aktual</label>
                                    <input type="number" step="0.01" min="0" name="aktual" id="aktual"
                                        class="form-control @error('aktual') is-invalid @enderror"
                                        value="{{ old('aktual', $rekapitulasiProduksi->aktual) }}"
                                        placeholder="Masukkan hasil aktual">
                                    @error('aktual')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="persen_pencapaian">Pencapaian (%)</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" name="persen_pencapaian"
                                            id="persen_pencapaian"
                                            class="form-control @error('persen_pencapaian') is-invalid @enderror"
                                            value="{{ old('persen_pencapaian', $rekapitulasiProduksi->persen_pencapaian) }}"
                                            placeholder="Masukkan persentase pencapaian">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    @error('persen_pencapaian')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group mb-0">
                                    <label for="output_kg">Output (KG)</label>
                                    <input type="number" step="0.01" min="0" name="output_kg" id="output_kg"
                                        class="form-control @error('output_kg') is-invalid @enderror"
                                        value="{{ old('output_kg', $rekapitulasiProduksi->output_kg) }}"
                                        placeholder="Masukkan output dalam KG">
                                    @error('output_kg')
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
                            Downtime dan Tindakan Perbaikan
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="downtime">Downtime (Menit)</label>
                            <input type="number" step="0.01" min="0" name="downtime" id="downtime"
                                class="form-control @error('downtime') is-invalid @enderror"
                                value="{{ old('downtime', $rekapitulasiProduksi->downtime) }}"
                                placeholder="Masukkan durasi downtime">
                            @error('downtime')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="penyebab_downtime">Penyebab Downtime</label>
                            <textarea name="penyebab_downtime" id="penyebab_downtime" rows="3"
                                class="form-control @error('penyebab_downtime') is-invalid @enderror" placeholder="Masukkan penyebab downtime">{{ old('penyebab_downtime', $rekapitulasiProduksi->penyebab_downtime) }}</textarea>
                            @error('penyebab_downtime')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="tindakan_perbaikan">Tindakan Perbaikan</label>
                            <textarea name="tindakan_perbaikan" id="tindakan_perbaikan" rows="3"
                                class="form-control @error('tindakan_perbaikan') is-invalid @enderror" placeholder="Masukkan tindakan perbaikan">{{ old('tindakan_perbaikan', $rekapitulasiProduksi->tindakan_perbaikan) }}</textarea>
                            @error('tindakan_perbaikan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <label for="pic_operator">PIC Operator</label>
                            <input type="text" name="pic_operator" id="pic_operator"
                                class="form-control @error('pic_operator') is-invalid @enderror"
                                value="{{ old('pic_operator', $rekapitulasiProduksi->pic_operator) }}"
                                placeholder="Masukkan nama PIC operator">
                            @error('pic_operator')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap mt-4 mb-4">
                    <a href="{{ route('operator.rekapitulasi-produksi.index') }}" class="btn btn-ghost mb-2">
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
            const batchId = @json($rekapitulasiProduksi->production_batch_id);

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
