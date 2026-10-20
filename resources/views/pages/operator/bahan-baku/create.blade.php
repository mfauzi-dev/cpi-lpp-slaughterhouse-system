@extends('layouts.master')

@section('title', 'Tambah Produksi Bahan Baku')

@section('content')
    <div class="page-section">
        <div class="section-header">
            <h1>Produksi Bahan Baku</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('operator.bahan-baku.index') }}">Produksi Bahan Baku</a>
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

            <form action="{{ route('operator.bahan-baku.store') }}" method="POST" id="productionForm">
                @csrf

                <input type="hidden" name="production_batch_id" id="production_batch_id"
                    value="{{ old('production_batch_id') }}">

                <div class="card mb-4">
                    <div class="card-header">
                        <h4>1. Informasi Produksi</h4>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_produksi">Tanggal Produksi <span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_produksi" id="tanggal_produksi"
                                        class="form-control @error('tanggal_produksi') is-invalid @enderror"
                                        value="{{ old('tanggal_produksi') }}" required>
                                    @error('tanggal_produksi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="shift">Shift <span class="text-danger">*</span></label>
                                    <select id="shift" class="form-control select2" required disabled>
                                        <option value="">Pilih tanggal terlebih dahulu</option>
                                    </select>
                                    <small class="form-text text-muted" id="batch_message">
                                        Pilih tanggal untuk menampilkan shift yang tersedia.
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cost_center_name">Cost Center Name</label>
                                    <select name="cost_center_name" id="cost_center_name"
                                        class="form-control @error('cost_center_name') is-invalid @enderror">
                                        <option value="">Pilih Cost Center</option>
                                        @foreach (['FCHO', 'FCHS', 'FCHM', 'FCHT', 'FCHR', 'FCH1', 'FCH2', 'FCH3', 'FCH4', 'FCH5'] as $costCenter)
                                            <option value="{{ $costCenter }}"
                                                {{ old('cost_center_name') == $costCenter ? 'selected' : '' }}>
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
                                    <label for="no_po">No. PO</label>
                                    <input type="text" name="no_po" id="no_po"
                                        class="form-control @error('no_po') is-invalid @enderror"
                                        value="{{ old('no_po') }}" placeholder="Masukkan nomor PO">
                                    @error('no_po')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="batch-summary" id="production-summary" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-2">
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
                    </div>
                </div>

                <div class="card card-accent mb-4">
                    <div class="card-header">
                        <h4>2. Data Bahan Baku</h4>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-wide" id="productTable">
                                <thead>
                                    <tr>
                                        <th width="70" class="text-center">No</th>
                                        <th>Material</th>
                                        <th>Jumlah Bag</th>
                                        <th>Berat (KG)</th>
                                        <th width="100" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="product-table-body">
                                    @php
                                        $formProducts = old('products');

                                        if (!is_array($formProducts) || count($formProducts) === 0) {
                                            $formProducts = [
                                                ['material_id' => '', 'jumlah_bag' => '', 'jumlah_kg' => ''],
                                            ];
                                        }
                                    @endphp

                                    @foreach ($formProducts as $index => $item)
                                        <tr>
                                            <td class="text-center align-middle nomor">{{ $index + 1 }}</td>
                                            <td>
                                                <select name="products[{{ $index }}][material_id]"
                                                    class="form-control material-select select2" required>
                                                    <option value="">Pilih Material</option>
                                                    @foreach ($materials as $material)
                                                        <option value="{{ $material->id }}"
                                                            {{ (string) ($item['material_id'] ?? '') === (string) $material->id ? 'selected' : '' }}>
                                                            {{ $material->material_code }} -
                                                            {{ $material->material_description }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="products[{{ $index }}][jumlah_bag]"
                                                    class="form-control jumlah-bag" min="0" step="1"
                                                    value="{{ $item['jumlah_bag'] ?? '' }}" placeholder="Jumlah bag"
                                                    required>
                                            </td>
                                            <td>
                                                <input type="number" name="products[{{ $index }}][jumlah_kg]"
                                                    class="form-control jumlah-kg" min="0" step="0.01"
                                                    value="{{ $item['jumlah_kg'] ?? '' }}" placeholder="Berat (KG)"
                                                    required>
                                            </td>
                                            <td class="text-center align-middle">
                                                <button type="button" class="btn btn-outline-danger btn-sm removeRow"
                                                    title="Hapus Baris">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <button type="button" id="btnAddRow" class="btn btn-add mb-2">
                                <i class="fas fa-plus mr-1"></i>
                                Tambah Baris
                            </button>

                            <div class="action-buttons">
                                <a href="{{ route('operator.bahan-baku.index') }}" class="btn btn-ghost mb-2">
                                    <i class="fas fa-arrow-left mr-1"></i>
                                    Kembali
                                </a>
                                <button type="submit" class="btn btn-primary mb-2">
                                    <i class="fas fa-save mr-1"></i>
                                    Simpan Produksi Bahan Baku
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('stisla/node_modules/select2/dist/css/select2.min.css') }}">
    <style>
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            min-height: 38px;
            border: 1px solid #e4e6fc;
            border-radius: 4px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
            padding-left: 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('stisla/node_modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            function initSelect2(element) {
                $(element).select2({
                    width: '100%',
                    placeholder: 'Pilih Material',
                    allowClear: true
                });
            }

            $('.material-select').each(function() {
                initSelect2(this);
            });

            $('#shift').select2({
                width: '100%',
                placeholder: 'Pilih Shift',
                allowClear: true
            });

            let rowIndex = $('#product-table-body tr').length;
            let batchRequest = null;
            let infoRequest = null;

            function formatNumber(value) {
                return Number(value || 0).toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function resetProductionInfo() {
                $('#production_batch_id').val('');
                $('#info_jumlah_produk').text('0');
                $('#info_total_kg').text('0,00 KG');
                $('#production-summary').hide();
            }

            function resetProductRows() {
                $('#product-table-body .material-select').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2('destroy');
                    }
                });

                $('#product-table-body').empty();
                rowIndex = 0;
                addProductRow();
            }

            function buildMaterialOptions(selectedId = '') {
                let options = '<option value="">Pilih Material</option>';

                @foreach ($materials as $material)
                    options +=
                        `<option value="{{ $material->id }}" ${String(selectedId) === '{{ $material->id }}' ? 'selected' : ''}>{{ addslashes($material->material_code) }} - {{ addslashes($material->material_description) }}</option>`;
                @endforeach

                return options;
            }

            function addProductRow(materialId = '', jumlahBag = '', jumlahKg = '') {
                const index = rowIndex++;

                const row = `
                    <tr>
                        <td class="text-center align-middle nomor"></td>
                        <td>
                            <select name="products[${index}][material_id]"
                                class="form-control material-select" required>
                                ${buildMaterialOptions(materialId)}
                            </select>
                        </td>
                        <td>
                            <input type="number" name="products[${index}][jumlah_bag]"
                                class="form-control jumlah-bag" min="0" step="1"
                                value="${jumlahBag}" placeholder="Jumlah bag" required>
                        </td>
                        <td>
                            <input type="number" name="products[${index}][jumlah_kg]"
                                class="form-control jumlah-kg" min="0" step="0.01"
                                value="${jumlahKg}" placeholder="Berat (KG)" required>
                        </td>
                        <td class="text-center align-middle">
                            <button type="button" class="btn btn-outline-danger btn-sm removeRow" title="Hapus Baris">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;

                const $row = $(row);
                $('#product-table-body').append($row);

                initSelect2($row.find('.material-select'));

                renumberRows();
            }

            function renumberRows() {
                $('#product-table-body tr').each(function(index) {
                    $(this).find('.nomor').text(index + 1);
                });
            }

            $('#btnAddRow').on('click', function() {
                addProductRow();
            });

            $(document).on('click', '.removeRow', function() {
                if ($('#product-table-body tr').length <= 1) {
                    alert('Minimal harus ada satu baris bahan baku.');
                    return;
                }

                const $row = $(this).closest('tr');
                const $select = $row.find('.material-select');

                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }

                $row.remove();
                renumberRows();
            });

            $('#tanggal_produksi').on('change', function() {
                const tanggal = $(this).val();
                const shiftSelect = $('#shift');

                if (batchRequest) {
                    batchRequest.abort();
                }

                if (infoRequest) {
                    infoRequest.abort();
                }

                resetProductionInfo();
                resetProductRows();

                shiftSelect.empty()
                    .append(new Option('Memuat shift...', ''))
                    .prop('disabled', true)
                    .trigger('change.select2');

                $('#batch_message').text('Memuat data production batch...');

                if (!tanggal) {
                    shiftSelect.empty()
                        .append(new Option('Pilih tanggal terlebih dahulu', ''))
                        .trigger('change.select2');

                    $('#batch_message').text('Pilih tanggal untuk menampilkan shift yang tersedia.');
                    return;
                }

                batchRequest = $.ajax({
                    url: `/production-batch/by-date/${encodeURIComponent(tanggal)}`,
                    type: 'GET',
                    success: function(response) {
                        shiftSelect.empty().append(new Option('Pilih Shift', ''));

                        if (!Array.isArray(response) || response.length === 0) {
                            shiftSelect.empty()
                                .append(new Option('Tidak ada batch pada tanggal ini', ''))
                                .prop('disabled', true)
                                .trigger('change.select2');

                            $('#batch_message').text(
                                'Tidak ditemukan production batch pada tanggal tersebut.');
                            return;
                        }

                        response.forEach(function(batch) {
                            const option = new Option(`Shift ${batch.shift}`, batch
                                .shift);

                            $(option).attr('data-batch-id', batch.id);
                            $(option).attr('data-tanggal-transfer', batch
                                .tanggal_transfer ?? '');
                            $(option).attr('data-tanggal-proses', batch
                                .tanggal_proses ?? '');

                            shiftSelect.append(option);
                        });

                        shiftSelect.prop('disabled', false).trigger('change.select2');
                        $('#batch_message').text(
                            'Pilih shift untuk menentukan production batch.');
                    },
                    error: function(xhr, status) {
                        if (status === 'abort') {
                            return;
                        }

                        shiftSelect.empty()
                            .append(new Option('Gagal memuat shift', ''))
                            .prop('disabled', true)
                            .trigger('change.select2');

                        $('#batch_message').text(
                            'Terjadi kesalahan saat mengambil data production batch.');
                    }
                });
            });

            $('#shift').on('change', function() {
                const selected = $(this).find(':selected');
                const batchId = selected.attr('data-batch-id');

                if (infoRequest) {
                    infoRequest.abort();
                }

                resetProductionInfo();

                if (!batchId) {
                    return;
                }

                $('#production_batch_id').val(batchId);
                $('#production-summary').show();
                $('#info_jumlah_produk').text('Memuat...');
                $('#info_total_kg').text('Memuat...');

                infoRequest = $.ajax({
                    url: '{{ route('operator.hasil-produksi.get-products') }}',
                    type: 'GET',
                    data: {
                        production_batch_id: batchId
                    },
                    success: function(response) {
                        $('#info_jumlah_produk').text((response.jumlah_produk ?? 0) + ' jenis');
                        $('#info_total_kg').text(formatNumber(response.total_kg) + ' KG');
                    },
                    error: function(xhr, status) {
                        if (status === 'abort') {
                            return;
                        }

                        $('#info_jumlah_produk').text('-');
                        $('#info_total_kg').text('-');
                    }
                });
            });

            $('#productionForm').on('submit', function(event) {
                if (!$('#production_batch_id').val()) {
                    event.preventDefault();
                    alert('Silakan pilih tanggal produksi dan shift terlebih dahulu.');
                    return;
                }

                const selectedMaterials = [];
                let duplicateMaterial = false;

                $('#product-table-body tr').each(function() {
                    const materialId = $(this).find('.material-select').val();

                    if (!materialId) {
                        return;
                    }

                    if (selectedMaterials.includes(materialId)) {
                        duplicateMaterial = true;
                        return false;
                    }

                    selectedMaterials.push(materialId);
                });

                if (duplicateMaterial) {
                    event.preventDefault();
                    alert('Material yang sama tidak boleh dipilih lebih dari satu kali.');
                }
            });

            renumberRows();

            const oldTanggal = @json(old('tanggal_produksi'));

            if (oldTanggal) {
                $('#tanggal_produksi').val(oldTanggal).trigger('change');
            }
        });
    </script>
@endpush
