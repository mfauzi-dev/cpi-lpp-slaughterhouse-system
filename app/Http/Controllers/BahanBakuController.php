<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Material;
use App\Models\ProductionBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BahanBakuController extends Controller
{
    public function managerIndex(Request $request)
    {
        $query = ProductionBatch::query()
            ->withCount('bahanBakus')
            ->withSum('bahanBakus', 'jumlah_kg');

        if ($request->filled('date_from')) {
            $query->whereDate('tanggal', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('tanggal', '<=', $request->date_to);
        }

        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }

        if ($request->filled('no_po')) {
            $query->where('no_po', 'like', '%' . $request->no_po . '%');
        }

        $bahanBakus = $query->whereHas('bahanBakus')
            ->with('bahanBakus.material')
            ->latest('tanggal')
            ->paginate(10)
            ->withQueryString();

        return view(
            'pages.manager.bahan-baku.index',
            compact('bahanBakus')
        );
    }

    public function managerDetail($id)
    {
        $production = ProductionBatch::with('bahanBakus.material')
            ->findOrFail($id);

        return view(
            'pages.manager.bahan-baku.detail',
            compact('production')
        );
    }

    public function index(Request $request)
    {
        $query = ProductionBatch::query()
            ->withCount('bahanBakus')
            ->withSum('bahanBakus', 'jumlah_kg');

        if ($request->filled('date_from')) {
            $query->whereDate('tanggal', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('tanggal', '<=', $request->date_to);
        }

        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }

        if ($request->filled('no_po')) {
            $query->where('no_po', 'like', '%' . $request->no_po . '%');
        }

        $bahanBakus = $query->whereHas('bahanBakus')
            ->with('bahanBakus.material')
            ->latest('tanggal')
            ->paginate(10)
            ->withQueryString();

        return view(
            'pages.operator.bahan-baku.index',
            compact('bahanBakus')
        );
    }

    public function create()
    {
        $materials = Material::orderBy('material_code')->get([
            'id',
            'material_code',
            'material_description',
        ]);

        $productions = ProductionBatch::orderByDesc('tanggal')->get();

        return view(
            'pages.operator.bahan-baku.create',
            compact('materials', 'productions')
        );
    }

    public function getProducts()
    {
        $bahanBakus = BahanBaku::with('material')
            ->orderBy('id')
            ->get([
                'id',
                'production_batch_id',
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
            'products' => [
                'required',
                'array',
                'min:1',
            ],
            'products.*.material_id' => [
                'required',
                'integer',
                'distinct',
                'exists:materials,id',
            ],
            'products.*.jumlah_bag' => [
                'required',
                'integer',
                'min:0',
            ],
            'products.*.jumlah_kg' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->products as $product) {
                BahanBaku::create([
                    'production_batch_id' => $request->production_batch_id,
                    'material_id' => $product['material_id'],
                    'jumlah_bag' => $product['jumlah_bag'],
                    'jumlah_kg' => $product['jumlah_kg'],
                ]);
            }
        });

        return redirect()
            ->route('operator.bahan-baku.index')
            ->with('success', 'Data bahan baku berhasil ditambahkan.');
    }

    public function detail($id)
    {
        $production = ProductionBatch::with('bahanBakus.material')
            ->findOrFail($id);

        return view(
            'pages.operator.bahan-baku.detail',
            compact('production')
        );
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer', 'distinct', 'exists:bahan_bakus,id'],
        ]);

        DB::transaction(function () use ($request) {
            BahanBaku::whereIn('id', $request->ids)->delete();
        });

        return redirect()
            ->back()
            ->with('success', 'Data bahan baku yang dipilih berhasil dihapus.');
    }

    public function edit($id)
    {
        $production = ProductionBatch::with('bahanBakus.material')
            ->findOrFail($id);

        $materials = Material::orderBy('material_code')->get([
            'id',
            'material_code',
            'material_description',
        ]);

        return view(
            'pages.operator.bahan-baku.edit',
            compact('production', 'materials')
        );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'products' => [
                'required',
                'array',
                'min:1',
            ],
            'products.*.material_id' => [
                'required',
                'integer',
                'distinct',
                'exists:materials,id',
            ],
            'products.*.jumlah_bag' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'products.*.jumlah_kg' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ]);

        DB::beginTransaction();

        try {
            $production = ProductionBatch::findOrFail($id);

            $production->bahanBakus()->delete();

            $hasDetail = false;

            foreach ($request->products as $row) {
                $jumlahBag = $row['jumlah_bag'] ?? '';
                $jumlahKg = $row['jumlah_kg'] ?? '';

                $isFilled = $jumlahBag !== '' || $jumlahKg !== '';

                if ($isFilled) {
                    $production->bahanBakus()->create([
                        'material_id' => $row['material_id'],
                        'jumlah_bag' => $jumlahBag === '' ? 0 : $jumlahBag,
                        'jumlah_kg' => $jumlahKg === '' ? 0 : $jumlahKg,
                    ]);

                    $hasDetail = true;
                }
            }

            if (!$hasDetail) {
                throw new \Exception(
                    'Minimal satu produk harus diisi datanya.'
                );
            }

            DB::commit();

            return redirect()
                ->route('operator.bahan-baku.detail', $production->id)
                ->with(
                    'success',
                    'Data bahan baku berhasil diperbarui.'
                );
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Gagal memperbarui data: ' . $e->getMessage()
                );
        }
    }

    public function destroy($id)
    {
        $bahanBaku = BahanBaku::findOrFail($id);
        $productionBatchId = $bahanBaku->production_batch_id;

        $bahanBaku->delete();

        return redirect()
            ->route('operator.bahan-baku.index')
            ->with('success', 'Data bahan baku berhasil dihapus.');
    }
}
