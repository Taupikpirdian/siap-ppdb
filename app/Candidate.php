<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = ['id', 'nama_siswa', 'tgl_lahir', 'sekolah_id', 'thn_id', 'program_id', 'payment_id', 'cost_id'
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
