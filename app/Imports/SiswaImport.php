<?php

namespace App\Imports;

use App\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Siswa([
            'npm' => $row['npm'],
            'rfid' => $row['rfid'],
            'nama_siswa' => $row['nama_siswa'], 
            'tempat_lahir' => $row['tempat_lahir'], 
            'tgl_lahir' => $row['tgl_lahir'], 
            'kelas' => $row['kelas'], 
            'subkelas' => $row['subkelas'], 
            'nama_ayah' => $row['nama_ayah'], 
            'hp_ayah' => $row['hp_ayah'], 
            'nama_ibu' => $row['nama_ibu'], 
            'hp_ibu' => $row['hp_ibu'], 
            'nama_wali' => $row['nama_wali'], 
            'hp_wali' => $row['hp_wali'], 
            'alamat' => $row['alamat'], 
            'kecamatan' => $row['kecamatan'], 
            'status' => $row['status'], 
            'semester' => $row['semester'], 
            'sekolah_id' => $row['sekolah_id'], 
            'thn_id' => $row['thn_id'], 
            'program_id' => $row['program_id'], 
        ]);
    }
}
