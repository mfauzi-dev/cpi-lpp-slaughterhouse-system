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
        $query = DataHasilProduksi::with(['productionBatch', 'material'])->latest();

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

        $materials = Material::orderBy('material_code')->get([
            'id',
            'material_code',
            'material_description',
        ]);

        return view(
            'pages.operator.hasil-produksi.create',
            compact('productions', 'materials')
        );
    }

    public function getProducts(Request $request)
    {
        $request->validate([
            'production_batch_id' => [
                'required',
                'integer',
                'exists:production_batches,id',
            ],
        ]);

        $production = ProductionBatch::withCount('bahanBakus')
            ->withSum('bahanBakus', 'jumlah_kg')
            ->findOrFail($request->production_batch_id);
        
        $bahanBakus = BahanBaku::with('material:id,material_code,material_description')
            ->where('production_batch_id', $production->id)
            ->get(['id', 'material_id', 'jumlah_bag', 'jumlah_kg']);

        return response()->json([
            'id' => $production->id,
            'tanggal' => $production->tanggal?->format('Y-m-d'),
            'shift' => $production->shift,
            'conveyor' => $production->conveyor,
            'absen_worker' => $production->absen_worker,
            'tanggal_transfer' => $production->tanggal_transfer?->format('Y-m-d'),
            'tanggal_proses' => $production->tanggal_proses?->format('Y-m-d'),
            'jumlah_produk' => $production->bahan_bakus_count,
            'total_kg' => $production->bahan_bakus_sum_jumlah_kg ?? 0,
            'bahan_bakus' => $bahanBakus,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'production_batch_id' => [
                'required',
                'integer',
                'exists:production_batches,id',
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
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data hasil produksi.');
        }
    }

    public function detail($id)
    {
        $hasilProduksi = DataHasilProduksi::with(['productionBatch', 'material'])->findOrFail($id);

        return view(
            'pages.operator.hasil-produksi.detail',
            compact('hasilProduksi')
        );
    }

    public function edit($id)
    {
        $hasilProduksi = DataHasilProduksi::with(['productionBatch', 'material'])->findOrFail($id);

        $productions = ProductionBatch::withCount('bahanBakus')
            ->withSum('bahanBakus', 'jumlah_kg')
            ->latest('tanggal')
            ->latest('id')
            ->get();

        $materials = Material::orderBy('material_code')->get([
            'id',
            'material_code',
            'material_description',
        ]);

        return view(
            'pages.operator.hasil-produksi.edit',
            compact('hasilProduksi', 'productions', 'materials')
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
        $query = DataHasilProduksi::with(['productionBatch', 'material'])->latest();

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
        $hasilProduksi = DataHasilProduksi::with(['productionBatch', 'material'])->findOrFail($id);

        return view(
            'pages.manager.hasil-produksi.detail',
            compact('hasilProduksi')
        );
    } 
}
