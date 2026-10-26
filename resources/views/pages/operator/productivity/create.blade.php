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

            <form action="{{ route('operator.productivity.store') }}" method="POST" id="productivityForm">
                @csrf

                <div class="card mb-4">
                    <div class="card-header">
                        <h4><span class="step-badge">1</span> Informasi Produksi</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
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

                            <div class="col-md-6 mb-3">
                                <label for="shift_select">
                                    Shift <span class="text-danger">*</span>
                                </label>
                                <select id="shift_select"
                                    class="form-control select2 @error('production_batch_id') is-invalid @enderror" required
                                    disabled>
                                    <option value="">Pilih tanggal terlebih dahulu</option>
                                </select>
                                @error('production_batch_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted" id="batch_message">
                                    Pilih tanggal untuk menampilkan shift yang tersedia.
                                </small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="no_po_bahan_baku_select">
                                    No. PO (Bahan Baku) <span class="text-danger">*</span>
                                </label>
                                <select id="no_po_bahan_baku_select" class="form-control select2" required disabled>
                                    <option value="">Pilih shift terlebih dahulu</option>
                                </select>
                                <small class="form-text text-muted">
                                    Menentukan daftar bahan baku yang bisa dipilih pada field Material Bahan Baku di
                                    bawah.
                                </small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="bahan_baku_select">
                                    Material Bahan Baku <span class="text-danger">*</span>
                                </label>
                                <select id="bahan_baku_select" class="form-control select2" required disabled>
                                    <option value="">Pilih No. PO terlebih dahulu</option>
                                </select>
                                @error('bahan_baku_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Daftar ini mengikuti No. PO bahan baku yang dipilih di atas.
                                </small>
                            </div>
                        </div>

                        <input type="hidden" name="production_batch_id" id="production_batch_id"
                            value="{{ old('production_batch_id') }}">
                        <input type="hidden" name="bahan_baku_id" id="bahan_baku_id" value="{{ old('bahan_baku_id') }}">

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
                            <small class="form-text text-muted">
                                Nama product hasil produksi diisi manual, tidak mengikuti bahan baku di atas.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card card-accent mb-4">
                    <div class="card-header">
                        <h4><span class="step-badge">2</span> Jumlah Tenaga Kerja</h4>
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
                                        id="worker_direct" class="form-control @error('worker_direct') is-invalid @enderror"
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
                        <h4><span class="step-badge">3</span> Data Produktivitas</h4>
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
            const EMPTY_PO_VALUE = '__EMPTY_PO__';

            const tanggalInput = $('#tanggal_produksi');
            const shiftSelect = $('#shift_select');
            const batchIdInput = $('#production_batch_id');
            const batchMessage = $('#batch_message');
            const poSelect = $('#no_po_bahan_baku_select');
            const bahanBakuSelect = $('#bahan_baku_select');
            const bahanBakuIdInput = $('#bahan_baku_id');

            let shiftRequest = null;
            let poListRequest = null;
            let bahanBakuRequest = null;

            $('.select2').select2({
                width: '100%',
                placeholder: 'Pilih opsi',
                allowClear: true
            });

            function resetShift(message) {
                shiftSelect.empty()
                    .append(new Option(message, '', true, true))
                    .prop('disabled', true)
                    .trigger('change.select2');

                batchIdInput.val('');
            }

            function resetPoList(message = 'Pilih shift terlebih dahulu') {
                poSelect.empty()
                    .append(new Option(message, '', true, true))
                    .prop('disabled', true)
                    .trigger('change.select2');
            }

            function resetBahanBaku(message = 'Pilih No. PO terlebih dahulu') {
                bahanBakuSelect.empty()
                    .append(new Option(message, '', true, true))
                    .prop('disabled', true)
                    .trigger('change.select2');

                bahanBakuIdInput.val('');
            }

            function loadShift(tanggal, selectedBatchId = '') {
                if (shiftRequest) shiftRequest.abort();
                if (poListRequest) poListRequest.abort();
                if (bahanBakuRequest) bahanBakuRequest.abort();

                resetShift('Memuat shift...');
                resetPoList();
                resetBahanBaku();
                batchMessage.text('Mengambil data shift berdasarkan tanggal produksi.');

                if (!tanggal) {
                    resetShift('Pilih tanggal terlebih dahulu');
                    batchMessage.text('Pilih tanggal untuk menampilkan shift yang tersedia.');
                    return;
                }

                shiftRequest = $.ajax({
                    url: '/production-batch/by-date/' + encodeURIComponent(tanggal),
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        shiftSelect.empty();

                        if (!Array.isArray(response) || response.length === 0) {
                            resetShift('Tidak ada data shift');
                            batchMessage.text('Tidak ditemukan data produksi pada tanggal tersebut.');
                            return;
                        }

                        shiftSelect.append(new Option('Pilih shift', '', true, true));

                        response.forEach(function(batch) {
                            shiftSelect.append(
                                new Option(
                                    'Shift ' + (batch.shift || '-') + ' - Conveyor ' + batch
                                    .conveyor,
                                    batch.id,
                                    false,
                                    String(batch.id) === String(selectedBatchId)
                                )
                            );
                        });

                        shiftSelect.prop('disabled', false).trigger('change.select2');

                        if (selectedBatchId) {
                            shiftSelect.val(String(selectedBatchId)).trigger('change');
                        }

                        batchMessage.text('Pilih shift sesuai data produksi yang tersedia.');
                    },
                    error: function(xhr, status) {
                        if (status === 'abort') return;

                        resetShift('Gagal memuat shift');
                        batchMessage.text('Terjadi kesalahan saat mengambil data produksi.');
                        console.error('Gagal mengambil data production batch:', xhr.responseText);
                    },
                    complete: function() {
                        shiftRequest = null;
                    }
                });
            }

            function loadPoList(batchId, selectedPo = '') {
                if (poListRequest) poListRequest.abort();
                if (bahanBakuRequest) bahanBakuRequest.abort();

                resetPoList('Memuat No. PO...');
                resetBahanBaku();

                if (!batchId) {
                    resetPoList();
                    return;
                }

                poListRequest = $.ajax({
                    url: '{{ route('operator.productivity.get-po-list') }}',
                    type: 'GET',
                    data: {
                        production_batch_id: batchId
                    },
                    success: function(groups) {
                        if (String(batchIdInput.val()) !== String(batchId)) return;

                        poSelect.empty().append(new Option('Pilih No. PO', ''));

                        if (!groups || groups.length === 0) {
                            poSelect
                                .append(new Option('Tidak ada bahan baku pada batch ini', ''))
                                .prop('disabled', true)
                                .trigger('change.select2');
                            return;
                        }

                        groups.forEach(function(group) {
                            const isEmptyPo = group.no_po === null || group.no_po === '';
                            const value = isEmptyPo ? EMPTY_PO_VALUE : group.no_po;
                            const label = isEmptyPo ? '(Belum diisi)' : group.no_po;

                            poSelect.append(new Option(
                                `${label} — ${group.jumlah_produk} material`,
                                value,
                                false,
                                value === selectedPo
                            ));
                        });

                        poSelect.prop('disabled', false).trigger('change.select2');

                        if (selectedPo) {
                            poSelect.val(selectedPo).trigger('change');
                        }
                    },
                    error: function(xhr, status) {
                        if (status === 'abort') return;

                        resetPoList('Gagal memuat No. PO');
                    },
                    complete: function() {
                        poListRequest = null;
                    }
                });
            }

            function loadBahanBaku(batchId, noPo, selectedBahanBakuId = '') {
                if (bahanBakuRequest) bahanBakuRequest.abort();

                resetBahanBaku('Memuat bahan baku...');

                bahanBakuRequest = $.ajax({
                    url: '{{ route('operator.productivity.get-bahan-baku-by-po') }}',
                    type: 'GET',
                    data: {
                        production_batch_id: batchId,
                        no_po: noPo
                    },
                    success: function(bahanBakus) {
                        if (String(batchIdInput.val()) !== String(batchId)) return;

                        if (!Array.isArray(bahanBakus) || bahanBakus.length === 0) {
                            resetBahanBaku('Tidak ada bahan baku untuk No. PO ini');
                            return;
                        }

                        bahanBakuSelect.empty().append(new Option('Pilih Material Bahan Baku', ''));

                        bahanBakus.forEach(function(item) {
                            const material = item.material || {};
                            const kode = material.material_code || '-';
                            const nama = material.material_description || '-';

                            bahanBakuSelect.append(
                                new Option(
                                    `${kode} - ${nama}`,
                                    item.id,
                                    false,
                                    String(item.id) === String(selectedBahanBakuId)
                                )
                            );
                        });

                        bahanBakuSelect.prop('disabled', false).trigger('change.select2');

                        if (selectedBahanBakuId) {
                            bahanBakuSelect.val(String(selectedBahanBakuId)).trigger('change');
                        }
                    },
                    error: function(xhr, status) {
                        if (status === 'abort') return;

                        resetBahanBaku('Gagal memuat bahan baku');
                    },
                    complete: function() {
                        bahanBakuRequest = null;
                    }
                });
            }

            tanggalInput.on('change', function() {
                loadShift($(this).val());
            });

            shiftSelect.on('change', function() {
                const batchId = $(this).val() || '';
                batchIdInput.val(batchId);

                if (batchId) {
                    loadPoList(batchId);
                } else {
                    resetPoList();
                    resetBahanBaku();
                }
            });

            poSelect.on('change', function() {
                const selectedPo = $(this).val();
                const batchId = batchIdInput.val();

                resetBahanBaku();

                if (!batchId || selectedPo === null || selectedPo === '') return;

                const noPo = selectedPo === EMPTY_PO_VALUE ? '' : selectedPo;
                loadBahanBaku(batchId, noPo);
            });

            bahanBakuSelect.on('change', function() {
                bahanBakuIdInput.val($(this).val() || '');
            });

            $('#productivityForm').on('submit', function(event) {
                if (!batchIdInput.val()) {
                    event.preventDefault();
                    alert('Silakan pilih tanggal produksi dan shift terlebih dahulu.');
                    return;
                }

                if (!bahanBakuIdInput.val()) {
                    event.preventDefault();
                    alert('Silakan pilih Material Bahan Baku terlebih dahulu.');
                }
            });

            const oldTanggal = @json(old('tanggal_produksi'));
            const oldBatchId = @json(old('production_batch_id'));

            if (oldTanggal) {
                tanggalInput.val(oldTanggal);
                loadShift(oldTanggal, oldBatchId);
            }

            function calculateProductivity() {
                const totalWeight = parseFloat($('#total_weight').val()) || 0;
                const totalTime = parseFloat($('#total_time').val()) || 0;
                const workerTotal = parseFloat($('#worker_total').val()) || 0;
                const workerDirect = parseFloat($('#worker_direct').val()) || 0;

                const productivityIndirect = totalTime > 0 && workerTotal > 0 ?
                    totalWeight / totalTime / workerTotal :
                    0;

                const productivityDirect = totalTime > 0 && workerDirect > 0 ?
                    totalWeight / totalTime / workerDirect :
                    0;

                $('#productivity_indirect').val(productivityIndirect.toFixed(2));
                $('#productivity_direct').val(productivityDirect.toFixed(2));
            }

            $('#total_weight, #total_time, #worker_total, #worker_direct').on('input', calculateProductivity);

            calculateProductivity();
        });
    </script>
@endpush
