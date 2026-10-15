<?php

namespace App\Http\Controllers;

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
        $validated = $request->validate([
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
            'worker_indirect' => ['required', 'integer', 'min:0'],
            'worker_direct' => ['required', 'integer', 'min:0'],
            'worker_total' => ['required', 'integer', 'min:0'],
            'total_weight' => ['required', 'numeric', 'min:0'],
            'total_time' => ['required', 'numeric', 'min:0'],
            'productivity_indirect' => ['required', 'numeric', 'min:0'],
            'productivity_direct' => ['required', 'numeric', 'min:0'],
        ]);

        try {
            DB::transaction(function () use ($validated) {
                Productivity::create($validated);
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
        $validated = $request->validate([
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
            'worker_indirect' => ['required', 'integer', 'min:0'],
            'worker_direct' => ['required', 'integer', 'min:0'],
            'worker_total' => ['required', 'integer', 'min:0'],
            'total_weight' => ['required', 'numeric', 'min:0'],
            'total_time' => ['required', 'numeric', 'min:0'],
            'productivity_indirect' => ['required', 'numeric', 'min:0'],
            'productivity_direct' => ['required', 'numeric', 'min:0'],
        ]);

        $productivity = Productivity::findOrFail($id);
        $productivity->update($validated);

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
