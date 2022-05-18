<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $fillable = ['id', 'npm', 'rfid', 'nama_siswa', 'tempat_lahir', 'tgl_lahir', 'kelas', 'subkelas', 'nama_ayah', 'hp_ayah', 'nama_ibu', 'hp_ibu', 'nama_wali', 'hp_wali', 'alamat', 'kecamatan', 'kota_kab', 'status', 'foto', 'candidate_id', 'semester', 'sekolah_id', 'thn_id', 'program_id', 'payment_id', 'cost_id',
	];

	public function sekolah()
	{
		return $this->belongsTo('App\Sekolah', 'sekolah_id', 'id');
	}

	public function tahun()
	{
		return $this->belongsTo('App\Year', 'thn_id', 'id');
	}

	public function program()
	{
		return $this->belongsTo('App\Program', 'program_id', 'id');
	}

	public function jenisbayar()
	{
		return $this->belongsTo('App\PaymentType', 'payment_id', 'id');
	}

	public function biaya()
	{
		return $this->belongsTo('App\Cost', 'cost_id', 'id');
	}
}
