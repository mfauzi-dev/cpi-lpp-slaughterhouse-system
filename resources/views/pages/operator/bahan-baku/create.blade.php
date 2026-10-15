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

        <div class="section-lead">
            Tambahkan data produksi bahan baku berdasarkan production batch, jumlah bag, dan berat dalam kilogram.
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

            <form action="{{ route('operator.bahan-baku.store') }}" method="POST" id="bahanBakuForm">
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
                    </div>
                </div>

                <div class="card card-accent mb-4" id="product-card">
                    <div class="card-header">
                        <h4>
                            <span class="step-badge">2</span>
                            Data Produk
                        </h4>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-wide" id="productTable">
                                <thead>
                                    <tr>
                                        <th width="70" class="text-center">No</th>
                                        <th>Nama Produk</th>
                                        <th>Jumlah Bag</th>
                                        <th>Jumlah KG</th>
                                        <th width="100" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="product-table-body">
                                    @php
                                        $oldProducts = old('products', [
                                            ['material_id' => '', 'jumlah_bag' => '', 'jumlah_kg' => ''],
                                        ]);
                                    @endphp

                                    @foreach ($oldProducts as $index => $product)
                                        <tr data-row-index="{{ $index }}">
                                            <td class="text-center align-middle nomor">{{ $loop->iteration }}</td>
                                            <td>
                                                <select name="products[{{ $index }}][material_id]"
                                                    class="form-control select2 material-select" required>
                                                    <option value="">Pilih Nama Produk</option>
                                                    @foreach ($materials as $material)
                                                        <option value="{{ $material->id }}"
                                                            {{ ($product['material_id'] ?? '') == $material->id ? 'selected' : '' }}>
                                                            {{ $material->material_code }} -
                                                            {{ $material->material_description }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="products[{{ $index }}][jumlah_bag]"
                                                    class="form-control jumlah-bag" placeholder="Jumlah bag"
                                                    value="{{ $product['jumlah_bag'] ?? '' }}" min="0"
                                                    step="1" required>
                                            </td>
                                            <td>
                                                <input type="number" name="products[{{ $index }}][jumlah_kg]"
                                                    class="form-control jumlah-kg" placeholder="Berat (KG)"
                                                    value="{{ $product['jumlah_kg'] ?? '' }}" min="0" step="0.01"
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

                    <div class="card-footer bg-white border-top">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="info-box">
                                    <div class="info-label">Jumlah Jenis Produk</div>
                                    <div class="info-value" id="total_produk">0 produk</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <div class="info-label">Total Berat</div>
                                    <div class="info-value" id="total_kg">0,00 KG</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <button type="button" id="btnAddRow" class="btn btn-add mb-2 mb-md-0">
                                <i class="fas fa-plus mr-1"></i> Tambah Baris
                            </button>
                            <div class="action-buttons">
                                <a href="{{ route('operator.bahan-baku.index') }}" class="btn btn-ghost">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const materials =
                {{ Illuminate\Support\Js::from(
                    $materials->map(function ($material) {
                            return [
                                'id' => $material->id,
                                'material_code' => $material->material_code,
                                'material_description' => $material->material_description,
                            ];
                        })->values(),
                ) }};

            let rowIndex = {{ count($oldProducts) }};

            $('.select2').select2({
                width: '100%',
                placeholder: 'Pilih opsi',
                allowClear: true
            });

            function escapeHtml(value) {
                return $('<div>').text(value ?? '').html();
            }

            function buildMaterialOptions(selectedId = '') {
                let options = '<option value="">Pilih Nama Produk</option>';

                materials.forEach(function(material) {
                    const selected = String(material.id) === String(selectedId) ? 'selected' : '';

                    options += `<option value="${material.id}" ${selected}>
                        ${escapeHtml(material.material_code)} - ${escapeHtml(material.material_description)}
                    </option>`;
                });

                return options;
            }

            function resetBatchInfo() {
                $('#batch_shift').text('-');
                $('#batch_cost_center').text('-');
                $('#batch_no_po').text('-');
                $('#batch_tanggal_transfer').text('-');
                $('#batch_tanggal_proses').text('-');
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

            function updateSummary() {
                let totalKg = 0;
                let totalProduk = 0;
                const selectedIds = [];

                $('#product-table-body tr').each(function(index) {
                    $(this).find('.nomor').text(index + 1);

                    const materialId = $(this).find('.material-select').val();

                    if (materialId) {
                        totalProduk++;
                        selectedIds.push(String(materialId));
                    }

                    totalKg += parseFloat($(this).find('.jumlah-kg').val()) || 0;
                });

                $('#total_produk').text(totalProduk + ' produk');
                $('#total_kg').text(totalKg.toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' KG');

                $('.material-select').each(function() {
                    const currentId = String($(this).val() || '');

                    $(this).find('option').each(function() {
                        const optionId = String($(this).val() || '');
                        const duplicate = optionId !== '' &&
                            optionId !== currentId &&
                            selectedIds.includes(optionId);

                        $(this).prop('disabled', duplicate);
                    });

                    $(this).trigger('change.select2');
                });
            }

            $('#tanggal_produksi').on('change', function() {
                const date = $(this).val();
                const batchSelect = $('#production_batch_id');

                resetBatchInfo();

                batchSelect
                    .empty()
                    .append('<option value="">Memuat production batch...</option>')
                    .prop('disabled', true)
                    .trigger('change');

                if (!date) {
                    batchSelect
                        .empty()
                        .append('<option value="">Pilih tanggal terlebih dahulu</option>')
                        .trigger('change');

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
                                .trigger('change');

                            return;
                        }

                        response.forEach(function(batch) {
                            batchSelect.append(`
                                <option
                                    value="${batch.id}"
                                    data-shift="${escapeHtml(batch.shift ?? '')}"
                                    data-cost-center="${escapeHtml(batch.cost_center_name ?? '')}"
                                    data-no-po="${escapeHtml(batch.no_po ?? '')}"
                                    data-tanggal-transfer="${escapeHtml(batch.tanggal_transfer ?? '')}"
                                    data-tanggal-proses="${escapeHtml(batch.tanggal_proses ?? '')}">
                                    No. PO - #${batch.no_po}
                                </option>
                            `);
                        });

                        batchSelect
                            .prop('disabled', false)
                            .trigger('change');

                        const oldBatchId = @json(old('production_batch_id'));

                        if (oldBatchId) {
                            batchSelect.val(String(oldBatchId)).trigger('change');
                        }
                    },
                    error: function() {
                        batchSelect
                            .empty()
                            .append('<option value="">Gagal memuat production batch</option>')
                            .trigger('change');
                    }
                });
            });

            $('#production_batch_id').on('change', function() {
                const selected = $(this).find(':selected');

                if (!$(this).val()) {
                    resetBatchInfo();
                    return;
                }

                $('#batch_shift').text(selected.attr('data-shift') || '-');
                $('#batch_cost_center').text(selected.attr('data-cost-center') || '-');
                $('#batch_no_po').text(selected.attr('data-no-po') || '-');
                $('#batch_tanggal_transfer').text(
                    formatDate(selected.attr('data-tanggal-transfer'))
                );
                $('#batch_tanggal_proses').text(
                    formatDate(selected.attr('data-tanggal-proses'))
                );
            });

            $('#btnAddRow').on('click', function() {
                const index = rowIndex++;

                const html = `
                    <tr data-row-index="${index}">
                        <td class="text-center align-middle nomor"></td>
                        <td>
                            <select name="products[${index}][material_id]"
                                class="form-control material-select" required>
                                ${buildMaterialOptions()}
                            </select>
                        </td>
                        <td>
                            <input type="number" name="products[${index}][jumlah_bag]"
                                class="form-control jumlah-bag" placeholder="Jumlah bag"
                                min="0" step="1" required>
                        </td>
                        <td>
                            <input type="number" name="products[${index}][jumlah_kg]"
                                class="form-control jumlah-kg" placeholder="Berat (KG)"
                                min="0" step="0.01" required>
                        </td>
                        <td class="text-center align-middle">
                            <button type="button" class="btn btn-outline-danger btn-sm removeRow"
                                title="Hapus Baris">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;

                const row = $(html);
                $('#product-table-body').append(row);

                row.find('.material-select').select2({
                    width: '100%',
                    placeholder: 'Pilih Nama Produk',
                    allowClear: true
                });

                updateSummary();
            });

            $(document).on('click', '.removeRow', function() {
                if ($('#product-table-body tr').length <= 1) {
                    alert('Minimal harus ada 1 baris produk.');
                    return;
                }

                const row = $(this).closest('tr');
                const select = row.find('.material-select');

                if (select.hasClass('select2-hidden-accessible')) {
                    select.select2('destroy');
                }

                row.remove();
                updateSummary();
            });

            $(document).on('change', '.material-select', updateSummary);
            $(document).on('input change', '.jumlah-kg', updateSummary);

            $('#bahanBakuForm').on('submit', function(e) {
                const selected = [];
                let valid = true;

                if (!$('#production_batch_id').val()) {
                    alert('Pilih production batch terlebih dahulu.');
                    e.preventDefault();
                    return;
                }

                $('.material-select').each(function() {
                    const id = $(this).val();

                    if (!id) {
                        alert('Pilih produk untuk setiap baris.');
                        valid = false;
                        return false;
                    }

                    if (selected.includes(String(id))) {
                        alert('Produk yang sama tidak boleh dipilih lebih dari sekali.');
                        valid = false;
                        return false;
                    }

                    selected.push(String(id));
                });

                if (!valid) {
                    e.preventDefault();
                }
            });

            updateSummary();

            const oldTanggal = @json(old('tanggal_produksi'));

            if (oldTanggal) {
                $('#tanggal_produksi').val(oldTanggal).trigger('change');
            }
        });
    </script>
@endpush
