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

            <form action="{{ route('operator.hasil-produksi.update', $hasilProduksi->id) }}" method="POST" id="hasilProduksiForm">
                @csrf
                @method('PUT')

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
                                    value="{{ old('tanggal_produksi', $hasilProduksi->productionBatch?->tanggal ? \Carbon\Carbon::parse($hasilProduksi->productionBatch->tanggal)->format('Y-m-d') : '') }}"
                                    required>
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
                                    <option value="">Memuat production batch...</option>
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
                                    <div class="info-value" id="batch_shift">{{ $hasilProduksi->productionBatch?->shift ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Cost Center Name</div>
                                    <div class="info-value" id="batch_cost_center">{{ $hasilProduksi->productionBatch?->cost_center_name ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <div class="info-label">No. PO</div>
                                    <div class="info-value" id="batch_no_po">{{ $hasilProduksi->productionBatch?->no_po ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Tanggal Transfer</div>
                                    <div class="info-value" id="batch_tanggal_transfer">
                                        {{ $hasilProduksi->productionBatch?->tanggal_transfer ? \Carbon\Carbon::parse($hasilProduksi->productionBatch->tanggal_transfer)->format('d/m/Y') : '-' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Tanggal Proses</div>
                                    <div class="info-value" id="batch_tanggal_proses">
                                        {{ $hasilProduksi->productionBatch?->tanggal_proses ? \Carbon\Carbon::parse($hasilProduksi->productionBatch->tanggal_proses)->format('d/m/Y') : '-' }}
                                    </div>
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
                                            id="domestik_kg" class="form-control" placeholder="0,00"
                                            value="{{ old('domestik_kg', $hasilProduksi->domestik_kg) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="further_kg">Further (KG)</label>
                                        <input type="number" step="0.01" min="0" name="further_kg"
                                            id="further_kg" class="form-control" placeholder="0,00"
                                            value="{{ old('further_kg', $hasilProduksi->further_kg) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="frozen_kg">Frozen (KG)</label>
                                        <input type="number" step="0.01" min="0" name="frozen_kg"
                                            id="frozen_kg" class="form-control" placeholder="0,00"
                                            value="{{ old('frozen_kg', $hasilProduksi->frozen_kg) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label for="rm">RM (KG)</label>
                            <input type="number" step="0.01" min="0" name="rm" id="rm"
                                class="form-control" placeholder="Masukkan berat RM"
                                value="{{ old('rm', $hasilProduksi->rm) }}">
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
                                        class="form-control" placeholder="0"
                                        value="{{ old('total_bag', $hasilProduksi->total_bag) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total_kg">Total KG</label>
                                    <input type="number" step="0.01" min="0" name="total_kg" id="total_kg"
                                        class="form-control" placeholder="0,00"
                                        value="{{ old('total_kg', $hasilProduksi->total_kg) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="loss_kg">Loss (KG)</label>
                                    <input type="number" step="0.01" min="0" name="loss_kg" id="loss_kg"
                                        class="form-control" placeholder="0,00"
                                        value="{{ old('loss_kg', $hasilProduksi->loss_kg) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="loss_persen">Loss (%)</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" name="loss_persen"
                                            id="loss_persen" class="form-control" placeholder="0,00"
                                            value="{{ old('loss_persen', $hasilProduksi->loss_persen) }}">
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
                                        class="form-control" placeholder="0,00"
                                        value="{{ old('pickup_kg', $hasilProduksi->pickup_kg) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pickup_persen">Pickup (%)</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" name="pickup_persen"
                                            id="pickup_persen" class="form-control" placeholder="0,00"
                                            value="{{ old('pickup_persen', $hasilProduksi->pickup_persen) }}">
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
                            <i class="fas fa-calculator mr-1"></i>
                            Perhitungan Yield
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="section-info mb-3">
                            <i class="fas fa-info-circle"></i>
                            Pilih bahan baku dari production batch ini untuk menentukan Jumlah KG sebagai pembagi
                            yield. Field RM pada bagian Detail Hasil Produksi diisi manual dan tidak terpengaruh oleh
                            pilihan ini.
                        </div>

                        <div class="form-group">
                            <label for="bahan_baku_pilihan">Pilih Bahan Baku (untuk Jumlah KG)</label>
                            <select id="bahan_baku_pilihan" class="form-control select2" disabled>
                                <option value="">Pilih production batch terlebih dahulu</option>
                            </select>
                        </div>

                        <input type="hidden" name="jumlah_kg_bahan_baku" id="jumlah_kg_bahan_baku"
                            value="{{ old('jumlah_kg_bahan_baku', $hasilProduksi->jumlah_kg_bahan_baku ?? 0) }}">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-0">
                                    <label for="preview_jumlah_kg">Jumlah KG (Bahan Baku Terpilih)</label>
                                    <input type="text" id="preview_jumlah_kg" class="form-control"
                                        value="0,00" readonly>
                                    <small class="form-text text-muted">
                                        Diambil dari Jumlah KG bahan baku yang dipilih.
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-0">
                                    <label for="preview_hasil_finish_good">Hasil Finish Good (KG)</label>
                                    <input type="text" id="preview_hasil_finish_good" class="form-control"
                                        value="0,00" readonly>
                                    <small class="form-text text-muted">
                                        Mengikuti nilai Total KG pada bagian atas.
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-0">
                                    <label for="preview_yield_persen">Yield (%)</label>
                                    <div class="input-group">
                                        <input type="text" id="preview_yield_persen" class="form-control"
                                            value="0,00" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">
                                        (Hasil Finish Good ÷ Jumlah KG) × 100.
                                    </small>
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
                                            id="pengecekan_suhu_suhu" class="form-control" placeholder="Masukkan suhu"
                                            value="{{ old('pengecekan_suhu_suhu', $hasilProduksi->pengecekan_suhu_suhu) }}">
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
                                        class="form-control" placeholder="Masukkan nama kemasan"
                                        value="{{ old('packaging_nama_kemasan', $hasilProduksi->packaging_nama_kemasan) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="packaging_jumlah_kg">Jumlah Packaging (KG)</label>
                                    <input type="number" step="0.01" min="0" name="packaging_jumlah_kg"
                                        id="packaging_jumlah_kg" class="form-control" placeholder="0,00"
                                        value="{{ old('packaging_jumlah_kg', $hasilProduksi->packaging_jumlah_kg) }}">
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
            $('.select2').select2({
                width: '100%',
                placeholder: 'Pilih opsi',
                allowClear: true
            });

            const batchIdAwal = @json(old('production_batch_id', $hasilProduksi->production_batch_id));
            const tanggalAwal = @json(old('tanggal_produksi', $hasilProduksi->productionBatch?->tanggal ? \Carbon\Carbon::parse($hasilProduksi->productionBatch->tanggal)->format('Y-m-d') : ''));
            const jumlahKgAwal = Number(@json(old('jumlah_kg_bahan_baku', $hasilProduksi->jumlah_kg_bahan_baku ?? 0))) || 0;

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

            function recalculatePreview() {
                const hasilFinishGood = parseFloat($('#total_kg').val()) || 0;
                const jumlahKg = parseFloat($('#jumlah_kg_bahan_baku').val()) || 0;

                $('#preview_hasil_finish_good').val(
                    hasilFinishGood.toLocaleString('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })
                );

                $('#preview_jumlah_kg').val(
                    jumlahKg.toLocaleString('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })
                );

                const yieldPersen = jumlahKg > 0 ? (hasilFinishGood / jumlahKg) * 100 : 0;

                $('#preview_yield_persen').val(
                    yieldPersen.toLocaleString('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })
                );
            }

            $('#total_kg').on('input', recalculatePreview);

            function populateBahanBakuPilihan(bahanBakus) {
                const select = $('#bahan_baku_pilihan');

                select.empty().append('<option value="">Pilih Bahan Baku</option>');

                if (!bahanBakus || !bahanBakus.length) {
                    select
                        .append('<option value="">Tidak ada data bahan baku pada batch ini</option>')
                        .prop('disabled', true)
                        .trigger('change.select2');

                    $('#jumlah_kg_bahan_baku').val(jumlahKgAwal || '');
                    recalculatePreview();
                    return;
                }

                let optionTerpilih = false;

                bahanBakus.forEach(function(item) {
                    const kode = item.material?.material_code ?? '-';
                    const nama = item.material?.material_description ?? '-';
                    const beratKg = Number(item.jumlah_kg || 0).toLocaleString('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    const option = new Option(`${kode} - ${nama} (${beratKg} KG)`, item.id);
                    $(option).attr('data-jumlah-kg', item.jumlah_kg);

                    if (!optionTerpilih && jumlahKgAwal > 0 &&
                        Math.abs(Number(item.jumlah_kg || 0) - jumlahKgAwal) < 0.01) {
                        option.selected = true;
                        optionTerpilih = true;
                        $('#jumlah_kg_bahan_baku').val(item.jumlah_kg);
                    }

                    select.append(option);
                });

                if (!optionTerpilih) {
                    $('#jumlah_kg_bahan_baku').val(jumlahKgAwal || '');
                }

                select.prop('disabled', false).trigger('change.select2');
                recalculatePreview();
            }

            $('#bahan_baku_pilihan').on('change', function() {
                const jumlahKg = $(this).find(':selected').attr('data-jumlah-kg');

                if (jumlahKg !== undefined) {
                    $('#jumlah_kg_bahan_baku').val(jumlahKg);
                } else if (!$(this).val()) {
                    $('#jumlah_kg_bahan_baku').val('');
                }

                recalculatePreview();
            });

            function updateProductionInfo() {
                const selected = $('#production_batch_id').find(':selected');
                const batchId = $('#production_batch_id').val();

                resetProductionInfo();

                $('#bahan_baku_pilihan')
                    .empty()
                    .append('<option value="">Memuat bahan baku...</option>')
                    .prop('disabled', true)
                    .trigger('change.select2');

                if (!batchId) {
                    $('#jumlah_kg_bahan_baku').val('');
                    recalculatePreview();
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

                        populateBahanBakuPilihan(response.bahan_bakus || []);
                    },
                    error: function() {
                        $('#info_jumlah_produk').text('-');
                        $('#info_total_kg').text('-');

                        $('#bahan_baku_pilihan')
                            .empty()
                            .append('<option value="">Gagal memuat bahan baku</option>')
                            .prop('disabled', true)
                            .trigger('change.select2');

                        recalculatePreview();
                    }
                });
            }

            function loadProductionBatches(date, selectedBatchId) {
                const batchSelect = $('#production_batch_id');

                batchSelect
                    .empty()
                    .append('<option value="">Memuat production batch...</option>')
                    .prop('disabled', true)
                    .trigger('change.select2');

                if (!date) {
                    batchSelect
                        .empty()
                        .append('<option value="">Pilih tanggal terlebih dahulu</option>')
                        .prop('disabled', true)
                        .trigger('change.select2');

                    resetProductionInfo();

                    $('#bahan_baku_pilihan')
                        .empty()
                        .append('<option value="">Pilih production batch terlebih dahulu</option>')
                        .prop('disabled', true)
                        .trigger('change.select2');

                    $('#jumlah_kg_bahan_baku').val('');
                    recalculatePreview();
                    return;
                }

                $.ajax({
                    url: `{{ url('production-batch/by-date') }}/${date}`,
                    type: 'GET',
                    success: function(response) {
                        batchSelect.empty().append('<option value="">Pilih Production Batch</option>');

                        if (!response.length) {
                            batchSelect
                                .append('<option value="">Tidak ada production batch pada tanggal ini</option>')
                                .prop('disabled', true)
                                .trigger('change.select2');

                            resetProductionInfo();

                            $('#bahan_baku_pilihan')
                                .empty()
                                .append('<option value="">Tidak ada bahan baku</option>')
                                .prop('disabled', true)
                                .trigger('change.select2');

                            $('#jumlah_kg_bahan_baku').val('');
                            recalculatePreview();
                            return;
                        }

                        response.forEach(function(batch) {
                            const option = new Option(`No. PO - ${batch.no_po || '-'}`, batch.id);

                            $(option).attr('data-shift', batch.shift ?? '');
                            $(option).attr('data-cost-center', batch.cost_center_name ?? '');
                            $(option).attr('data-no-po', batch.no_po ?? '');
                            $(option).attr('data-tanggal-transfer', batch.tanggal_transfer ?? '');
                            $(option).attr('data-tanggal-proses', batch.tanggal_proses ?? '');

                            batchSelect.append(option);
                        });

                        batchSelect.prop('disabled', false);

                        if (selectedBatchId && batchSelect.find(`option[value="${selectedBatchId}"]`).length) {
                            batchSelect.val(String(selectedBatchId));
                        }

                        batchSelect.trigger('change.select2');

                        if (batchSelect.val()) {
                            updateProductionInfo();
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
            }

            $('#tanggal_produksi').on('change', function() {
                loadProductionBatches($(this).val(), null);
            });

            $('#production_batch_id').on('change', updateProductionInfo);

            if (tanggalAwal) {
                $('#tanggal_produksi').val(tanggalAwal);
                loadProductionBatches(tanggalAwal, batchIdAwal);
            } else {
                resetProductionInfo();
            }

            recalculatePreview();
        });
    </script>
@endpush
