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
                                <select id="shift_select" class="form-control select2" required disabled>
                                    <option value="">Pilih tanggal terlebih dahulu</option>
                                </select>
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
                            <div class="col-md-12 mb-3">
                                <label for="mesin">
                                    Mesin <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="mesin" id="mesin"
                                    class="form-control @error('mesin') is-invalid @enderror" value="{{ old('mesin') }}"
                                    placeholder="Masukkan nama mesin" required>
                                @error('mesin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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
                shiftSelect.empty().append(
                    new Option(message, '', true, true)
                ).prop('disabled', true).trigger('change');

                batchIdInput.val('');
            }

            function resetPoList(message = 'Pilih shift terlebih dahulu') {
                poSelect.empty()
                    .append(new Option(message, '', true, true))
                    .prop('disabled', true)
                    .trigger('change');
            }

            function resetBahanBaku(message = 'Pilih No. PO terlebih dahulu') {
                bahanBakuSelect.empty()
                    .append(new Option(message, '', true, true))
                    .prop('disabled', true)
                    .trigger('change');

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
                                    'Shift ' + batch.shift,
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
                    url: '{{ route('operator.rekapitulasi-produksi.get-po-list') }}',
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
                    url: '{{ route('operator.rekapitulasi-produksi.get-bahan-baku-by-po') }}',
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

            $('#rekapitulasiProduksiForm').on('submit', function(event) {
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

            const oldTanggal = tanggalInput.val();
            const oldBatchId = batchIdInput.val();

            if (oldTanggal) {
                loadShift(oldTanggal, oldBatchId);
            }
        });
    </script>
@endpush
