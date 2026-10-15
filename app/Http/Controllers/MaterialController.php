<?php

namespace App\Http\Controllers;

use App\Imports\MaterialImport;
use App\Models\Material;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => ['nullable', 'string'],
            'size' => ['nullable', 'integer', 'min:1'],
        ]);

        $search = $request->search;
        $size = $request->size ?? 20;

        $query = Material::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('material_code', 'like', "%{$search}%")
                    ->orWhere('material_description', 'like', "%{$search}%");
            });
        }

        $materials = $query->orderBy('material_code')
            ->paginate($size)
            ->withQueryString();

        return view('pages.operator.material.index', compact([
            'materials',
            'search',
        ]));
    }

    public function create()
    {
        return view('pages.operator.material.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'material_code' => ['required', 'string', 'max:255'],
            'material_description' => ['required', 'string', 'max:255'],
        ]);

        Material::create([
            'material_code' => $request->material_code,
            'material_description' => $request->material_description,
        ]);

        return redirect()
            ->route('operator.material.index')
            ->with('success', 'Material berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $material = Material::findOrFail($id);

        return view('pages.operator.material.edit', compact('material'));
    }

     public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);

        $request->validate([
            'material_code' => [
                'required',
                'string',
                'max:255',
            ],
            'material_description' => ['required', 'string', 'max:255'],
        ]);

        $material->update([
            'material_code' => $request->material_code,
            'material_description' => $request->material_description,
        ]);

        return redirect()
            ->route('operator.material.index')
            ->with('success', 'Material berhasil diupdate.');
    }

    public function destroy($id)
    {
        $material = Material::findOrFail($id);

        $material->delete();

        return redirect()
            ->route('operator.material.index')
            ->with('success', 'Material berhasil dihapus.');
    }

    public function importPage()
    {
        return view('pages.operator.material.import');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        ]);

        try {
            Excel::import(new MaterialImport(), $request->file('file'));

            return redirect()
                ->route('operator.material.index')
                ->with('success', 'Data material berhasil diimport.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
