<?php

namespace App\Imports;

use App\Models\Material;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MaterialImport implements ToCollection, WithHeadingRow
{
    public function headingRow(): int
    {
        return 3;
    }

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            throw new \Exception('File Excel kosong.');
        }

        $requiredHeaders = [
            'material_code',
            'material_description',
        ];

        $headers = array_keys($rows->first()->toArray());

        foreach ($requiredHeaders as $header) {
            if (!in_array($header, $headers)) {
                throw new \Exception(
                    "Format Excel tidak sesuai. Kolom '{$header}' tidak ditemukan."
                );
            }
        }

        foreach ($rows as $row) {
            $materialCode = trim((string) ($row['material_code'] ?? ''));
            $materialDescription = trim((string) ($row['material_description'] ?? ''));

            if (empty($materialCode) && empty($materialDescription)) {
                continue;
            }

            if (empty($materialCode)) {
                throw new \Exception(
                    'material_code tidak boleh kosong.'
                );
            }

            if (empty($materialDescription)) {
                throw new \Exception(
                    "material_description tidak boleh kosong untuk material code '{$materialCode}'."
                );
            }

            Material::updateOrCreate(
                [
                    'material_code' => $materialCode,
                ],
                [
                    'material_description' => $materialDescription,
                ]
            );
        }
    }
}