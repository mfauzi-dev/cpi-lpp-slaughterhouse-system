@extends('layouts.master')

@section('title', 'Tambah Data Hasil Produksi')

@section('content')
    <div class="page-section">
        <div class="section-header">
            <h1>Data Hasil Produksi</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('operator.hasil-produksi.index') }}">Data Hasil Produksi</a>
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

            <form action="{{ route('operator.hasil-produksi.store') }}" method="POST" id="hasilProduksiForm">
                @csrf

                <input type="hidden" name="production_batch_id" id="production_batch_id"
                    value="{{ old('production_batch_id') }}">
                <input type="hidden" name="jumlah_kg_bahan_baku" id="jumlah_kg_bahan_baku"
                    value="{{ old('jumlah_kg_bahan_baku') }}">
                <input type="hidden" name="bahan_baku_id" id="bahan_baku_id" value="{{ old('bahan_baku_id') }}">

                <div class="card mb-4">
                    <div class="card-header">
                        <h4><span class="step-badge">1</span> Informasi Produksi</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_produksi">Tanggal Produksi <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_produksi" id="tanggal_produksi"
                                    class="form-control @error('tanggal_produksi') is-invalid @enderror"
                                    value="{{ old('tanggal_produksi') }}" required>
                                @error('tanggal_produksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="shift_select">Shift <span class="text-danger">*</span></label>
                                <select id="shift_select" class="form-control select2" required disabled>
                                    <option value="">Pilih tanggal terlebih dahulu</option>
                                </select>
                                <small class="form-text text-muted" id="batch_message">Pilih tanggal untuk menampilkan
                                    shift yang tersedia.</small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="no_po_bahan_baku_select">No. PO (Bahan Baku) <span
                                        class="text-danger">*</span></label>
                                <select id="no_po_bahan_baku_select" class="form-control select2" required disabled>
                                    <option value="">Pilih shift terlebih dahulu</option>
                                </select>
                                <small class="form-text text-muted">
                                    Menentukan daftar bahan baku pada Perhitungan Yield. Tidak mengisi No. PO hasil
                                    produksi di bawah ini.
                                </small>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6 mb-3">
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
                                <small class="form-text text-muted">Cost center hasil produksi diisi manual, tidak
                                    mengikuti bahan baku.</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="no_po">No. PO</label>
                                <input type="text" name="no_po" id="no_po"
                                    class="form-control @error('no_po') is-invalid @enderror" value="{{ old('no_po') }}"
                                    placeholder="Masukkan nomor PO">
                                @error('no_po')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">No. PO hasil produksi diisi manual, tidak mengikuti No.
                                    PO bahan baku.</small>
                            </div>
                        </div>

                        <div class="batch-summary" id="production-summary" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-2 mb-md-0">
                                    <i class="fas fa-boxes"></i>
                                    Jumlah Jenis Bahan Baku (PO ini):
                                    <strong id="info_jumlah_produk">0</strong>
                                </div>
                                <div class="col-md-6">
                                    <i class="fas fa-weight-hanging"></i>
                                    Total Berat Bahan Baku (PO ini):
                                    <strong id="info_total_kg">0,00 KG</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-accent mb-4">
                    <div class="card-header">
                        <h4><span class="step-badge">2</span> Detail Hasil Produksi</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="material_id">Material <span class="text-danger">*</span></label>
                            <select name="material_id" id="material_id"
                                class="form-control select2 @error('material_id') is-invalid @enderror" required>
                                <option value="">Memuat seluruh material...</option>
                            </select>
                            @error('material_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Menampilkan seluruh material dari master Material, tidak
                                dibatasi batch atau No. PO bahan baku.</small>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="domestik_kg">Domestik (KG)</label>
                                        <input type="number" step="0.01" min="0" name="domestik_kg"
                                            id="domestik_kg" class="form-control" placeholder="0,00"
                                            value="{{ old('domestik_kg') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="further_kg">Further (KG)</label>
                                        <input type="number" step="0.01" min="0" name="further_kg"
                                            id="further_kg" class="form-control" placeholder="0,00"
                                            value="{{ old('further_kg') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="frozen_kg">Frozen (KG)</label>
                                        <input type="number" step="0.01" min="0" name="frozen_kg"
                                            id="frozen_kg" class="form-control" placeholder="0,00"
                                            value="{{ old('frozen_kg') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label for="rm">RM (KG)</label>
                            <input type="number" step="0.01" min="0" name="rm" id="rm"
                                class="form-control" placeholder="Masukkan berat RM" value="{{ old('rm') }}">
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h4><span class="step-badge">3</span> Total Produksi dan Loss</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total_bag">Total Bag</label>
                                    <input type="number" min="0" step="1" name="total_bag" id="total_bag"
                                        class="form-control" placeholder="0" value="{{ old('total_bag') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total_kg">Total KG</label>
                                    <input type="number" step="0.01" min="0" name="total_kg" id="total_kg"
                                        class="form-control" placeholder="0,00" value="{{ old('total_kg') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="loss_kg">Loss (KG)</label>
                                    <input type="number" step="0.01" min="0" name="loss_kg" id="loss_kg"
                                        class="form-control" placeholder="0,00" value="{{ old('loss_kg') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="loss_persen">Loss (%)</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" name="loss_persen"
                                            id="loss_persen" class="form-control" placeholder="0,00"
                                            value="{{ old('loss_persen') }}">
                                        <div class="input-group-append"><span class="input-group-text">%</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pickup_kg">Pickup (KG)</label>
                                    <input type="number" step="0.01" min="0" name="pickup_kg" id="pickup_kg"
                                        class="form-control" placeholder="0,00" value="{{ old('pickup_kg') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pickup_persen">Pickup (%)</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" name="pickup_persen"
                                            id="pickup_persen" class="form-control" placeholder="0,00"
                                            value="{{ old('pickup_persen') }}">
                                        <div class="input-group-append"><span class="input-group-text">%</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h4><i class="fas fa-calculator mr-1"></i> Perhitungan Yield</h4>
                    </div>
                    <div class="card-body">
                        <div class="section-info mb-3">
                            <i class="fas fa-info-circle"></i>
                            Pilih bahan baku sesuai No. PO yang sudah dipilih. Jumlah KG akan otomatis digunakan sebagai
                            pembagi Yield.
                        </div>

                        <div class="form-group">
                            <label for="yield_bahan_baku_id">Material Bahan Baku <span
                                    class="text-danger">*</span></label>
                            <select id="yield_bahan_baku_id" class="form-control select2" disabled>
                                <option value="">Pilih tanggal, shift, dan No. PO terlebih dahulu</option>
                            </select>
                            @error('bahan_baku_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Daftar ini mengikuti No. PO bahan baku yang dipilih pada
                                bagian Informasi Produksi.</small>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-0">
                                    <label for="preview_jumlah_kg">Jumlah KG Bahan Baku</label>
                                    <input type="text" id="preview_jumlah_kg" class="form-control" value="0,00"
                                        readonly>
                                    <small class="form-text text-muted">Diambil otomatis dari bahan baku terpilih.</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-0">
                                    <label for="preview_hasil_finish_good">Hasil Finish Good (KG)</label>
                                    <input type="text" id="preview_hasil_finish_good" class="form-control"
                                        value="0,00" readonly>
                                    <small class="form-text text-muted">Mengikuti nilai Total KG di atas.</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-0">
                                    <label for="preview_yield_persen">Yield (%)</label>
                                    <div class="input-group">
                                        <input type="text" id="preview_yield_persen" class="form-control"
                                            value="0,00" readonly>
                                        <div class="input-group-append"><span class="input-group-text">%</span></div>
                                    </div>
                                    <small class="form-text text-muted">(Hasil Finish Good ÷ Jumlah KG) × 100.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h4><span class="step-badge">4</span> Pengecekan Suhu</h4>
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
                                        class="form-control" value="{{ old('pengecekan_suhu_jam') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pengecekan_suhu_suhu">Suhu (°C)</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="pengecekan_suhu_suhu"
                                            id="pengecekan_suhu_suhu" class="form-control" placeholder="Masukkan suhu"
                                            value="{{ old('pengecekan_suhu_suhu') }}">
                                        <div class="input-group-append"><span class="input-group-text">°C</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h4><span class="step-badge">5</span> Packaging</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="packaging_nama_kemasan">Nama Kemasan</label>
                                    <input type="text" name="packaging_nama_kemasan" id="packaging_nama_kemasan"
                                        class="form-control" placeholder="Masukkan nama kemasan"
                                        value="{{ old('packaging_nama_kemasan') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="packaging_jumlah_kg">Jumlah Packaging (KG)</label>
                                    <input type="number" step="0.01" min="0" name="packaging_jumlah_kg"
                                        id="packaging_jumlah_kg" class="form-control" placeholder="0,00"
                                        value="{{ old('packaging_jumlah_kg') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap mt-4 mb-4">
                    <a href="{{ route('operator.hasil-produksi.index') }}" class="btn btn-ghost mb-2">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary mb-2">
                        <i class="fas fa-save mr-1"></i> Simpan Hasil Produksi
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

            $('#shift_select, #no_po_bahan_baku_select, #material_id, #cost_center_name, #yield_bahan_baku_id')
                .select2({
                    width: '100%',
                    placeholder: 'Pilih opsi',
                    allowClear: true
                });

            let shiftRequest = null;
            let poListRequest = null;
            let productsRequest = null;
            let bahanBakuRequest = null;
            let currentYieldMaterials = [];

            function formatNumber(value) {
                return Number(value || 0).toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function resetPoList(message = 'Pilih shift terlebih dahulu') {
                $('#no_po_bahan_baku_select')
                    .empty()
                    .append(new Option(message, ''))
                    .prop('disabled', true)
                    .val('')
                    .trigger('change.select2');
            }

            function resetMaterials(message = 'Memuat seluruh material...') {
                $('#material_id')
                    .empty()
                    .append(new Option(message, ''))
                    .prop('disabled', false)
                    .val('')
                    .trigger('change.select2');
            }

            function resetYieldMaterials(message = 'Pilih tanggal, shift, dan No. PO terlebih dahulu') {
                currentYieldMaterials = [];

                $('#yield_bahan_baku_id')
                    .empty()
                    .append(new Option(message, ''))
                    .prop('disabled', true)
                    .val('')
                    .trigger('change.select2');

                $('#jumlah_kg_bahan_baku').val('');
                $('#bahan_baku_id').val('');
                recalculatePreview();
            }

            function resetSummary() {
                $('#info_jumlah_produk').text('0');
                $('#info_total_kg').text('0,00 KG');
                $('#production-summary').hide();
            }

            function recalculatePreview() {
                const hasilFinishGood = parseFloat($('#total_kg').val()) || 0;
                const jumlahKg = parseFloat($('#jumlah_kg_bahan_baku').val()) || 0;
                const yieldPersen = jumlahKg > 0 ? (hasilFinishGood / jumlahKg) * 100 : 0;

                $('#preview_jumlah_kg').val(formatNumber(jumlahKg));
                $('#preview_hasil_finish_good').val(formatNumber(hasilFinishGood));
                $('#preview_yield_persen').val(formatNumber(yieldPersen));
            }

            // Endpoint 1: seluruh Material dari tabel materials, tanpa filter batch atau No. PO.
            function loadAllMaterials() {
                const materialSelect = $('#material_id');

                materialSelect
                    .empty()
                    .append(new Option('Memuat seluruh material...', ''))
                    .prop('disabled', true)
                    .trigger('change.select2');

                if (productsRequest) {
                    productsRequest.abort();
                }

                productsRequest = $.ajax({
                    url: '{{ route('operator.hasil-produksi.get-products') }}',
                    type: 'GET',
                    dataType: 'json',
                    success: function(materials) {
                        console.log('Response getProducts:', materials);

                        materialSelect.empty().append(new Option('Pilih Material', ''));

                        if (!Array.isArray(materials)) {
                            materialSelect
                                .empty()
                                .append(new Option('Response getProducts bukan array JSON', ''))
                                .prop('disabled', false)
                                .trigger('change.select2');

                            return;
                        }

                        const selectedOldMaterial = @json(old('material_id'));

                        materials.forEach(function(item) {
                            const option = new Option(
                                `${item.material_code ?? '-'} - ${item.material_description ?? '-'}`,
                                item.id
                            );

                            if (String(item.id) === String(selectedOldMaterial)) {
                                option.selected = true;
                            }

                            materialSelect.append(option);
                        });

                        if (materials.length === 0) {
                            materialSelect
                                .empty()
                                .append(new Option('Data material kosong', ''));
                        }

                        materialSelect
                            .prop('disabled', false)
                            .trigger('change.select2');
                    },
                    error: function(xhr, status, error) {
                        if (status === 'abort') return;

                        console.error('getProducts gagal:', xhr.status, xhr.responseText, error);

                        materialSelect
                            .empty()
                            .append(new Option(
                                `Gagal memuat material (${xhr.status}). Periksa endpoint.`,
                                ''
                            ))
                            .prop('disabled', false)
                            .trigger('change.select2');
                    }
                });
            }

            // Endpoint 2: BahanBaku berdasarkan production_batch_id dan No. PO terpilih.
            function loadBahanBakuByPo(batchId, noPo) {
                if (bahanBakuRequest) {
                    bahanBakuRequest.abort();
                }

                resetSummary();
                resetYieldMaterials('Memuat daftar bahan baku...');

                const selectedBatchId = String(batchId);
                const selectedNoPo = String(noPo);

                bahanBakuRequest = $.ajax({
                    url: '{{ route('operator.hasil-produksi.get-bahan-baku-by-po') }}',
                    type: 'GET',
                    data: {
                        production_batch_id: batchId,
                        no_po: noPo
                    },
                    success: function(bahanBakus) {
                        const activePo = $('#no_po_bahan_baku_select').val();
                        const normalizedActivePo = activePo === EMPTY_PO_VALUE ? '' : activePo;

                        if (
                            String($('#production_batch_id').val()) !== selectedBatchId ||
                            String(normalizedActivePo) !== selectedNoPo
                        ) {
                            return;
                        }

                        if (!Array.isArray(bahanBakus)) {
                            resetYieldMaterials('Format data bahan baku tidak sesuai.');
                            return;
                        }

                        currentYieldMaterials = bahanBakus;

                        $('#info_jumlah_produk').text(bahanBakus.length);
                        const totalKg = bahanBakus.reduce(function(total, item) {
                            return total + Number(item.jumlah_kg || 0);
                        }, 0);

                        $('#info_total_kg').text(formatNumber(totalKg) + ' KG');
                        $('#production-summary').show();

                        const yieldSelect = $('#yield_bahan_baku_id');
                        const selectedOldBahanBaku = @json(old('bahan_baku_id'));

                        yieldSelect.empty().append(new Option('Pilih Material Bahan Baku', ''));

                        bahanBakus.forEach(function(item) {
                            const material = item.material || {};
                            const kode = material.material_code || '-';
                            const nama = material.material_description || '-';
                            const option = new Option(
                                `${kode} - ${nama} (${formatNumber(item.jumlah_kg)} KG)`,
                                item.id
                            );

                            if (String(item.id) === String(selectedOldBahanBaku)) {
                                option.selected = true;
                            }

                            yieldSelect.append(option);
                        });

                        if (bahanBakus.length > 0) {
                            yieldSelect.prop('disabled', false);
                        } else {
                            yieldSelect.empty().append(new Option(
                                'Tidak ada bahan baku untuk No. PO ini', ''));
                            yieldSelect.prop('disabled', true);
                        }

                        yieldSelect.trigger('change.select2');

                        if (selectedOldBahanBaku && bahanBakus.some(function(item) {
                                return String(item.id) === String(selectedOldBahanBaku);
                            })) {
                            yieldSelect.val(String(selectedOldBahanBaku)).trigger('change');
                        } else {
                            yieldSelect.val('').trigger('change.select2');
                            $('#jumlah_kg_bahan_baku').val('');
                            $('#bahan_baku_id').val('');
                            recalculatePreview();
                        }
                    },
                    error: function(xhr, status) {
                        if (status === 'abort') return;
                        resetSummary();
                        resetYieldMaterials('Gagal memuat material bahan baku.');
                    }
                });
            }

            $('#yield_bahan_baku_id').on('change', function() {
                const selectedId = $(this).val();

                if (selectedId === null || selectedId === '') {
                    $('#jumlah_kg_bahan_baku').val('');
                    $('#bahan_baku_id').val('');
                    recalculatePreview();
                    return;
                }

                const selectedMaterial = currentYieldMaterials.find(function(item) {
                    return String(item.id) === String(selectedId);
                });

                if (!selectedMaterial) {
                    $('#jumlah_kg_bahan_baku').val('');
                    $('#bahan_baku_id').val('');
                    recalculatePreview();
                    return;
                }

                $('#jumlah_kg_bahan_baku').val(Number(selectedMaterial.jumlah_kg || 0));
                $('#bahan_baku_id').val(selectedMaterial.id);
                recalculatePreview();
            });

            $('#total_kg').on('input', recalculatePreview);

            // Material master dimuat sendiri dan tidak menunggu pemilihan batch atau No. PO.
            loadAllMaterials();

            $('#tanggal_produksi').on('change', function() {
                const tanggal = $(this).val();
                const shiftSelect = $('#shift_select');

                if (shiftRequest) shiftRequest.abort();
                if (poListRequest) poListRequest.abort();
                if (bahanBakuRequest) bahanBakuRequest.abort();

                $('#production_batch_id').val('');
                resetSummary();
                resetPoList();
                resetYieldMaterials();
                recalculatePreview();

                shiftSelect.empty()
                    .append(new Option('Memuat shift...', ''))
                    .prop('disabled', true)
                    .val('')
                    .trigger('change.select2');

                $('#batch_message').text('Memuat data production batch...');

                if (!tanggal) {
                    shiftSelect.empty()
                        .append(new Option('Pilih tanggal terlebih dahulu', ''))
                        .prop('disabled', true)
                        .trigger('change.select2');

                    $('#batch_message').text('Pilih tanggal untuk menampilkan shift yang tersedia.');
                    return;
                }

                shiftRequest = $.ajax({
                    url: `/production-batch/by-date/${encodeURIComponent(tanggal)}`,
                    type: 'GET',
                    success: function(response) {
                        if ($('#tanggal_produksi').val() !== tanggal) return;

                        shiftSelect.empty().append(new Option('Pilih Shift', ''));

                        if (!Array.isArray(response) || response.length === 0) {
                            shiftSelect
                                .append(new Option('Tidak ada batch pada tanggal ini', ''))
                                .prop('disabled', true)
                                .trigger('change.select2');

                            $('#batch_message').text(
                                'Tidak ditemukan production batch pada tanggal tersebut.');
                            return;
                        }

                        response.forEach(function(batch) {
                            const option = new Option(
                                `Shift ${batch.shift} - Conveyor ${batch.conveyor}`,
                                batch
                                .shift);
                            $(option).attr('data-batch-id', batch.id);
                            shiftSelect.append(option);
                        });

                        shiftSelect.prop('disabled', false).trigger('change.select2');
                        $('#batch_message').text(
                            'Pilih shift untuk menentukan production batch.');
                    },
                    error: function(xhr, status) {
                        if (status === 'abort') return;

                        shiftSelect.empty()
                            .append(new Option('Gagal memuat shift', ''))
                            .prop('disabled', true)
                            .trigger('change.select2');

                        $('#batch_message').text(
                            'Terjadi kesalahan saat mengambil data production batch.');
                    }
                });
            });

            $('#shift_select').on('change', function() {
                const selected = $(this).find(':selected');
                const batchId = selected.attr('data-batch-id');

                if (poListRequest) poListRequest.abort();
                if (bahanBakuRequest) bahanBakuRequest.abort();

                $('#production_batch_id').val(batchId || '');
                resetSummary();
                resetYieldMaterials();
                recalculatePreview();

                if (!batchId) {
                    resetPoList();
                    return;
                }

                $('#no_po_bahan_baku_select')
                    .empty()
                    .append(new Option('Memuat No. PO...', ''))
                    .prop('disabled', true)
                    .val('')
                    .trigger('change.select2');

                poListRequest = $.ajax({
                    url: '{{ route('operator.hasil-produksi.get-po-list') }}',
                    type: 'GET',
                    data: {
                        production_batch_id: batchId
                    },
                    success: function(groups) {
                        if (String($('#production_batch_id').val()) !== String(batchId)) return;

                        const select = $('#no_po_bahan_baku_select');
                        select.empty().append(new Option('Pilih No. PO', ''));

                        if (!groups || groups.length === 0) {
                            select
                                .append(new Option('Tidak ada bahan baku pada batch ini', ''))
                                .prop('disabled', true)
                                .trigger('change.select2');
                            return;
                        }

                        groups.forEach(function(group) {
                            const isEmptyPo = group.no_po === null || group.no_po ===
                                '';
                            const value = isEmptyPo ? EMPTY_PO_VALUE : group.no_po;
                            const label = isEmptyPo ? '(Belum diisi)' : group.no_po;

                            select.append(new Option(
                                `${label} — ${group.jumlah_produk} material, ${formatNumber(group.total_kg)} KG`,
                                value
                            ));
                        });

                        select.prop('disabled', false).trigger('change.select2');
                    },
                    error: function(xhr, status) {
                        if (status === 'abort') return;

                        $('#no_po_bahan_baku_select')
                            .empty()
                            .append(new Option('Gagal memuat No. PO', ''))
                            .prop('disabled', true)
                            .trigger('change.select2');
                    }
                });
            });

            $('#no_po_bahan_baku_select').on('change', function() {
                const selectedPo = $(this).val();
                const batchId = $('#production_batch_id').val();

                if (bahanBakuRequest) bahanBakuRequest.abort();

                resetSummary();
                resetYieldMaterials();
                recalculatePreview();

                if (!batchId || selectedPo === null || selectedPo === '') return;

                const noPo = selectedPo === EMPTY_PO_VALUE ? '' : selectedPo;
                loadBahanBakuByPo(batchId, noPo);
            });

            $('#hasilProduksiForm').on('submit', function(event) {
                if (!$('#production_batch_id').val()) {
                    event.preventDefault();
                    alert('Silakan pilih tanggal produksi dan shift terlebih dahulu.');
                    return;
                }

                const selectedPo = $('#no_po_bahan_baku_select').val();

                if (selectedPo === null || selectedPo === '') {
                    event.preventDefault();
                    alert('Silakan pilih No. PO bahan baku terlebih dahulu.');
                    return;
                }

                if (!$('#material_id').val()) {
                    event.preventDefault();
                    alert('Silakan pilih Material pada Detail Hasil Produksi.');
                    return;
                }

                if (!$('#bahan_baku_id').val()) {
                    event.preventDefault();
                    alert('Silakan pilih material bahan baku pada Perhitungan Yield terlebih dahulu.');
                }
            });

            recalculatePreview();
        });
    </script>
@endpush
