@extends('layouts.master')

@section('title', 'Tambah Rekapitulasi Produksi')

@section('content')
    <div class="page-section">
        <div class="section-header">
            <h1>Rekapitulasi Produksi dan Monitoring Mesin</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('operator.rekapitulasi-produksi.index') }}">Rekapitulasi Produksi</a>
                </div>
                <div class="breadcrumb-item active">Tambah</div>
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

            <form action="{{ route('operator.rekapitulasi-produksi.store') }}" method="POST" id="rekapitulasiProduksiForm">
                @csrf

                <div class="card mb-4">
                    <div class="card-header">
                        <h4>
                            <span class="step-badge">1</span>
                            Informasi Produksi
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="tanggal_produksi">
                                    Tanggal Produksi <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="tanggal_produksi" id="tanggal_produksi"
                                    class="form-control @error('tanggal_produksi') is-invalid @enderror"
                                    value="{{ old('tanggal_produksi') }}" required>
                                @error('tanggal_produksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-8 mb-3">
                                <label for="production_batch_id">
                                    Production Batch <span class="text-danger">*</span>
                                </label>
                                <select name="production_batch_id" id="production_batch_id"
                                    class="form-control select2 @error('production_batch_id') is-invalid @enderror" required
                                    disabled>
                                    <option value="">Pilih tanggal terlebih dahulu</option>
                                </select>
                                @error('production_batch_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Shift</div>
                                    <div class="info-value" id="batch_shift">-</div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Cost Center Name</div>
                                    <div class="info-value" id="batch_cost_center">-</div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <div class="info-label">No. PO</div>
                                    <div class="info-value" id="batch_no_po">-</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Tanggal Transfer</div>
                                    <div class="info-value" id="batch_tanggal_transfer">-</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Tanggal Proses</div>
                                    <div class="info-value" id="batch_tanggal_proses">-</div>
                                </div>
                            </div>
                        </div>

                        <div class="batch-summary" id="production-summary" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-2 mb-md-0">
                                    <i class="fas fa-boxes"></i>
                                    Jumlah Jenis Bahan Baku:
                                    <strong id="info_jumlah_produk">0</strong>
                                </div>
                                <div class="col-md-6">
                                    <i class="fas fa-weight-hanging"></i>
                                    Total Berat Bahan Baku:
                                    <strong id="info_total_kg">0,00 KG</strong>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0 mt-3">
                            <label for="mesin">
                                Nama Mesin <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="mesin" id="mesin"
                                class="form-control @error('mesin') is-invalid @enderror" value="{{ old('mesin') }}"
                                placeholder="Masukkan nama mesin" required>
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
                                        value="{{ old('waktu_start') }}">
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
                                        value="{{ old('waktu_stop') }}">
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
                                        value="{{ old('operation_time') }}" placeholder="Masukkan operation time">
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
                                        value="{{ old('speed_kapasitas') }}" placeholder="Masukkan speed / kapasitas">
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
                                        value="{{ old('aktual') }}" placeholder="Masukkan hasil aktual">
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
                                            value="{{ old('persen_pencapaian') }}"
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
                                        value="{{ old('output_kg') }}" placeholder="Masukkan output dalam KG">
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
                                value="{{ old('downtime') }}" placeholder="Masukkan durasi downtime">
                            @error('downtime')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="penyebab_downtime">Penyebab Downtime</label>
                            <textarea name="penyebab_downtime" id="penyebab_downtime" rows="3"
                                class="form-control @error('penyebab_downtime') is-invalid @enderror" placeholder="Masukkan penyebab downtime">{{ old('penyebab_downtime') }}</textarea>
                            @error('penyebab_downtime')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="tindakan_perbaikan">Tindakan Perbaikan</label>
                            <textarea name="tindakan_perbaikan" id="tindakan_perbaikan" rows="3"
                                class="form-control @error('tindakan_perbaikan') is-invalid @enderror" placeholder="Masukkan tindakan perbaikan">{{ old('tindakan_perbaikan') }}</textarea>
                            @error('tindakan_perbaikan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <label for="pic_operator">PIC Operator</label>
                            <input type="text" name="pic_operator" id="pic_operator"
                                class="form-control @error('pic_operator') is-invalid @enderror"
                                value="{{ old('pic_operator') }}" placeholder="Masukkan nama PIC operator">
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
                        Simpan Rekapitulasi Produksi
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

            function resetProductionInfo() {
                $('#batch_shift').text('-');
                $('#batch_cost_center').text('-');
                $('#batch_no_po').text('-');
                $('#batch_tanggal_transfer').text('-');
                $('#batch_tanggal_proses').text('-');
                $('#info_jumlah_produk').text('0');
                $('#info_total_kg').text('0,00 KG');
                $('#production-summary').hide();
            }

            function formatDate(date) {
                if (!date) {
                    return '-';
                }

                const parts = date.split('-');

                if (parts.length !== 3) {
                    return date;
                }

                return `${parts[2]}/${parts[1]}/${parts[0]}`;
            }

            function updateProductionInfo() {
                const selected = $('#production_batch_id').find(':selected');
                const batchId = $('#production_batch_id').val();

                resetProductionInfo();

                if (!batchId) {
                    return;
                }

                $('#batch_shift').text(selected.attr('data-shift') || '-');
                $('#batch_cost_center').text(selected.attr('data-cost-center') || '-');
                $('#batch_no_po').text(selected.attr('data-no-po') || '-');
                $('#batch_tanggal-transfer').text(
                    formatDate(selected.attr('data-tanggal-transfer'))
                );
                $('#batch_tanggal-proses').text(
                    formatDate(selected.attr('data-tanggal-proses'))
                );

                $('#production-summary').show();
                $('#info_jumlah_produk').text('Memuat...');
                $('#info_total_kg').text('Memuat...');

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
            }

            $('#tanggal_produksi').on('change', function() {
                const date = $(this).val();
                const batchSelect = $('#production_batch_id');

                resetProductionInfo();

                batchSelect
                    .empty()
                    .append('<option value="">Memuat production batch...</option>')
                    .prop('disabled', true)
                    .trigger('change.select2');

                if (!date) {
                    batchSelect
                        .empty()
                        .append('<option value="">Pilih tanggal terlebih dahulu</option>')
                        .trigger('change.select2');

                    return;
                }

                $.ajax({
                    url: `{{ url('production-batch/by-date') }}/${date}`,
                    type: 'GET',
                    success: function(response) {
                        batchSelect
                            .empty()
                            .append('<option value="">Pilih Production Batch</option>');

                        if (!response.length) {
                            batchSelect
                                .append(
                                    '<option value="">Tidak ada production batch pada tanggal ini</option>'
                                )
                                .prop('disabled', true)
                                .trigger('change.select2');

                            return;
                        }

                        response.forEach(function(batch) {
                            const option = new Option(
                                `No. PO - ${batch.no_po || '-'}`,
                                batch.id
                            );

                            $(option).attr('data-shift', batch.shift ?? '');
                            $(option).attr('data-cost-center', batch.cost_center_name ??
                                '');
                            $(option).attr('data-no-po', batch.no_po ?? '');
                            $(option).attr('data-tanggal-transfer', batch
                                .tanggal_transfer ?? '');
                            $(option).attr('data-tanggal-proses', batch
                                .tanggal_proses ?? '');

                            batchSelect.append(option);
                        });

                        batchSelect
                            .prop('disabled', false)
                            .trigger('change.select2');

                        const oldBatchId = @json(old('production_batch_id'));

                        if (oldBatchId) {
                            batchSelect.val(String(oldBatchId)).trigger('change');
                        }
                    },
                    error: function() {
                        batchSelect
                            .empty()
                            .append('<option value="">Gagal memuat production batch</option>')
                            .prop('disabled', true)
                            .trigger('change.select2');
                    }
                });
            });

            $('#production_batch_id').on('change', updateProductionInfo);

            const oldTanggal = @json(old('tanggal_produksi'));

            if (oldTanggal) {
                $('#tanggal_produksi').val(oldTanggal).trigger('change');
            }
        });
    </script>
@endpush
