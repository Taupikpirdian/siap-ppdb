<?php

namespace App\Imports;

use App\Approval;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SppImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Approval([
            'no_urut' => $row['no_urut'],
            'npm' => $row['npm'],
            'tgl_bayar' => $row['tgl_bayar'],
            'sekolah_id' => $row['sekolah_id'], 
            'amount' => $row['amount'], 
            'ket' => $row['ket'], 
            'payment_id' => $row['payment_id'], 
            'semester' => $row['semester'], 
        ]);
    }
}
