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
                                    Shift {{ $production->shift ?? '-' }}
                                </div>
                            </div>

                            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                <div class="batch-label">No. PO</div>
                                <div class="batch-number batch-date">
                                    {{ $production->no_po ?? '-' }}
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
                                <div class="info-label">No. PO</div>
                                <div class="info-value">{{ $production->no_po ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-boxes"></i>
                                </div>
                                <div>
                                    <div class="stat-label">Jumlah Jenis Produk</div>
                                    <div class="stat-value">
                                        {{ $production->bahanBakus->count() }} Produk
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-weight-hanging"></i>
                                </div>
                                <div>
                                    <div class="stat-label">Total Berat</div>
                                    <div class="stat-value">
                                        {{ number_format($production->bahanBakus->sum('jumlah_kg'), 2, ',', '.') }} KG
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
                        Data Produk
                    </h4>
                </div>

                <div class="card-body">
                    <form id="bulkDeleteForm" action="{{ route('operator.bahan-baku.bulk-destroy') }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <div class="table-responsive">
                            <table class="table table-bordered table-wide">
                                <thead>
                                    <tr>
                                        <th width="50" class="text-center">
                                            <input type="checkbox" id="checkAll">
                                        </th>
                                        <th width="60" class="text-center">No</th>
                                        <th>Nama Produk</th>
                                        <th>Jumlah Bag</th>
                                        <th>Berat (KG)</th>
                                        <th width="100" class="text-center">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($production->bahanBakus as $index => $bahanBaku)
                                        <tr>
                                            <td class="text-center align-middle">
                                                <input type="checkbox" name="ids[]" value="{{ $bahanBaku->id }}"
                                                    class="detail-checkbox">
                                            </td>

                                            <td class="text-center font-weight-bold">
                                                {{ $index + 1 }}
                                            </td>

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
                                                <div class="action-buttons">
                                                    <form
                                                        action="{{ route('operator.bahan-baku.destroy', $bahanBaku->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                            title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">
                                                <div class="empty-state">
                                                    <i class="fas fa-box-open d-block"></i>
                                                    <div>Belum ada data produk.</div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                @if ($production->bahanBakus->isNotEmpty())
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-right font-weight-bold">
                                                Total Bahan Baku
                                            </td>
                                            <td colspan="2" class="font-weight-bold">
                                                {{ number_format($production->bahanBakus->sum('jumlah_kg'), 2, ',', '.') }}
                                                KG
                                            </td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card card-final">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <div class="info-label mb-1">Total Berat Bahan Baku</div>
                            <div class="stat-value">
                                {{ number_format($production->bahanBakus->sum('jumlah_kg'), 2, ',', '.') }} KG
                            </div>
                        </div>

                        <div class="mt-3 mt-md-0">
                            <a href="{{ route('operator.bahan-baku.index') }}" class="btn btn-ghost mr-1">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Kembali
                            </a>

                            @if ($production->bahanBakus->isNotEmpty())
                                <a href="{{ route('operator.bahan-baku.edit', $production->id) }}"
                                    class="btn btn-primary mr-1">
                                    <i class="fas fa-edit mr-1"></i>
                                    Edit
                                </a>
                            @endif

                            <button type="submit" form="bulkDeleteForm" id="btnBulkDelete" class="btn btn-danger"
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkAll = document.getElementById('checkAll');
            const btnBulkDelete = document.getElementById('btnBulkDelete');
            const checkboxes = document.querySelectorAll('.detail-checkbox');
            const bulkDeleteForm = document.getElementById('bulkDeleteForm');

            function updateButton() {
                const checked = document.querySelectorAll('.detail-checkbox:checked');

                btnBulkDelete.disabled = checked.length === 0;
                checkAll.checked = checkboxes.length > 0 && checked.length === checkboxes.length;
                checkAll.indeterminate = checked.length > 0 && checked.length < checkboxes.length;
            }

            checkAll.addEventListener('change', function() {
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = checkAll.checked;
                });

                updateButton();
            });

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', updateButton);
            });

            bulkDeleteForm.addEventListener('submit', function(event) {
                const checked = document.querySelectorAll('.detail-checkbox:checked');

                if (checked.length === 0) {
                    event.preventDefault();
                    alert('Pilih minimal satu produk.');
                    return;
                }

                if (!confirm('Yakin ingin menghapus data bahan baku yang dipilih?')) {
                    event.preventDefault();
                }
            });
        });
    </script>
@endpush
