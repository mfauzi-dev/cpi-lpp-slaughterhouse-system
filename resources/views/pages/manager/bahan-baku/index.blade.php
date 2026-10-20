@extends('layouts.master')

@section('title', 'Produksi Bahan Baku')

@section('content')
    <div class="page-section">
        <div class="section-header">
            <h1>Produksi Bahan Baku</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="{{ route('manager.bahan-baku.index') }}">Produksi Bahan Baku</a>
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

        <div class="card mb-4">
            <div class="card-header">
                <h4>
                    <i class="fas fa-filter mr-2"></i>
                    Filter Data
                </h4>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('manager.bahan-baku.index') }}">
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

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="filter-label">No. PO</label>
                                <input type="text" name="no_po" class="form-control" placeholder="Cari nomor PO"
                                    value="{{ request('no_po') }}">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center flex-wrap" style="gap: .5rem;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search mr-1"></i>
                            Tampilkan
                        </button>

                        <a href="{{ route('manager.bahan-baku.index') }}" class="btn btn-ghost">
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
                    <i class="fas fa-boxes mr-2"></i>
                    Data Produksi Bahan Baku
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
                                <th>Jumlah Produk</th>
                                <th>Total KG</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($bahanBakus as $index => $bahanBaku)
                                @foreach ($bahanBaku->bahanBakus->groupBy('no_po') as $poNumber => $poItems)
                                    <tr>
                                        <td class="text-center">
                                            {{ $bahanBakus->firstItem() + $index }}
                                        </td>

                                        <td>
                                            <span class="badge-soft">
                                                {{ $bahanBaku->tanggal ? \Carbon\Carbon::parse($bahanBaku->tanggal)->format('d/m/Y') : '-' }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="badge-soft">
                                                {{ $bahanBaku->shift ? 'Shift ' . $bahanBaku->shift : '-' }}
                                            </span>
                                        </td>

                                        <td>
                                            {{ $poItems->pluck('cost_center_name')->filter()->unique()->implode(', ') ?: '-' }}
                                        </td>

                                        <td>
                                            <span class="badge-soft">
                                                {{ $poNumber ?: '-' }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="badge-value">
                                                {{ $poItems->count() }} Produk
                                            </span>
                                        </td>

                                        <td>
                                            <span class="badge-value">
                                                {{ number_format($poItems->sum('jumlah_kg'), 2, ',', '.') }} KG
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <div class="action-buttons">
                                                <a href="{{ route('manager.bahan-baku.detail', [$bahanBaku->id, 'no_po' => $poNumber]) }}"
                                                    class="btn btn-outline-info" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <i class="fas fa-boxes d-block"></i>
                                            <div>Belum ada data Produksi Bahan Baku.</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white border-top">
                {{ $bahanBakus->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
