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
            ->withSum('bahanBakus', 'jumlah_kg')
            ->with('bahanBakus.material');

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
            $query->whereHas('bahanBakus', function ($q) use ($request) {
                $q->where('no_po', 'like', '%' . $request->no_po . '%');
            });
        }

        $bahanBakus = $query
            ->whereHas('bahanBakus')
            ->latest('tanggal')
            ->paginate(10)
            ->withQueryString();

        return view(
            'pages.manager.bahan-baku.index',
            compact('bahanBakus')
        );
    }

    public function managerDetail(Request $request, $id)
    {
        $production = ProductionBatch::findOrFail($id);

        $query = BahanBaku::with('material')
            ->where('production_batch_id', $production->id);

        if ($request->has('no_po')) {
            $noPo = $request->query('no_po');

            if ($noPo === '') {
                $query->where(function ($q) {
                    $q->whereNull('no_po')
                        ->orWhere('no_po', '');
                });
            } else {
                $query->where('no_po', $noPo);
            }
        }

        $bahanBakus = $query->get();

        $production->setRelation('bahanBakus', $bahanBakus);

        $poGroups = $bahanBakus->groupBy(function ($item) {
            return $item->no_po ?? '';
        });

        abort_if($request->has('no_po') && $bahanBakus->isEmpty(), 404);

        return view(
            'pages.manager.bahan-baku.detail',
            compact('production', 'poGroups')
        );
    }

    public function index(Request $request)
    {
        $query = ProductionBatch::query()
            ->withCount('bahanBakus')
            ->withSum('bahanBakus', 'jumlah_kg')
            ->with('bahanBakus.material');

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
            $query->whereHas('bahanBakus', function ($q) use ($request) {
                $q->where('no_po', 'like', '%' . $request->no_po . '%');
            });
        }

        $bahanBakus = $query
            ->whereHas('bahanBakus')
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
                'cost_center_name',
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
            'cost_center_name' => [
                'nullable',
                'string',
                'in:FCHO,FCHS,FCHM,FCHT,FCHR,FCH1,FCH2,FCH3,FCH4,FCH5',
            ],
            'no_po' => ['nullable', 'string', 'max:255'],
            'products' => ['required', 'array', 'min:1'],
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
                    'cost_center_name' => $request->cost_center_name,
                    'no_po' => $request->no_po,
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

    public function detail(Request $request, $id)
    {
        $production = ProductionBatch::findOrFail($id);

        $query = BahanBaku::with('material')
            ->where('production_batch_id', $production->id);

        if ($request->has('no_po')) {
            $noPo = $request->query('no_po');

            if ($noPo === '') {
                $query->where(function ($q) {
                    $q->whereNull('no_po')
                        ->orWhere('no_po', '');
                });
            } else {
                $query->where('no_po', $noPo);
            }
        }

        $bahanBakus = $query->get();

        $production->setRelation('bahanBakus', $bahanBakus);

        $poGroups = $bahanBakus->groupBy(function ($item) {
            return $item->no_po ?? '';
        });

        abort_if($request->has('no_po') && $bahanBakus->isEmpty(), 404);

        return view(
            'pages.operator.bahan-baku.detail',
            compact('production', 'poGroups')
        );
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'production_batch_id' => [
                'required',
                'integer',
                'exists:production_batches,id',
            ],
            'no_po' => ['nullable', 'string', 'max:255'],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:bahan_bakus,id',
            ],
        ]);

        $query = BahanBaku::where(
            'production_batch_id',
            $request->production_batch_id
        )->whereIn('id', $request->ids);

        if ($request->filled('no_po')) {
            $query->where('no_po', $request->no_po);
        }

        $deleted = $query->delete();

        return redirect()->route('operator.bahan-baku.index')
            ->with('success', $deleted . ' data bahan baku berhasil dihapus.');
    }

    public function edit($id)
    {
        $bahanBaku = BahanBaku::findOrFail($id);

        $production = ProductionBatch::findOrFail(
            $bahanBaku->production_batch_id
        );

        $products = BahanBaku::with('material')
            ->where('production_batch_id', $bahanBaku->production_batch_id)
            ->where('no_po', $bahanBaku->no_po)
            ->get();

        $materials = Material::orderBy('material_code')->get([
            'id',
            'material_code',
            'material_description',
        ]);

        return view(
            'pages.operator.bahan-baku.edit',
            compact('production', 'bahanBaku', 'products', 'materials')
        );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'products' => ['required', 'array', 'min:1'],
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

        DB::beginTransaction();

        try {
            $bahanBaku = BahanBaku::findOrFail($id);

            $productionBatchId = $bahanBaku->production_batch_id;
            $noPo = $bahanBaku->no_po;
            $costCenterName = $bahanBaku->cost_center_name;

            BahanBaku::where('production_batch_id', $productionBatchId)
                ->where('no_po', $noPo)
                ->delete();

            foreach ($request->products as $product) {
                BahanBaku::create([
                    'production_batch_id' => $productionBatchId,
                    'cost_center_name' => $costCenterName,
                    'no_po' => $noPo,
                    'material_id' => $product['material_id'],
                    'jumlah_bag' => $product['jumlah_bag'],
                    'jumlah_kg' => $product['jumlah_kg'],
                ]);
            }

            DB::commit();

            $url = route(
                'operator.bahan-baku.detail',
                $productionBatchId
            );

            if ($noPo !== null) {
                $url .= '?no_po=' . urlencode($noPo);
            }

            return redirect($url)
                ->with('success', 'Data bahan baku untuk PO berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data bahan baku.');
        }
    }

    public function destroy($id)
    {
        $bahanBaku = BahanBaku::findOrFail($id);

        $productionBatchId = $bahanBaku->production_batch_id;
        $noPo = $bahanBaku->no_po;

        $bahanBaku->delete();

        return redirect()->route('operator.bahan-baku.index')
            ->with('success', 'Data bahan baku berhasil dihapus.');
    }
}
