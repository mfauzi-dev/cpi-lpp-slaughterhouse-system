@extends('layouts.master')

@section('title', 'Detail Produksi Bahan Baku')

@section('content')
    <div class="page-section">
        <div class="section-header">
            <h1>Produksi Bahan Baku</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('operator.bahan-baku.index') }}">Produksi Bahan Baku</a>
                </div>
                <div class="breadcrumb-item active">Detail</div>
            </div>
        </div>

        <div class="section-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
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

            @php
                $selectedPo = request('no_po');
                $selectedItems =
                    $selectedPo !== null
                        ? $production->bahanBakus->where('no_po', $selectedPo)
                        : $production->bahanBakus;
            @endphp

            <div class="card mb-4">
                <div class="card-header">
                    <h4>
                        <span class="step-badge">1</span>
                        Informasi Produksi
                    </h4>
                </div>

                <div class="card-body">
                    <div class="batch-highlight">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="batch-label">Tanggal Produksi</div>
                                <div class="batch-number">
                                    {{ $production->tanggal ? \Carbon\Carbon::parse($production->tanggal)->format('d M Y') : '-' }}
                                </div>
                                <div class="batch-product">
                                    Shift {{ $production->shift ?? '-' }} - Conveyor {{ $production->conveyor }}
                                </div>
                            </div>

                            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                <div class="batch-label">No. PO</div>
                                <div class="batch-number batch-date">
                                    {{ $selectedPo !== null ? ($selectedPo !== '' ? $selectedPo : '(Belum diisi)') : 'Semua PO' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">Tanggal Produksi</div>
                                <div class="info-value">
                                    {{ $production->tanggal ? \Carbon\Carbon::parse($production->tanggal)->format('d M Y') : '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">Shift</div>
                                <div class="info-value">{{ $production->shift ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="info-box">
                                <div class="info-label">Jumlah Pekerja</div>
                                <div class="info-value">{{ $production->absen_worker ?? 0 }} orang</div>
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
                                <div class="info-label">Jumlah Baris Material</div>
                                <div class="info-value">{{ $selectedItems->count() }} baris</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-weight-hanging"></i>
                                </div>
                                <div>
                                    <div class="stat-label">Total Berat {{ $selectedPo !== null ? 'PO' : 'Seluruh PO' }}
                                    </div>
                                    <div class="stat-value">
                                        {{ number_format($selectedItems->sum('jumlah_kg'), 2, ',', '.') }} KG
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
                        <span class="step-badge">2</span>
                        Data Bahan Baku
                    </h4>
                </div>

                <div class="card-body">
                    <form id="bulkDeleteForm" action="{{ route('operator.bahan-baku.bulk-destroy') }}" method="POST">
                        @csrf
                        <input type="hidden" name="production_batch_id" value="{{ $production->id }}">
                        <input type="hidden" name="no_po" value="{{ $selectedPo }}">
                    </form>

                    @forelse ($poGroups as $poNumber => $poItems)
                        <div class="po-section mb-4">
                            <div class="po-heading d-flex justify-content-between align-items-center flex-wrap">
                                <div>
                                    <h5 class="font-weight-bold mb-1">
                                        No. PO: {{ $poNumber !== '' ? $poNumber : '(Belum diisi)' }}
                                    </h5>
                                    <small class="text-muted">
                                        {{ $poItems->count() }} material
                                        @if ($poItems->first()->cost_center_name)
                                            | Cost Center: {{ $poItems->first()->cost_center_name }}
                                        @endif
                                    </small>
                                </div>

                                <div class="mt-2 mt-md-0">
                                    <span class="font-weight-bold">
                                        Total: {{ number_format($poItems->sum('jumlah_kg'), 2, ',', '.') }} KG
                                    </span>
                                </div>
                            </div>

                            <div class="table-responsive mt-2">
                                <table class="table table-bordered table-wide mb-0">
                                    <thead>
                                        <tr>
                                            <th width="50" class="text-center">
                                                <input type="checkbox" class="check-po">
                                            </th>
                                            <th width="60" class="text-center">No</th>
                                            <th>Nama Material</th>
                                            <th>Jumlah Bag</th>
                                            <th>Berat (KG)</th>
                                            <th width="100" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($poItems as $index => $bahanBaku)
                                            <tr>
                                                <td class="text-center align-middle">
                                                    <input type="checkbox" name="ids[]" value="{{ $bahanBaku->id }}"
                                                        class="detail-checkbox" form="bulkDeleteForm">
                                                </td>

                                                <td class="text-center font-weight-bold">{{ $index + 1 }}</td>

                                                <td>
                                                    <div class="font-weight-bold">
                                                        {{ $bahanBaku->material->material_description ?? '-' }}
                                                    </div>

                                                    @if ($bahanBaku->material && $bahanBaku->material->material_code)
                                                        <small class="text-muted">
                                                            {{ $bahanBaku->material->material_code }}
                                                        </small>
                                                    @endif
                                                </td>

                                                <td>
                                                    {{ number_format($bahanBaku->jumlah_bag ?? 0, 0, ',', '.') }}
                                                </td>

                                                <td>
                                                    <strong>
                                                        {{ number_format($bahanBaku->jumlah_kg ?? 0, 2, ',', '.') }}
                                                    </strong>
                                                    KG
                                                </td>

                                                <td class="text-center">
                                                    <form
                                                        action="{{ route('operator.bahan-baku.destroy', $bahanBaku->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus material ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                            title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-right font-weight-bold">Total Berat PO</td>
                                            <td colspan="2" class="font-weight-bold">
                                                {{ number_format($poItems->sum('jumlah_kg'), 2, ',', '.') }} KG
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-box-open d-block"></i>
                            <div>Belum ada data bahan baku.</div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="card card-final">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <div class="info-label mb-1">Total Berat
                                {{ $selectedPo !== null ? 'PO' : 'Seluruh Bahan Baku' }}</div>
                            <div class="stat-value">
                                {{ number_format($selectedItems->sum('jumlah_kg'), 2, ',', '.') }} KG
                            </div>
                        </div>

                        <div class="d-flex align-items-center flex-wrap mt-3 mt-md-0">
                            <a href="{{ route('operator.bahan-baku.index') }}" class="btn btn-ghost mr-2 mb-2">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Kembali
                            </a>

                            @if ($selectedItems->isNotEmpty())
                                <a href="{{ route('operator.bahan-baku.edit', $selectedItems->first()->id) }}"
                                    class="btn btn-primary mr-2 mb-2">
                                    <i class="fas fa-edit mr-1"></i>
                                    Edit PO
                                </a>
                            @endif

                            <button type="submit" form="bulkDeleteForm" id="btnBulkDelete" class="btn btn-danger mb-2"
                                disabled>
                                <i class="fas fa-trash mr-1"></i>
                                Hapus Terpilih
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .po-section {
            border: 1px solid #e3e8ef;
            border-radius: 6px;
            overflow: hidden;
        }

        .po-heading {
            padding: 16px 20px;
            background: #f8f9fc;
            border-bottom: 1px solid #e3e8ef;
        }

        .po-heading h5 {
            font-size: 15px;
        }

        .po-section .table {
            margin-bottom: 0;
        }

        .po-section .table thead th {
            vertical-align: middle;
            white-space: nowrap;
        }

        @media (max-width: 767px) {
            .po-heading {
                padding: 12px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkAllGroups = document.querySelectorAll('.check-po');
            const checkboxes = document.querySelectorAll('.detail-checkbox');
            const btnBulkDelete = document.getElementById('btnBulkDelete');
            const bulkDeleteForm = document.getElementById('bulkDeleteForm');

            function updateButton() {
                const checked = document.querySelectorAll('.detail-checkbox:checked');
                btnBulkDelete.disabled = checked.length === 0;

                checkAllGroups.forEach(function(checkAll) {
                    const table = checkAll.closest('table');
                    const tableCheckboxes = table.querySelectorAll('.detail-checkbox');
                    const tableChecked = table.querySelectorAll('.detail-checkbox:checked');

                    checkAll.checked = tableCheckboxes.length > 0 &&
                        tableChecked.length === tableCheckboxes.length;

                    checkAll.indeterminate = tableChecked.length > 0 &&
                        tableChecked.length < tableCheckboxes.length;
                });
            }

            checkAllGroups.forEach(function(checkAll) {
                checkAll.addEventListener('change', function() {
                    const table = checkAll.closest('table');

                    table.querySelectorAll('.detail-checkbox').forEach(function(checkbox) {
                        checkbox.checked = checkAll.checked;
                    });

                    updateButton();
                });
            });

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', updateButton);
            });

            bulkDeleteForm.addEventListener('submit', function(event) {
                const checked = document.querySelectorAll('.detail-checkbox:checked');

                if (checked.length === 0) {
                    event.preventDefault();
                    alert('Pilih minimal satu material.');
                    return;
                }

                if (!confirm('Yakin ingin menghapus material yang dipilih?')) {
                    event.preventDefault();
                }
            });

            updateButton();
        });
    </script>
@endpush
