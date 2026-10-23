<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Material;
use App\Models\ProductionBatch;
use App\Models\Productivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Productivity::with(['productionBatch', 'material'])->latest();

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

        $productivities = $query->paginate(10)->withQueryString();

        return view(
            'pages.operator.productivity.index',
            compact('productivities')
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

    public function create()
    {
        $productions = ProductionBatch::latest('tanggal')
            ->latest('id')
            ->get();

        $materials = Material::orderBy('id')->get();

        return view(
            'pages.operator.productivity.create',
            compact('productions', 'materials')
        );
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
            'material_id' => [
                'required',
                'integer',
                'exists:materials,id',
            ],
            'worker_indirect' => ['required', 'integer', 'min:0'],
            'worker_direct' => ['required', 'integer', 'min:0'],
            'worker_total' => ['required', 'integer', 'min:0'],
            'total_weight' => ['required', 'numeric', 'min:0'],
            'total_time' => ['required', 'numeric', 'min:0'],
        ]);

        $productivityIndirect = ($request->total_time > 0 && $request->worker_total > 0)
            ? $request->total_weight / $request->total_time / $request->worker_total
            : 0;

        $productivityDirect = ($request->total_time > 0 && $request->worker_direct > 0)
            ? $request->total_weight / $request->total_time / $request->worker_direct
            : 0;

        try {
            DB::transaction(function () use ($request, $productivityIndirect, $productivityDirect) {
                Productivity::create([
                    'production_batch_id' => $request->production_batch_id,
                    'bahan_baku_id' => $request->bahan_baku_id,
                    'material_id' => $request->material_id,
                    'worker_indirect' => $request->worker_indirect,
                    'worker_direct' => $request->worker_direct,
                    'worker_total' => $request->worker_total,
                    'total_weight' => $request->total_weight,
                    'total_time' => $request->total_time,
                    'productivity_indirect' => $productivityIndirect,
                    'productivity_direct' => $productivityDirect,
                ]);
            });

            return redirect()
                ->route('operator.productivity.index')
                ->with('success', 'Data productivity berhasil disimpan.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data productivity.');
        }
    }

    public function detail($id)
    {
        $productivity = Productivity::with(['productionBatch', 'material'])
            ->findOrFail($id);

        return view(
            'pages.operator.productivity.detail',
            compact('productivity')
        );
    }

    public function edit($id)
    {
        $productivity = Productivity::with(['productionBatch', 'material'])
            ->findOrFail($id);

        $productions = ProductionBatch::latest('tanggal')
            ->latest('id')
            ->get();

        $materials = Material::orderBy('id')->get();

        return view(
            'pages.operator.productivity.edit',
            compact('productivity', 'productions', 'materials')
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
            'material_id' => [
                'required',
                'integer',
                'exists:materials,id',
            ],
            'worker_indirect' => ['required', 'integer', 'min:0'],
            'worker_direct' => ['required', 'integer', 'min:0'],
            'worker_total' => ['required', 'integer', 'min:0'],
            'total_weight' => ['required', 'numeric', 'min:0'],
            'total_time' => ['required', 'numeric', 'min:0'],
        ]);

        $productivityIndirect = ($request->total_time > 0 && $request->worker_total > 0)
            ? $request->total_weight / $request->total_time / $request->worker_total
            : 0;

        $productivityDirect = ($request->total_time > 0 && $request->worker_direct > 0)
            ? $request->total_weight / $request->total_time / $request->worker_direct
            : 0;

        $productivity = Productivity::findOrFail($id);
        $productivity->update([
            'production_batch_id' => $request->production_batch_id,
            'bahan_baku_id' => $request->bahan_baku_id,
            'material_id' => $request->material_id,
            'worker_indirect' => $request->worker_indirect,
            'worker_direct' => $request->worker_direct,
            'worker_total' => $request->worker_total,
            'total_weight' => $request->total_weight,
            'total_time' => $request->total_time,
            'productivity_indirect' => $productivityIndirect,
            'productivity_direct' => $productivityDirect,
        ]);

        return redirect()
            ->route('operator.productivity.index')
            ->with('success', 'Data productivity berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $productivity = Productivity::findOrFail($id);
        $productivity->delete();

        return redirect()
            ->route('operator.productivity.index')
            ->with('success', 'Data productivity berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:productivities,id',
            ],
        ]);

        Productivity::whereIn('id', $request->ids)->delete();

        return redirect()
            ->route('operator.productivity.index')
            ->with('success', 'Data productivity yang dipilih berhasil dihapus.');
    }

    public function managerIndex(Request $request)
    {
        $query = Productivity::with(['productionBatch', 'material'])->latest();

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

        $productivities = $query->paginate(10)->withQueryString();

        return view(
            'pages.manager.productivity.index',
            compact('productivities')
        );
    }

    public function managerDetail($id)
    {
        $productivity = Productivity::with(['productionBatch', 'material'])->findOrFail($id);

        return view(
            'pages.manager.productivity.detail',
            compact('productivity')
        );
    }
}