@extends('layouts.master')

@section('title', 'Production Batch')

@section('content')
    <div class="page-section">
        <div class="section-header">
            <h1>Production Batch</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="#">Production Batch</a>
                </div>
                <div class="breadcrumb-item">
                    Table
                </div>
            </div>
        </div>

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

        <div class="mb-4">
            <a href="{{ route('operator.production-batch.create') }}" class="btn btn-add">
                <i class="fas fa-plus mr-1"></i>
                Tambah Production Batch
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h4>
                    <i class="fas fa-filter mr-2"></i>
                    Filter Data
                </h4>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('operator.production-batch.index') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="filter-label">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="filter-label">Shift</label>
                                <select name="shift" class="form-control">
                                    <option value="">Semua Shift</option>
                                    @foreach ([1, 2, 3] as $shift)
                                        <option value="{{ $shift }}"
                                            {{ request('shift') == $shift ? 'selected' : '' }}>
                                            Shift {{ $shift }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="filter-label">Cost Center Name</label>
                                <select name="cost_center_name" class="form-control">
                                    <option value="">Semua Cost Center</option>
                                    @foreach (['FCHO', 'FCHS', 'FCHM', 'FCHT', 'FCHR', 'FCH1', 'FCH2', 'FCH3', 'FCH4', 'FCH5'] as $costCenter)
                                        <option value="{{ $costCenter }}"
                                            {{ request('cost_center_name') == $costCenter ? 'selected' : '' }}>
                                            {{ $costCenter }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="filter-label">No. PO</label>
                                <input type="text" name="no_po" class="form-control" placeholder="Cari nomor PO..."
                                    value="{{ request('no_po') }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="filter-label">Tanggal Transfer</label>
                                <input type="date" name="tanggal_transfer" class="form-control"
                                    value="{{ request('tanggal_transfer') }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="filter-label">Tanggal Proses</label>
                                <input type="date" name="tanggal_proses" class="form-control"
                                    value="{{ request('tanggal_proses') }}">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center" style="gap: .5rem;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search mr-1"></i>
                            Tampilkan
                        </button>
                        <a href="{{ route('operator.production-batch.index') }}" class="btn btn-ghost">
                            <i class="fas fa-sync-alt mr-1"></i>
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-accent">
            <div class="card-header">
                <h4>
                    <i class="fas fa-layer-group mr-2"></i>
                    Data Production Batch
                </h4>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-wide">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Tanggal</th>
                                <th>Shift</th>
                                <th>Cost Center Name</th>
                                <th>No. PO</th>
                                <th>Tanggal Transfer</th>
                                <th>Tanggal Proses</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($productionBatches as $index => $productionBatch)
                                <tr>
                                    <td class="text-center">
                                        {{ $productionBatches->firstItem() + $index }}
                                    </td>

                                    <td>
                                        @if ($productionBatch->tanggal)
                                            {{ $productionBatch->tanggal->format('d/m/Y') }}
                                        @else
                                            <span class="value-empty">-</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($productionBatch->shift)
                                            <span class="badge-soft">
                                                Shift {{ $productionBatch->shift }}
                                            </span>
                                        @else
                                            <span class="value-empty">-</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($productionBatch->cost_center_name)
                                            <span class="badge-soft">
                                                {{ $productionBatch->cost_center_name }}
                                            </span>
                                        @else
                                            <span class="value-empty">-</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($productionBatch->no_po)
                                            {{ $productionBatch->no_po }}
                                        @else
                                            <span class="value-empty">-</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($productionBatch->tanggal_transfer)
                                            {{ $productionBatch->tanggal_transfer->format('d/m/Y') }}
                                        @else
                                            <span class="value-empty">-</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($productionBatch->tanggal_proses)
                                            {{ $productionBatch->tanggal_proses->format('d/m/Y') }}
                                        @else
                                            <span class="value-empty">-</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <div class="action-buttons">
                                            <a href="#" class="btn btn-outline-danger" title="Export PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>

                                            <a href="{{ route('operator.production-batch.export', $productionBatch->id) }}"
                                                class="btn btn-outline-success" title="Export Excel">
                                                <i class="fas fa-file-excel"></i>
                                            </a>

                                            <a href="{{ route('operator.production-batch.detail', $productionBatch->id) }}"
                                                class="btn btn-outline-info" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <a href="{{ route('operator.production-batch.edit', $productionBatch->id) }}"
                                                class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form
                                                action="{{ route('operator.production-batch.destroy', $productionBatch->id) }}"
                                                method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Hapus"
                                                    onclick="return confirm('Yakin hapus data production batch ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">
                                        <div class="empty-state">
                                            <i class="fas fa-layer-group d-block"></i>
                                            <div>Belum ada data Production Batch.</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white border-top">
                {{ $productionBatches->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
