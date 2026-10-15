<?php

namespace App\Http\Controllers;

use App\Models\ProductionBatch;
use App\Models\RekapitulasiProduksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekapitulasiProduksiController extends Controller
{
    public function index(Request $request)
    {
        $query = RekapitulasiProduksi::with('productionBatch')->latest();

        if ($request->filled('date_from')) {
            $query->whereHas('productionBatch', function ($q) use ($request) {
                $q->whereDate('tanggal', '>=', $request->date_from);
            });
        }

        if ($request->filled('date_to')) {
            $query->whereHas('productionBatch', function ($q) use ($request) {
                $q->whereDate('tanggal', '<=', $request->date_to);
            });
        }

        if ($request->filled('shift')) {
            $query->whereHas('productionBatch', function ($q) use ($request) {
                $q->where('shift', $request->shift);
            });
        }

        if ($request->filled('no_po')) {
            $query->where('no_po', 'like', '%' . $request->no_po . '%');
        }

        $rekapitulasiProduksis = $query->paginate(10)->withQueryString();

        return view(
            'pages.operator.rekapitulasi-produksi.index',
            compact('rekapitulasiProduksis')
        );
    }

    public function create()
    {
        $productions = ProductionBatch::latest('tanggal')
            ->latest('id')
            ->get();

        return view(
            'pages.operator.rekapitulasi-produksi.create',
            compact('productions')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'production_batch_id' => [
                'required',
                'integer',
                'exists:production_batches,id',
            ],
            'mesin' => ['nullable', 'string', 'max:255'],
            'waktu_start' => ['nullable', 'date_format:H:i'],
            'waktu_stop' => ['nullable', 'date_format:H:i'],
            'operation_time' => ['nullable', 'numeric', 'min:0'],
            'speed_kapasitas' => ['nullable', 'numeric', 'min:0'],
            'aktual' => ['nullable', 'numeric', 'min:0'],
            'persen_pencapaian' => ['nullable', 'numeric', 'min:0'],
            'output_kg' => ['nullable', 'numeric', 'min:0'],
            'downtime' => ['nullable', 'numeric', 'min:0'],
            'penyebab_downtime' => ['nullable', 'string'],
            'tindakan_perbaikan' => ['nullable', 'string'],
            'pic_operator' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            DB::transaction(function () use ($validated) {
                RekapitulasiProduksi::create([
                    'production_batch_id' => $validated['production_batch_id'],
                    'mesin' => $validated['mesin'] ?? null,
                    'waktu_start' => $validated['waktu_start'] ?? null,
                    'waktu_stop' => $validated['waktu_stop'] ?? null,
                    'operation_time' => $validated['operation_time'] ?? 0,
                    'speed_kapasitas' => $validated['speed_kapasitas'] ?? 0,
                    'aktual' => $validated['aktual'] ?? 0,
                    'persen_pencapaian' => $validated['persen_pencapaian'] ?? 0,
                    'output_kg' => $validated['output_kg'] ?? 0,
                    'downtime' => $validated['downtime'] ?? 0,
                    'penyebab_downtime' => $validated['penyebab_downtime'] ?? null,
                    'tindakan_perbaikan' => $validated['tindakan_perbaikan'] ?? null,
                    'pic_operator' => $validated['pic_operator'] ?? null,
                ]);
            });

            return redirect()
                ->route('operator.rekapitulasi-produksi.index')
                ->with('success', 'Data rekapitulasi produksi dan monitoring mesin berhasil disimpan.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data rekapitulasi produksi dan monitoring mesin.');
        }
    }

    public function detail($id)
    {
        $rekapitulasiProduksi = RekapitulasiProduksi::with('productionBatch')
            ->findOrFail($id);

        return view(
            'pages.operator.rekapitulasi-produksi.detail',
            compact('rekapitulasiProduksi')
        );
    }

    public function edit($id)
    {
        $rekapitulasiProduksi = RekapitulasiProduksi::with('productionBatch')->findOrFail($id);

        $productions = ProductionBatch::latest('tanggal')
            ->latest('id')
            ->get();

        return view(
            'pages.operator.rekapitulasi-produksi.edit',
            compact('rekapitulasiProduksi', 'productions')
        );
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'production_batch_id' => [
                'required',
                'integer',
                'exists:production_batches,id',
            ],
            'mesin' => ['nullable', 'string', 'max:255'],
            'waktu_start' => ['nullable', 'date_format:H:i'],
            'waktu_stop' => ['nullable', 'date_format:H:i'],
            'operation_time' => ['nullable', 'numeric', 'min:0'],
            'speed_kapasitas' => ['nullable', 'numeric', 'min:0'],
            'aktual' => ['nullable', 'numeric', 'min:0'],
            'persen_pencapaian' => ['nullable', 'numeric', 'min:0'],
            'output_kg' => ['nullable', 'numeric', 'min:0'],
            'downtime' => ['nullable', 'numeric', 'min:0'],
            'penyebab_downtime' => ['nullable', 'string'],
            'tindakan_perbaikan' => ['nullable', 'string'],
            'pic_operator' => ['nullable', 'string', 'max:255'],
        ]);

        $rekapitulasiProduksi = RekapitulasiProduksi::findOrFail($id);

        $rekapitulasiProduksi->update([
            'production_batch_id' => $validated['production_batch_id'],
            'mesin' => $validated['mesin'] ?? null,
            'waktu_start' => $validated['waktu_start'] ?? null,
            'waktu_stop' => $validated['waktu_stop'] ?? null,
            'operation_time' => $validated['operation_time'] ?? 0,
            'speed_kapasitas' => $validated['speed_kapasitas'] ?? 0,
            'aktual' => $validated['aktual'] ?? 0,
            'persen_pencapaian' => $validated['persen_pencapaian'] ?? 0,
            'output_kg' => $validated['output_kg'] ?? 0,
            'downtime' => $validated['downtime'] ?? 0,
            'penyebab_downtime' => $validated['penyebab_downtime'] ?? null,
            'tindakan_perbaikan' => $validated['tindakan_perbaikan'] ?? null,
            'pic_operator' => $validated['pic_operator'] ?? null,
        ]);

        return redirect()
            ->route('operator.rekapitulasi-produksi.index')
            ->with('success', 'Data rekapitulasi produksi dan monitoring mesin berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $rekapitulasiProduksi = RekapitulasiProduksi::findOrFail($id);
        $rekapitulasiProduksi->delete();

        return redirect()
            ->route('operator.rekapitulasi-produksi.index')
            ->with('success', 'Data rekapitulasi produksi dan monitoring mesin berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:rekapitulasi_produksis,id',
            ],
        ]);

        RekapitulasiProduksi::whereIn('id', $request->ids)->delete();

        return redirect()
            ->route('operator.rekapitulasi-produksi.index')
            ->with('success', 'Data rekapitulasi produksi dan monitoring mesin yang dipilih berhasil dihapus.');
    }

    public function managerIndex(Request $request)
    {
        $query = RekapitulasiProduksi::with('productionBatch')->latest();

        if ($request->filled('date_from')) {
            $query->whereHas('productionBatch', function ($q) use ($request) {
                $q->whereDate('tanggal', '>=', $request->date_from);
            });
        }

        if ($request->filled('date_to')) {
            $query->whereHas('productionBatch', function ($q) use ($request) {
                $q->whereDate('tanggal', '<=', $request->date_to);
            });
        }

        if ($request->filled('shift')) {
            $query->whereHas('productionBatch', function ($q) use ($request) {
                $q->where('shift', $request->shift);
            });
        }

        if ($request->filled('no_po')) {
            $query->where('no_po', 'like', '%' . $request->no_po . '%');
        }

        $rekapitulasiProduksis = $query->paginate(10)->withQueryString();

        return view(
            'pages.manager.rekapitulasi-produksi.index',
            compact('rekapitulasiProduksis')
        );
    }

    public function managerDetail($id)
    {
        $rekapitulasiProduksi = RekapitulasiProduksi::with('productionBatch')->findOrFail($id);

        return view(
            'pages.manager.rekapitulasi-produksi.detail',
            compact('rekapitulasiProduksi')
        );
    }
}
