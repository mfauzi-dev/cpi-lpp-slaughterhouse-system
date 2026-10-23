<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\DataHasilProduksi;
use App\Models\Material;
use App\Models\ProductionBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataHasilProduksiController extends Controller
{
    public function index(Request $request)
    {
        $query = DataHasilProduksi::with(['productionBatch', 'material', 'bahanBaku'])->latest();

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

        $hasilProduksis = $query->paginate(10)->withQueryString();

        return view(
            'pages.operator.hasil-produksi.index',
            compact('hasilProduksis')
        );
    }

    public function create()
    {
        $productions = ProductionBatch::withCount('bahanBakus')
            ->withSum('bahanBakus', 'jumlah_kg')
            ->latest('tanggal')
            ->latest('id')
            ->get();

        return view(
            'pages.operator.hasil-produksi.create',
            compact('productions')
        );
    }

    public function getPoList(Request $request)
    {
        $request->validate([
            'production_batch_id' => [
                'required',
                'integer',
                'exists:production_batches,id',
            ],
        ]);

        $groups = BahanBaku::where('production_batch_id', $request->production_batch_id)
            ->get(['no_po', 'jumlah_kg'])
            ->groupBy(fn ($item) => $item->no_po ?? '')
            ->map(function ($items, $noPo) {
                return [
                    'no_po' => $noPo,
                    'jumlah_produk' => $items->count(),
                    'total_kg' => $items->sum('jumlah_kg'),
                ];
            })
            ->values();

        return response()->json($groups);
    }

    public function getProducts(Request $request)
    {

        $materials = Material::query()
            ->orderBy('material_code')
            ->get(['id', 'material_code', 'material_description']);

         return response()->json($materials);
    }

    public function getBahanBakuByPo(Request $request)
    {
        $request->validate([
            'production_batch_id' => [
                'required',
                'integer',
                'exists:production_batches,id',
            ],
            'no_po' => ['present', 'nullable', 'string'],
        ]);

        $query = BahanBaku::with([
            'material:id,material_code,material_description',
        ])->where('production_batch_id', $request->production_batch_id);

        if ($request->input('no_po') === '') {
            $query->where(function ($q) {
                $q->whereNull('no_po')
                    ->orWhere('no_po', '');
            });
        } else {
            $query->where('no_po', $request->no_po);
        }

        $bahanBakus = $query
            ->orderBy('id')
            ->get([
                'id',
                'production_batch_id',
                'no_po',
                'material_id',
                'jumlah_bag',
                'jumlah_kg',
            ]);

        return response()->json($bahanBakus);
    }

    public function store(Request $request)
    {
        $request->validate([
            'production_batch_id' => [
                'required',
                'integer',
                'exists:production_batches,id',
            ],
            'bahan_baku_id' => [
                'nullable',
                'integer',
                'exists:bahan_bakus,id',
            ],
            'no_po' => ['nullable', 'string', 'max:255'],
            'cost_center_name' => [
                'nullable',
                'string',
                'in:FCHO,FCHS,FCHM,FCHT,FCHR,FCH1,FCH2,FCH3,FCH4,FCH5',
            ],
            'material_id' => [
                'required',
                'integer',
                'exists:materials,id',
            ],
            'domestik_kg' => ['nullable', 'numeric', 'min:0'],
            'further_kg' => ['nullable', 'numeric', 'min:0'],
            'frozen_kg' => ['nullable', 'numeric', 'min:0'],
            'rm' => ['nullable', 'numeric', 'min:0'],
            'total_bag' => ['nullable', 'integer', 'min:0'],
            'total_kg' => ['nullable', 'numeric', 'min:0'],
            'loss_kg' => ['nullable', 'numeric', 'min:0'],
            'loss_persen' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'pickup_kg' => ['nullable', 'numeric', 'min:0'],
            'pickup_persen' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'pengecekan_suhu_jam' => ['nullable', 'date_format:H:i'],
            'pengecekan_suhu_suhu' => ['nullable', 'numeric'],
            'packaging_nama_kemasan' => ['nullable', 'string', 'max:255'],
            'packaging_jumlah_kg' => ['nullable', 'numeric', 'min:0'],
            'jumlah_kg_bahan_baku' => ['nullable', 'numeric', 'min:0'],
        ]);

        try {
            DB::transaction(function () use ($request) {
                $totalKg = $request->total_kg ?? 0;
                $jumlahKgBahanBaku = $request->jumlah_kg_bahan_baku ?? 0;

                $yieldPersen = $jumlahKgBahanBaku > 0
                    ? round(($totalKg / $jumlahKgBahanBaku) * 100, 2)
                    : 0;

                DataHasilProduksi::create([
                    'production_batch_id' => $request->production_batch_id,
                    'bahan_baku_id' => $request->bahan_baku_id,
                    'no_po' => $request->no_po,
                    'cost_center_name' => $request->cost_center_name,
                    'material_id' => $request->material_id,
                    'domestik_kg' => $request->domestik_kg ?? 0,
                    'further_kg' => $request->further_kg ?? 0,
                    'frozen_kg' => $request->frozen_kg ?? 0,
                    'rm' => $request->rm ?? 0,
                    'total_bag' => $request->total_bag ?? 0,
                    'total_kg' => $request->total_kg ?? 0,
                    'yield_persen' => $yieldPersen,
                    'loss_kg' => $request->loss_kg ?? 0,
                    'loss_persen' => $request->loss_persen ?? 0,
                    'pickup_kg' => $request->pickup_kg ?? 0,
                    'pickup_persen' => $request->pickup_persen ?? 0,
                    'pengecekan_suhu_jam' => $request->pengecekan_suhu_jam,
                    'pengecekan_suhu_suhu' => $request->pengecekan_suhu_suhu,
                    'packaging_nama_kemasan' => $request->packaging_nama_kemasan,
                    'packaging_jumlah_kg' => $request->packaging_jumlah_kg ?? 0,
                ]);
            });

            return redirect()
                ->route('operator.hasil-produksi.index')
                ->with('success', 'Data hasil produksi berhasil disimpan.');
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data hasil produksi.');
        }
    }

    public function detail($id)
    {
        $hasilProduksi = DataHasilProduksi::with(['productionBatch', 'material', 'bahanBaku'])->findOrFail($id);

        return view(
            'pages.operator.hasil-produksi.detail',
            compact('hasilProduksi')
        );
    }

    public function edit($id)
    {
        $hasilProduksi = DataHasilProduksi::with(['productionBatch', 'material', 'bahanBaku'])->findOrFail($id);

        $productions = ProductionBatch::withCount('bahanBakus')
            ->withSum('bahanBakus', 'jumlah_kg')
            ->latest('tanggal')
            ->latest('id')
            ->get();

        return view(
            'pages.operator.hasil-produksi.edit',
            compact('hasilProduksi', 'productions')
        );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'production_batch_id' => [
                'required',
                'integer',
                'exists:production_batches,id',
            ],
            'bahan_baku_id' => [
                'nullable',
                'integer',
                'exists:bahan_bakus,id',
            ],
            'no_po' => ['nullable', 'string', 'max:255'],
            'cost_center_name' => [
                'nullable',
                'string',
                'in:FCHO,FCHS,FCHM,FCHT,FCHR,FCH1,FCH2,FCH3,FCH4,FCH5',
            ],
            'material_id' => [
                'required',
                'integer',
                'exists:materials,id',
            ],
            'domestik_kg' => ['nullable', 'numeric', 'min:0'],
            'further_kg' => ['nullable', 'numeric', 'min:0'],
            'frozen_kg' => ['nullable', 'numeric', 'min:0'],
            'rm' => ['nullable', 'numeric', 'min:0'],
            'total_bag' => ['nullable', 'integer', 'min:0'],
            'total_kg' => ['nullable', 'numeric', 'min:0'],
            'loss_kg' => ['nullable', 'numeric', 'min:0'],
            'loss_persen' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'pickup_kg' => ['nullable', 'numeric', 'min:0'],
            'pickup_persen' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'pengecekan_suhu_jam' => ['nullable', 'date_format:H:i'],
            'pengecekan_suhu_suhu' => ['nullable', 'numeric'],
            'packaging_nama_kemasan' => ['nullable', 'string', 'max:255'],
            'packaging_jumlah_kg' => ['nullable', 'numeric', 'min:0'],
            'jumlah_kg_bahan_baku' => ['nullable', 'numeric', 'min:0'],
        ]);

        $hasilProduksi = DataHasilProduksi::findOrFail($id);

        $totalKg = $request->total_kg ?? 0;
        $jumlahKgBahanBaku = $request->jumlah_kg_bahan_baku ?? 0;

        $yieldPersen = $jumlahKgBahanBaku > 0
            ? round(($totalKg / $jumlahKgBahanBaku) * 100, 2)
            : 0;

        $hasilProduksi->update([
            'production_batch_id' => $request->production_batch_id,
            'bahan_baku_id' => $request->bahan_baku_id,
            'no_po' => $request->no_po,
            'cost_center_name' => $request->cost_center_name,
            'material_id' => $request->material_id,
            'domestik_kg' => $request->domestik_kg ?? 0,
            'further_kg' => $request->further_kg ?? 0,
            'frozen_kg' => $request->frozen_kg ?? 0,
            'rm' => $request->rm ?? 0,
            'total_bag' => $request->total_bag ?? 0,
            'total_kg' => $request->total_kg ?? 0,
            'yield_persen' => $yieldPersen,
            'loss_kg' => $request->loss_kg ?? 0,
            'loss_persen' => $request->loss_persen ?? 0,
            'pickup_kg' => $request->pickup_kg ?? 0,
            'pickup_persen' => $request->pickup_persen ?? 0,
            'pengecekan_suhu_jam' => $request->pengecekan_suhu_jam,
            'pengecekan_suhu_suhu' => $request->pengecekan_suhu_suhu,
            'packaging_nama_kemasan' => $request->packaging_nama_kemasan,
            'packaging_jumlah_kg' => $request->packaging_jumlah_kg ?? 0,
        ]);

        return redirect()
            ->route('operator.hasil-produksi.index')
            ->with('success', 'Data hasil produksi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $hasilProduksi = DataHasilProduksi::findOrFail($id);
        $hasilProduksi->delete();

        return redirect()
            ->route('operator.hasil-produksi.index')
            ->with('success', 'Data hasil produksi berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:data_hasil_produksis,id',
            ],
        ]);

        DataHasilProduksi::whereIn('id', $request->ids)->delete();

        return redirect()
            ->route('operator.hasil-produksi.index')
            ->with('success', 'Data hasil produksi yang dipilih berhasil dihapus.');
    }

    public function managerIndex(Request $request)
    {
        $query = DataHasilProduksi::with(['productionBatch', 'material', 'bahanBaku'])->latest();

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

        $hasilProduksis = $query->paginate(10)->withQueryString();

        return view(
            'pages.manager.hasil-produksi.index',
            compact('hasilProduksis')
        );
    }

    public function managerDetail($id)
    {
        $hasilProduksi = DataHasilProduksi::with(['productionBatch', 'material', 'bahanBaku'])->findOrFail($id);

        return view(
            'pages.manager.hasil-produksi.detail',
            compact('hasilProduksi')
        );
    }
}