<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\ProductionBatch;
use App\Models\MetalDetector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MetalDetectorController extends Controller
{
    public function index(Request $request)
    {
        $query = MetalDetector::with(['productionBatch', 'material'])->latest();

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

        $metalDetectors = $query->paginate(10)->withQueryString();

        return view(
            'pages.operator.metal-detector.index',
            compact('metalDetectors')
        );
    }

    public function create()
    {
        $productions = ProductionBatch::latest('tanggal')
            ->latest('id')
            ->get();

        $materials = Material::orderBy('id')->get();

        return view(
            'pages.operator.metal-detector.create',
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
            'no_program' => ['nullable', 'string', 'max:255'],

            'fe_depan' => ['nullable', 'in:Terdeteksi,Tidak terdeteksi'],
            'fe_tengah' => ['nullable', 'in:Terdeteksi,Tidak terdeteksi'],
            'fe_belakang' => ['nullable', 'in:Terdeteksi,Tidak terdeteksi'],

            'non_fe_depan' => ['nullable', 'in:Terdeteksi,Tidak terdeteksi'],
            'non_fe_tengah' => ['nullable', 'in:Terdeteksi,Tidak terdeteksi'],
            'non_fe_belakang' => ['nullable', 'in:Terdeteksi,Tidak terdeteksi'],

            'sus_316_depan' => ['nullable', 'in:Terdeteksi,Tidak terdeteksi'],
            'sus_316_tengah' => ['nullable', 'in:Terdeteksi,Tidak terdeteksi'],
            'sus_316_belakang' => ['nullable', 'in:Terdeteksi,Tidak terdeteksi'],

            'in' => ['nullable', 'string', 'max:255'],
            'out' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ]);

        try {
            DB::transaction(function () use ($validated) {
                MetalDetector::create($validated);
            });

            return redirect()
                ->route('operator.metal-detector.index')
                ->with('success', 'Data metal detector berhasil disimpan.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data metal detector.');
        }
    }

    public function detail($id)
    {
        $metalDetector = MetalDetector::with(['productionBatch', 'material'])
            ->findOrFail($id);

        return view(
            'pages.operator.metal-detector.detail',
            compact('metalDetector')
        );
    }

    public function edit($id)
    {
        $metalDetector = MetalDetector::with(['productionBatch', 'material'])
            ->findOrFail($id);

        $productions = ProductionBatch::latest('tanggal')
            ->latest('id')
            ->get();

        $materials = Material::orderBy('id')->get();

        return view(
            'pages.operator.metal-detector.edit',
            compact('metalDetector', 'productions', 'materials')
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
            'no_program' => ['nullable', 'string', 'max:255'],

            'fe_depan' => ['nullable', 'in:Terdeteksi,Tidak terdeteksi'],
            'fe_tengah' => ['nullable', 'in:Terdeteksi,Tidak terdeteksi'],
            'fe_belakang' => ['nullable', 'in:Terdeteksi,Tidak terdeteksi'],

            'non_fe_depan' => ['nullable', 'in:Terdeteksi,Tidak terdeteksi'],
            'non_fe_tengah' => ['nullable', 'in:Terdeteksi,Tidak terdeteksi'],
            'non_fe_belakang' => ['nullable', 'in:Terdeteksi,Tidak terdeteksi'],

            'sus_316_depan' => ['nullable', 'in:Terdeteksi,Tidak terdeteksi'],
            'sus_316_tengah' => ['nullable', 'in:Terdeteksi,Tidak terdeteksi'],
            'sus_316_belakang' => ['nullable', 'in:Terdeteksi,Tidak terdeteksi'],

            'in' => ['nullable', 'string', 'max:255'],
            'out' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $metalDetector = MetalDetector::findOrFail($id);
        $metalDetector->update($validated);

        return redirect()
            ->route('operator.metal-detector.index')
            ->with('success', 'Data metal detector berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $metalDetector = MetalDetector::findOrFail($id);
        $metalDetector->delete();

        return redirect()
            ->route('operator.metal-detector.index')
            ->with('success', 'Data metal detector berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:metal_detectors,id',
            ],
        ]);

        MetalDetector::whereIn('id', $request->ids)->delete();

        return redirect()
            ->route('operator.metal-detector.index')
            ->with('success', 'Data metal detector yang dipilih berhasil dihapus.');
    }

    public function managerIndex(Request $request)
    {
        $query = MetalDetector::with(['productionBatch', 'material'])->latest();

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

        $metalDetectors = $query->paginate(10)->withQueryString();

        return view(
            'pages.manager.metal-detector.index',
            compact('metalDetectors')
        );
    }

    public function managerDetail($id)
    {
        $metalDetector = MetalDetector::with(['productionBatch', 'material'])
            ->findOrFail($id);

        return view(
            'pages.manager.metal-detector.detail',
            compact('metalDetector')
        );
    }
}
