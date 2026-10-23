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

                <input type="hidden" name="production_batch_id" id="production_batch_id"
                    value="{{ old('production_batch_id', $productivity->production_batch_id) }}">
                <input type="hidden" name="bahan_baku_id" id="bahan_baku_id"
                    value="{{ old('bahan_baku_id', $productivity->bahan_baku_id) }}">

                <div class="card mb-4">
                    <div class="card-header">
                        <h4>
                            <span class="step-badge">1</span>
                            Informasi Produksi
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="filter-label">Tanggal Produksi</label>
                                    <input type="date" class="form-control"
                                        value="{{ $productivity->productionBatch?->tanggal ? \Carbon\Carbon::parse($productivity->productionBatch->tanggal)->format('Y-m-d') : '' }}"
                                        disabled>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="filter-label">Shift</label>
                                    <input type="text" class="form-control"
                                        value="{{ $productivity->productionBatch?->shift ?? '-' }}" disabled>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="no_po_bahan_baku_select">
                                    No. PO (Bahan Baku) <span class="text-danger">*</span>
                                </label>
                                <select id="no_po_bahan_baku_select" class="form-control select2" required disabled>
                                    <option value="">Memuat No. PO...</option>
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

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Tanggal Transfer</div>
                                    <div class="info-value">
                                        {{ $productivity->productionBatch?->tanggal_transfer
                                            ? \Carbon\Carbon::parse($productivity->productionBatch->tanggal_transfer)->format('d-m-Y')
                                            : '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Tanggal Proses</div>
                                    <div class="info-value">
                                        {{ $productivity->productionBatch?->tanggal_proses
                                            ? \Carbon\Carbon::parse($productivity->productionBatch->tanggal_proses)->format('d-m-Y')
                                            : '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Jumlah Pekerja</div>
                                    <div class="info-value">
                                        {{ $productivity->productionBatch?->absen_worker ?? 0 }} orang
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
                            <small class="form-text text-muted">
                                Field ini diisi manual dan tidak mengikuti pilihan Material Bahan Baku di atas.
                            </small>
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
                                        id="worker_direct"
                                        class="form-control @error('worker_direct') is-invalid @enderror"
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
                                        placeholder="0.00" readonly>
                                    <small class="form-text text-muted">
                                        Otomatis dihitung: Total Weight / Total Time / Worker Total
                                    </small>
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
                                        placeholder="0.00" readonly>
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
            const EMPTY_PO_VALUE = '__EMPTY_PO__';

            // Batch sudah fix (gak bisa diganti di form edit), jadi ambil langsung dari hidden input.
            const batchId = $('#production_batch_id').val();

            // No. PO dan bahan baku yang SAAT INI dipakai record ini (buat preselect),
            // diambil dari relasi bahanBaku, bukan dari field material_id (itu field terpisah).
            const currentNoPo = @json(optional($productivity->bahanBaku)->no_po);
            const currentBahanBakuId = @json(old('bahan_baku_id', $productivity->bahan_baku_id));

            const poSelect = $('#no_po_bahan_baku_select');
            const bahanBakuSelect = $('#bahan_baku_select');
            const bahanBakuIdInput = $('#bahan_baku_id');

            let bahanBakuRequest = null;

            $('.select2').select2({
                width: '100%',
                placeholder: 'Pilih opsi',
                allowClear: true
            });

            if (!batchId) {
                $('#info_jumlah_produk').text('0');
                $('#info_total_kg').text('0,00 KG');
            } else {
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

            function resetBahanBaku(message = 'Pilih No. PO terlebih dahulu') {
                bahanBakuSelect.empty()
                    .append(new Option(message, '', true, true))
                    .prop('disabled', true)
                    .trigger('change.select2');

                bahanBakuIdInput.val('');
            }

            function loadBahanBaku(noPo, selectedBahanBakuId = '') {
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

            function loadPoList(selectedPo = '') {
                poSelect.empty()
                    .append(new Option('Memuat No. PO...', '', true, true))
                    .prop('disabled', true)
                    .trigger('change.select2');

                if (!batchId) {
                    poSelect.empty()
                        .append(new Option('Production batch tidak ditemukan', '', true, true))
                        .trigger('change.select2');
                    return;
                }

                $.ajax({
                    url: '{{ route('operator.productivity.get-po-list') }}',
                    type: 'GET',
                    data: {
                        production_batch_id: batchId
                    },
                    success: function(groups) {
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
                    error: function() {
                        poSelect.empty()
                            .append(new Option('Gagal memuat No. PO', ''))
                            .prop('disabled', true)
                            .trigger('change.select2');
                    }
                });
            }

            poSelect.on('change', function() {
                const selectedPo = $(this).val();

                resetBahanBaku();

                if (selectedPo === null || selectedPo === '') return;

                const noPo = selectedPo === EMPTY_PO_VALUE ? '' : selectedPo;

                // Kalau yang dipilih sama kayak PO yang lagi dipakai record ini, langsung preselect bahan bakunya.
                const preselectId = (noPo === (currentNoPo ?? '')) ? currentBahanBakuId : '';

                loadBahanBaku(noPo, preselectId);
            });

            bahanBakuSelect.on('change', function() {
                bahanBakuIdInput.val($(this).val() || '');
            });

            $('#productivityForm').on('submit', function(event) {
                if (!bahanBakuIdInput.val()) {
                    event.preventDefault();
                    alert('Silakan pilih Material Bahan Baku terlebih dahulu.');
                }
            });

            const initialPo = currentNoPo === '' ? EMPTY_PO_VALUE : currentNoPo;
            loadPoList(currentNoPo !== null ? initialPo : '');

            function calculateProductivity() {
                const totalWeight = parseFloat($('#total_weight').val()) || 0;
                const totalTime = parseFloat($('#total_time').val()) || 0;
                const workerTotal = parseFloat($('#worker_total').val()) || 0;
                const workerDirect = parseFloat($('#worker_direct').val()) || 0;

                const productivityIndirect = (totalTime > 0 && workerTotal > 0) ?
                    totalWeight / totalTime / workerTotal :
                    0;

                const productivityDirect = (totalTime > 0 && workerDirect > 0) ?
                    totalWeight / totalTime / workerDirect :
                    0;

                $('#productivity_indirect').val(productivityIndirect.toFixed(2));
                $('#productivity_direct').val(productivityDirect.toFixed(2));
            }

            $('#total_weight, #total_time, #worker_total, #worker_direct').on(
                'input',
                calculateProductivity
            );

            calculateProductivity();
        });
    </script>
@endpush
