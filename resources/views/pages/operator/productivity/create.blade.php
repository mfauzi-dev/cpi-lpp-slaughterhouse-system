@extends('layouts.master')

@section('title', 'Tambah Productivity')

@section('content')
    <div class="page-section">
        <div class="section-header">
            <h1>Tambah Productivity</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('operator.productivity.index') }}">Productivity</a>
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

            <form action="{{ route('operator.productivity.store') }}" method="POST">
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
                            <label for="material_id">
                                Nama Product <span class="text-danger">*</span>
                            </label>
                            <select name="material_id" id="material_id"
                                class="form-control select2 @error('material_id') is-invalid @enderror" required>
                                <option value="">Pilih Product</option>
                                @foreach ($materials as $material)
                                    <option value="{{ $material->id }}"
                                        {{ old('material_id') == $material->id ? 'selected' : '' }}>
                                        {{ $material->material_code }} - {{ $material->material_description }}
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
                            Jumlah Tenaga Kerja
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
                                        value="{{ old('worker_indirect') }}" placeholder="Jumlah tenaga kerja indirect">
                                    @error('worker_indirect')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="worker_direct">Worker Direct</label>
                                    <input type="number" min="0" step="1" name="worker_direct"
                                        id="worker_direct"
                                        class="form-control @error('worker_direct') is-invalid @enderror"
                                        value="{{ old('worker_direct') }}" placeholder="Jumlah tenaga kerja direct">
                                    @error('worker_direct')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="worker_total">Worker Total</label>
                                    <input type="number" min="0" step="1" name="worker_total"
                                        id="worker_total" class="form-control @error('worker_total') is-invalid @enderror"
                                        value="{{ old('worker_total') }}" placeholder="Total tenaga kerja">
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
                            Data Produktivitas
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total_weight">Total Weight (KG)</label>
                                    <input type="number" min="0" step="0.01" name="total_weight"
                                        id="total_weight" class="form-control @error('total_weight') is-invalid @enderror"
                                        value="{{ old('total_weight') }}" placeholder="Masukkan total berat">
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
                                        value="{{ old('total_time') }}" placeholder="Masukkan total waktu">
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
                                        value="{{ old('productivity_indirect') }}" placeholder="0.00" readonly>
                                    <small class="form-text text-muted">
                                        Otomatis dihitung: Total Weight / Total Time / Worker Total
                                    </small>
                                    @error('productivity_indirect')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="productivity_direct">Productivity Direct</label>
                                    <input type="number" min="0" step="0.01" name="productivity_direct"
                                        id="productivity_direct"
                                        class="form-control @error('productivity_direct') is-invalid @enderror"
                                        value="{{ old('productivity_direct') }}" placeholder="0.00" readonly>
                                    <small class="form-text text-muted">
                                        Otomatis dihitung: Total Weight / Total Time / Worker Direct
                                    </small>
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
                        Simpan Productivity
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
                $('#batch_tanggal_transfer').text(formatDate(selected.attr('data-tanggal-transfer')));
                $('#batch_tanggal_proses').text(formatDate(selected.attr('data-tanggal-proses')));

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
                        $('#info_jumlah_produk').text((response.jumlah_produk ?? 0) + ' jenis');
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

            // Auto-calculate productivity_indirect & productivity_direct
            function calculateProductivity() {
                const totalWeight = parseFloat($('#total_weight').val()) || 0;
                const totalTime = parseFloat($('#total_time').val()) || 0;
                const workerTotal = parseFloat($('#worker_total').val()) || 0;
                const workerDirect = parseFloat($('#worker_direct').val()) || 0;

                const productivityIndirect = (totalTime > 0 && workerTotal > 0)
                    ? (totalWeight / totalTime / workerTotal)
                    : 0;

                const productivityDirect = (totalTime > 0 && workerDirect > 0)
                    ? (totalWeight / totalTime / workerDirect)
                    : 0;

                $('#productivity_indirect').val(productivityIndirect.toFixed(2));
                $('#productivity_direct').val(productivityDirect.toFixed(2));
            }

            $('#total_weight, #total_time, #worker_total, #worker_direct').on('input',
                calculateProductivity);

            calculateProductivity();
        });
    </script>
@endpush