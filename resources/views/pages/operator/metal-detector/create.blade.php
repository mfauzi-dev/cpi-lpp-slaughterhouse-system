```blade
@extends('layouts.master')

@section('title', 'Tambah Metal Detector')

@section('content')
    <div class="page-section">
        <div class="section-header">
            <h1>Tambah Metal Detector</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('operator.metal-detector.index') }}">Metal Detector</a>
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

            <form action="{{ route('operator.metal-detector.store') }}" method="POST">
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

                        <div class="form-group mt-3">
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

                        <div class="form-group mb-0">
                            <label for="no_program">No Program</label>
                            <input type="text" name="no_program" id="no_program"
                                class="form-control @error('no_program') is-invalid @enderror"
                                value="{{ old('no_program') }}" placeholder="Masukkan nomor program">
                            @error('no_program')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card card-accent mb-4">
                    <div class="card-header">
                        <h4>
                            <span class="step-badge">2</span>
                            Pemeriksaan Metal Detector
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Jenis Pemeriksaan</th>
                                        <th class="text-center">Depan Produk</th>
                                        <th class="text-center">Tengah Produk</th>
                                        <th class="text-center">Belakang Produk</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>FE</th>
                                        @foreach (['fe_depan' => 'Depan', 'fe_tengah' => 'Tengah', 'fe_belakang' => 'Belakang'] as $field => $position)
                                            <td>
                                                <select name="{{ $field }}"
                                                    class="form-control @error($field) is-invalid @enderror">
                                                    <option value="">Pilih Hasil</option>
                                                    <option value="Terdeteksi"
                                                        {{ old($field) === 'Terdeteksi' ? 'selected' : '' }}>
                                                        Terdeteksi
                                                    </option>
                                                    <option value="Tidak terdeteksi"
                                                        {{ old($field) === 'Tidak terdeteksi' ? 'selected' : '' }}>
                                                        Tidak terdeteksi
                                                    </option>
                                                </select>
                                                @error($field)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                        @endforeach
                                    </tr>

                                    <tr>
                                        <th>Non FE</th>
                                        @foreach (['non_fe_depan' => 'Depan', 'non_fe_tengah' => 'Tengah', 'non_fe_belakang' => 'Belakang'] as $field => $position)
                                            <td>
                                                <select name="{{ $field }}"
                                                    class="form-control @error($field) is-invalid @enderror">
                                                    <option value="">Pilih Hasil</option>
                                                    <option value="Terdeteksi"
                                                        {{ old($field) === 'Terdeteksi' ? 'selected' : '' }}>
                                                        Terdeteksi
                                                    </option>
                                                    <option value="Tidak terdeteksi"
                                                        {{ old($field) === 'Tidak terdeteksi' ? 'selected' : '' }}>
                                                        Tidak terdeteksi
                                                    </option>
                                                </select>
                                                @error($field)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                        @endforeach
                                    </tr>

                                    <tr>
                                        <th>SUS 316</th>
                                        @foreach (['sus_316_depan' => 'Depan', 'sus_316_tengah' => 'Tengah', 'sus_316_belakang' => 'Belakang'] as $field => $position)
                                            <td>
                                                <select name="{{ $field }}"
                                                    class="form-control @error($field) is-invalid @enderror">
                                                    <option value="">Pilih Hasil</option>
                                                    <option value="Terdeteksi"
                                                        {{ old($field) === 'Terdeteksi' ? 'selected' : '' }}>
                                                        Terdeteksi
                                                    </option>
                                                    <option value="Tidak terdeteksi"
                                                        {{ old($field) === 'Tidak terdeteksi' ? 'selected' : '' }}>
                                                        Tidak terdeteksi
                                                    </option>
                                                </select>
                                                @error($field)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h4>
                            <span class="step-badge">3</span>
                            Informasi IN / OUT dan Keterangan
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="in">IN</label>
                                    <input type="text" name="in" id="in"
                                        class="form-control @error('in') is-invalid @enderror"
                                        value="{{ old('in') }}" placeholder="Masukkan informasi IN">
                                    @error('in')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="out">OUT</label>
                                    <input type="text" name="out" id="out"
                                        class="form-control @error('out') is-invalid @enderror"
                                        value="{{ old('out') }}" placeholder="Masukkan informasi OUT">
                                    @error('out')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="3"
                                class="form-control @error('keterangan') is-invalid @enderror" placeholder="Masukkan keterangan">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap mt-4 mb-4">
                    <a href="{{ route('operator.metal-detector.index') }}" class="btn btn-ghost mb-2">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary mb-2">
                        <i class="fas fa-save mr-1"></i>
                        Simpan Metal Detector
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
        });
    </script>
@endpush
```
