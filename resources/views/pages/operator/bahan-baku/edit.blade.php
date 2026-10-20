@extends('layouts.master')

@section('title', 'Edit Produksi Bahan Baku')

@section('content')
    <div class="page-section">
        <div class="section-header">
            <h1>Produksi Bahan Baku</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('operator.bahan-baku.index') }}">Produksi Bahan Baku</a>
                </div>
                <div class="breadcrumb-item active">Edit</div>
            </div>
        </div>

        <div class="section-lead">
            Perbarui data bahan baku berdasarkan No. PO yang dipilih.
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

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('operator.bahan-baku.update', $bahanBaku->id) }}" method="POST" id="productionForm">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="filter-label">Tanggal Produksi</label>
                                    <input type="date" class="form-control"
                                        value="{{ old('tanggal', $production->tanggal ? \Carbon\Carbon::parse($production->tanggal)->format('Y-m-d') : '') }}"
                                        disabled>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="filter-label">Shift</label>
                                    <input type="text" class="form-control" value="{{ $production->shift ?? '-' }}"
                                        disabled>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="filter-label">No. PO</label>
                                    <input type="text" class="form-control" value="{{ $bahanBaku->no_po ?? '-' }}"
                                        disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Tanggal Transfer</div>
                                    <div class="info-value">
                                        {{ $production->tanggal_transfer ? \Carbon\Carbon::parse($production->tanggal_transfer)->format('d-m-Y') : '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Tanggal Proses</div>
                                    <div class="info-value">
                                        {{ $production->tanggal_proses ? \Carbon\Carbon::parse($production->tanggal_proses)->format('d-m-Y') : '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <div class="info-label">Jumlah Pekerja</div>
                                    <div class="info-value">
                                        {{ $production->absen_worker ?? 0 }} orang
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-accent mb-4" id="product-card">
                    <div class="card-header">
                        <h4>
                            <span class="step-badge">2</span>
                            Data Bahan Baku
                        </h4>
                    </div>

                    <div class="card-body p-0">
                        @php
                            $formProducts = old('products');

                            if (!is_array($formProducts)) {
                                $formProducts = $products
                                    ->map(function ($item) {
                                        return [
                                            'material_id' => $item->material_id,
                                            'jumlah_bag' => $item->jumlah_bag,
                                            'jumlah_kg' => $item->jumlah_kg,
                                        ];
                                    })
                                    ->values()
                                    ->toArray();
                            }

                            if (empty($formProducts)) {
                                $formProducts = [
                                    [
                                        'material_id' => '',
                                        'jumlah_bag' => '',
                                        'jumlah_kg' => '',
                                    ],
                                ];
                            }
                        @endphp

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
                                    @foreach ($formProducts as $index => $item)
                                        <tr data-row-index="{{ $index }}">
                                            <td class="text-center align-middle nomor">
                                                {{ $index + 1 }}
                                            </td>

                                            <td>
                                                <select name="products[{{ $index }}][material_id]"
                                                    class="form-control material-select" required>
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
                                                    placeholder="Jumlah bag" value="{{ $item['jumlah_bag'] ?? '' }}"
                                                    required>
                                            </td>

                                            <td>
                                                <input type="number" name="products[{{ $index }}][jumlah_kg]"
                                                    class="form-control jumlah-kg" min="0" step="0.01"
                                                    placeholder="Berat (KG)" value="{{ $item['jumlah_kg'] ?? '' }}"
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
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <button type="button" id="btnAddRow" class="btn btn-add mb-2 mb-md-0">
                                <i class="fas fa-plus mr-1"></i>
                                Tambah Baris
                            </button>

                            <div class="action-buttons">
                                <a href="{{ route('operator.bahan-baku.detail', $production->id) }}"
                                    class="btn btn-ghost">
                                    <i class="fas fa-arrow-left mr-1"></i>
                                    Kembali
                                </a>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i>
                                    Simpan Perubahan
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
            let rowIndex = {{ count($formProducts) }};

            function renumberRows() {
                $('#product-table-body tr').each(function(index) {
                    $(this).find('.nomor').text(index + 1);
                });
            }

            function buildMaterialOptions() {
                let options = '<option value="">Pilih Material</option>';

                @foreach ($materials as $material)
                    options += `
                        <option value="{{ $material->id }}">
                            {{ addslashes($material->material_code) }} -
                            {{ addslashes($material->material_description) }}
                        </option>
                    `;
                @endforeach

                return options;
            }

            $('#btnAddRow').on('click', function() {
                const html = `
                    <tr data-row-index="${rowIndex}">
                        <td class="text-center align-middle nomor"></td>
                        <td>
                            <select name="products[${rowIndex}][material_id]"
                                class="form-control material-select" required>
                                ${buildMaterialOptions()}
                            </select>
                        </td>
                        <td>
                            <input type="number"
                                name="products[${rowIndex}][jumlah_bag]"
                                class="form-control jumlah-bag"
                                min="0" step="1"
                                placeholder="Jumlah bag" required>
                        </td>
                        <td>
                            <input type="number"
                                name="products[${rowIndex}][jumlah_kg]"
                                class="form-control jumlah-kg"
                                min="0" step="0.01"
                                placeholder="Berat (KG)" required>
                        </td>
                        <td class="text-center align-middle">
                            <button type="button"
                                class="btn btn-outline-danger btn-sm removeRow"
                                title="Hapus Baris">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;

                $('#product-table-body').append(html);
                rowIndex++;
                renumberRows();
            });

            $(document).on('click', '.removeRow', function() {
                const rows = $('#product-table-body tr');

                if (rows.length <= 1) {
                    alert('Minimal harus ada 1 baris.');
                    return;
                }

                $(this).closest('tr').remove();
                renumberRows();
            });

            $('#productionForm').on('submit', function(event) {
                const selectedMaterials = [];
                let valid = true;

                $('#product-table-body tr').each(function() {
                    const materialId = $(this).find('.material-select').val();
                    const jumlahBag = $(this).find('.jumlah-bag').val();
                    const jumlahKg = $(this).find('.jumlah-kg').val();

                    if (!materialId || jumlahBag === '' || jumlahKg === '') {
                        valid = false;
                        return false;
                    }

                    if (selectedMaterials.includes(materialId)) {
                        alert('Material yang sama tidak boleh dipilih lebih dari satu kali.');
                        valid = false;
                        return false;
                    }

                    selectedMaterials.push(materialId);
                });

                if (!valid) {
                    event.preventDefault();

                    if (selectedMaterials.length === $('#product-table-body tr').length) {
                        alert('Lengkapi semua data bahan baku terlebih dahulu.');
                    }
                }
            });

            renumberRows();
        });
    </script>
@endpush
