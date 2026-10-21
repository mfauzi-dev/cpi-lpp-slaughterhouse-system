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
                            <div class="col-md-6">
                                <div class="form-group">
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
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="shift_select">
                                        Shift <span class="text-danger">*</span>
                                    </label>
                                    <select id="shift_select"
                                        class="form-control select2 @error('production_batch_id') is-invalid @enderror"
                                        disabled required>
                                        <option value="">Pilih tanggal terlebih dahulu</option>
                                    </select>
                                    <input type="hidden" name="production_batch_id" id="production_batch_id"
                                        value="{{ old('production_batch_id') }}">
                                    @error('production_batch_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted" id="batch_message">
                                        Pilih tanggal untuk menampilkan shift yang tersedia.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="material_id">
                                Nama Product <span class="text-danger">*</span>
                            </label>
                            <select name="material_id" id="material_id"
                                class="form-control select2 @error('material_id') is-invalid @enderror" required>
                                <option value="">Pilih Product</option>
                                @foreach ($materials as $material)
                                    <option value="{{ $material->id }}"
                                        {{ (string) old('material_id') === (string) $material->id ? 'selected' : '' }}>
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
            const tanggalInput = $('#tanggal_produksi');
            const shiftSelect = $('#shift_select');
            const batchIdInput = $('#production_batch_id');
            const batchMessage = $('#batch_message');

            let batchRequest = null;
            let productRequest = null;

            $('.select2').select2({
                width: '100%',
                placeholder: 'Pilih opsi',
                allowClear: true
            });

            function resetProductionInfo() {
                $('#batch_tanggal_transfer').text('-');
                $('#batch_tanggal_proses').text('-');
                $('#batch_absen_worker').text('0 orang');
                $('#info_jumlah_produk').text('0 jenis');
                $('#info_total_kg').text('0,00 KG');
                $('#production-summary').hide();
            }

            function formatDate(date) {
                if (!date) {
                    return '-';
                }

                const parts = String(date).split('-');

                if (parts.length !== 3) {
                    return date;
                }

                return `${parts[2]}-${parts[1]}-${parts[0]}`;
            }

            function updateProductionInfo() {
                const batchId = shiftSelect.val();
                const selected = shiftSelect.find(':selected');

                batchIdInput.val(batchId || '');
                resetProductionInfo();

                if (!batchId) {
                    return;
                }

                $('#batch_tanggal_transfer').text(
                    formatDate(selected.attr('data-tanggal-transfer'))
                );

                $('#batch_tanggal_proses').text(
                    formatDate(selected.attr('data-tanggal-proses'))
                );

                $('#batch_absen_worker').text(
                    (selected.attr('data-absen-worker') || '0') + ' orang'
                );

                $('#production-summary').show();
                $('#info_jumlah_produk').text('Memuat...');
                $('#info_total_kg').text('Memuat...');

                if (productRequest) {
                    productRequest.abort();
                }

                productRequest = $.ajax({
                    url: '{{ route('operator.hasil-produksi.get-products') }}',
                    type: 'GET',
                    data: {
                        production_batch_id: batchId
                    },
                    success: function(response) {
                        if (String(batchIdInput.val()) !== String(batchId)) {
                            return;
                        }

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
                    error: function(xhr, status) {
                        if (status !== 'abort') {
                            $('#info_jumlah_produk').text('-');
                            $('#info_total_kg').text('-');
                        }
                    },
                    complete: function() {
                        productRequest = null;
                    }
                });
            }

            function resetShift(message) {
                shiftSelect.empty().append(new Option(message, '', true, true));
                shiftSelect.prop('disabled', true);
                batchIdInput.val('');
                shiftSelect.trigger('change.select2');
                resetProductionInfo();
            }

            function loadShift(tanggal, selectedBatchId = '') {
                if (batchRequest) {
                    batchRequest.abort();
                    batchRequest = null;
                }

                if (productRequest) {
                    productRequest.abort();
                    productRequest = null;
                }

                resetShift('Memuat shift...');
                batchMessage.text('Mengambil data shift berdasarkan tanggal produksi.');

                if (!tanggal) {
                    resetShift('Pilih tanggal terlebih dahulu');
                    batchMessage.text('Pilih tanggal untuk menampilkan shift yang tersedia.');
                    return;
                }

                batchRequest = $.ajax({
                    url: '/production-batch/by-date/' + encodeURIComponent(tanggal),
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        shiftSelect.empty();

                        if (!Array.isArray(response) || response.length === 0) {
                            resetShift('Tidak ada data shift');
                            batchMessage.text(
                                'Tidak ditemukan data produksi pada tanggal tersebut.'
                            );
                            return;
                        }

                        shiftSelect.append(new Option('Pilih shift', '', true, true));

                        response.forEach(function(batch) {
                            const option = new Option(
                                'Shift ' + batch.shift,
                                batch.id
                            );

                            $(option).attr('data-tanggal-transfer', batch.tanggal_transfer ||
                                '');
                            $(option).attr('data-tanggal-proses', batch.tanggal_proses || '');
                            $(option).attr('data-absen-worker', batch.absen_worker ?? 0);

                            shiftSelect.append(option);
                        });

                        shiftSelect.prop('disabled', false).trigger('change.select2');

                        if (selectedBatchId && response.some(function(batch) {
                                return String(batch.id) === String(selectedBatchId);
                            })) {
                            shiftSelect.val(String(selectedBatchId)).trigger('change');
                        } else {
                            shiftSelect.val('').trigger('change.select2');
                            batchIdInput.val('');
                            resetProductionInfo();
                        }

                        batchMessage.text('Pilih shift sesuai data produksi yang tersedia.');
                    },
                    error: function(xhr, status) {
                        if (status === 'abort') {
                            return;
                        }

                        resetShift('Gagal memuat shift');
                        batchMessage.text('Terjadi kesalahan saat mengambil data produksi.');
                        console.error('Gagal mengambil data production batch:', xhr.responseText);
                    },
                    complete: function() {
                        batchRequest = null;
                    }
                });
            }

            tanggalInput.on('change', function() {
                loadShift($(this).val());
            });

            shiftSelect.on('change', updateProductionInfo);

            const oldTanggal = @json(old('tanggal_produksi'));
            const oldBatchId = batchIdInput.val();

            if (oldTanggal) {
                loadShift(oldTanggal, oldBatchId);
            } else {
                resetShift('Pilih tanggal terlebih dahulu');
                batchMessage.text('Pilih tanggal untuk menampilkan shift yang tersedia.');
            }
        });
    </script>
@endpush
