<?php

namespace App\Imports;

use App\Models\Rango;
use Maatwebsite\Excel\Concerns\ToModel;

class RangoImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Rango([
            'desde' => $row[0],
            'hasta' => $row[1],
            'incremento' => $row[2],
        ]);
    }
}
