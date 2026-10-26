@extends('layouts.master')

@section('title', 'Rekapitulasi Produksi dan Monitoring Mesin')

@section('content')
    <div class="page-section">
        <div class="section-header">
            <h1>Rekapitulasi Produksi dan Monitoring Mesin</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="{{ route('operator.rekapitulasi-produksi.index') }}">Rekapitulasi Produksi</a>
                </div>
                <div class="breadcrumb-item">Table</div>
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
            <a href="{{ route('operator.rekapitulasi-produksi.create') }}" class="btn btn-add">
                <i class="fas fa-plus mr-1"></i>
                Tambah Rekapitulasi Produksi
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
                <form method="GET" action="{{ route('operator.rekapitulasi-produksi.index') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="filter-label">Tanggal Dari</label>
                                <input type="date" name="date_from" class="form-control"
                                    value="{{ request('date_from') }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="filter-label">Tanggal Sampai</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="filter-label">Shift</label>
                                <select name="shift" class="form-control">
                                    <option value="">Semua Shift</option>
                                    <option value="1" {{ request('shift') == '1' ? 'selected' : '' }}>Shift 1</option>
                                    <option value="2" {{ request('shift') == '2' ? 'selected' : '' }}>Shift 2</option>
                                    <option value="3" {{ request('shift') == '3' ? 'selected' : '' }}>Shift 3</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="d-flex align-items-center" style="gap: .5rem;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search mr-1"></i>
                            Tampilkan
                        </button>

                        <a href="{{ route('operator.rekapitulasi-produksi.index') }}" class="btn btn-ghost">
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
                    <i class="fas fa-clipboard-list mr-2"></i>
                    Data Rekapitulasi Produksi dan Monitoring Mesin
                </h4>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-wide">
                        <thead>
                            <tr>
                                <th class="text-center" width="60">No</th>
                                <th>Tanggal</th>
                                <th>Shift</th>
                                <th>Conveyor</th>
                                <th>Bahan Baku</th>
                                <th>Total KG</th>
                                <th>Mesin</th>
                                <th class="text-center" width="150">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rekapitulasiProduksis as $index => $rekapitulasiProduksi)
                                <tr>
                                    <td class="text-center">
                                        {{ $rekapitulasiProduksis->firstItem() + $index }}
                                    </td>
                                    <td>
                                        {{ $rekapitulasiProduksi->productionBatch?->tanggal
                                            ? \Carbon\Carbon::parse($rekapitulasiProduksi->productionBatch->tanggal)->format('d/m/Y')
                                            : '-' }}
                                    </td>
                                    <td>
                                        {{ $rekapitulasiProduksi->productionBatch?->shift
                                            ? 'Shift ' . $rekapitulasiProduksi->productionBatch->shift
                                            : '-' }}
                                    </td>
                                    <td>
                                        {{ $rekapitulasiProduksi->ProductionBatch->conveyor ? $rekapitulasiProduksi->productionBatch->conveyor : '-' }}
                                    </td>
                                    <td>
                                        {{ $rekapitulasiProduksi->bahanBaku->material->material_description }}
                                    </td>
                                    <td>
                                        {{ $rekapitulasiProduksi->bahanBaku->jumlah_kg }}
                                    </td>
                                    <td>{{ $rekapitulasiProduksi->mesin ?? '-' }}</td>
                                    <td class="text-center">
                                        <div class="action-buttons">
                                            <a href="{{ route('operator.rekapitulasi-produksi.detail', $rekapitulasiProduksi->id) }}"
                                                class="btn btn-outline-info" title="Detail">
                                                <i class="fas fa-file-alt"></i> Lihat Detail
                                            </a>

                                            <a href="{{ route('operator.rekapitulasi-produksi.edit', $rekapitulasiProduksi->id) }}"
                                                class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>

                                            <form
                                                action="{{ route('operator.rekapitulasi-produksi.destroy', $rekapitulasiProduksi->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin hapus data rekapitulasi produksi ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <i class="fas fa-clipboard-list d-block"></i>
                                            <div>Belum ada data rekapitulasi produksi dan monitoring mesin.</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white border-top">
                {{ $rekapitulasiProduksis->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
