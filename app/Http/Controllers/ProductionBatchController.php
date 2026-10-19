<?php

namespace App\Http\Controllers;

use App\Exports\LaporanPengendalianProdukExport;
use App\Models\ProductionBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ProductionBatchController extends Controller
{
    public function getByDate($date)
    {
        $productionBatches = ProductionBatch::whereDate('tanggal', $date)
            ->latest('id')
            ->get();

        return response()->json($productionBatches);
    }

    public function index(Request $request)
    {
        $query = ProductionBatch::query()
            ->latest('tanggal')
            ->latest('id');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }

        if ($request->filled('conveyor')) {
            $query->where('conveyor', 'like', '%' . $request->conveyor . '%');
        }

        if ($request->filled('tanggal_transfer')) {
            $query->whereDate('tanggal_transfer', $request->tanggal_transfer);
        }

        if ($request->filled('tanggal_proses')) {
            $query->whereDate('tanggal_proses', $request->tanggal_proses);
        }

        if ($request->filled('cost_center_name')) {
            $query->where('cost_center_name', $request->cost_center_name);
        }

        if ($request->filled('no_po')) {
            $query->where('no_po', 'like', '%' . $request->no_po . '%');
        }

        $productionBatches = $query->paginate(10)->withQueryString();

        return view(
            'pages.operator.production-batch.index',
            compact('productionBatches')
        );
    }

    public function managerIndex(Request $request)
    {
        $query = ProductionBatch::query()
            ->latest('tanggal')
            ->latest('id');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }

        if ($request->filled('conveyor')) {
            $query->where('conveyor', 'like', '%' . $request->conveyor . '%');
        }

        if ($request->filled('tanggal_transfer')) {
            $query->whereDate('tanggal_transfer', $request->tanggal_transfer);
        }

        if ($request->filled('tanggal_proses')) {
            $query->whereDate('tanggal_proses', $request->tanggal_proses);
        }

        if ($request->filled('cost_center_name')) {
            $query->where('cost_center_name', $request->cost_center_name);
        }

        if ($request->filled('no_po')) {
            $query->where('no_po', 'like', '%' . $request->no_po . '%');
        }

        $productionBatches = $query->paginate(10)->withQueryString();

        return view(
            'pages.manager.production-batch.index',
            compact('productionBatches')
        );
    }

    public function managerDetail($id)
    {
        $productionBatch = ProductionBatch::findOrFail($id);

        return view(
            'pages.manager.production-batch.detail',
            compact('productionBatch')
        );
    }

    public function detail($id)
    {
        $productionBatch = ProductionBatch::with([
            'bahanBakus.material',
            'dataHasilProduksis.material',
            'metalDetectors.material',
            'productivities.material',
            'rekapitulasiProduksis',
        ])->findOrFail($id);

        return view(
            'pages.operator.production-batch.detail',
            compact('productionBatch')
        );
    }

    public function create()
    {
        return view('pages.operator.production-batch.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'shift' => ['required', 'integer', 'min:1', 'max:3'],
            'conveyor' => ['required', 'string', 'max:100'],
            'absen_worker' => ['nullable', 'integer', 'min:0'],
            'tanggal_transfer' => ['nullable', 'date'],
            'tanggal_proses' => ['nullable', 'date'],
            'cost_center_name' => [
                'nullable',
                'string',
                'in:FCHO,FCHS,FCHM,FCHT,FCHR,FCH1,FCH2,FCH3,FCH4,FCH5',
            ],
            'no_po' => ['nullable', 'string', 'max:255'],
        ]);

        DB::beginTransaction();

        try {
            ProductionBatch::create($validated);

            DB::commit();

            return redirect()
                ->route('operator.production-batch.index')
                ->with('success', 'Data production batch berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data production batch.');
        }
    }

    public function edit($id)
    {
        $productionBatch = ProductionBatch::findOrFail($id);

        return view(
            'pages.operator.production-batch.edit',
            compact('productionBatch')
        );
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'shift' => ['required', 'integer', 'min:1', 'max:3'],
            'conveyor' => ['required', 'string', 'max:100'],
            'absen_worker' => ['nullable', 'integer', 'min:0'],
            'tanggal_transfer' => ['nullable', 'date'],
            'tanggal_proses' => ['nullable', 'date'],
            'cost_center_name' => [
                'nullable',
                'string',
                'in:FCHO,FCHS,FCHM,FCHT,FCHR,FCH1,FCH2,FCH3,FCH4,FCH5',
            ],
            'no_po' => ['nullable', 'string', 'max:255'],
        ]);

        DB::beginTransaction();

        try {
            $productionBatch = ProductionBatch::findOrFail($id);
            $productionBatch->update($validated);

            DB::commit();

            return redirect()
                ->route('operator.production-batch.index')
                ->with('success', 'Data production batch berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data production batch.');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $productionBatch = ProductionBatch::findOrFail($id);
            $productionBatch->delete();

            DB::commit();

            return redirect()
                ->route('operator.production-batch.index')
                ->with('success', 'Data production batch berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            return redirect()
                ->route('operator.production-batch.index')
                ->with('error', 'Gagal menghapus data production batch.');
        }
    }

    public function export($id)
    {
        $productionBatch = ProductionBatch::findOrFail($id);

        return Excel::download(
            new LaporanPengendalianProdukExport($productionBatch->id),
            'Laporan-Pengendalian-Produk.xlsx'
        );
    }

    public function managerExport($id)
    {
        $productionBatch = ProductionBatch::findOrFail($id);

        return Excel::download(
            new LaporanPengendalianProdukExport($productionBatch->id),
            'Laporan-Pengendalian-Produk.xlsx'
        );
    }
}
