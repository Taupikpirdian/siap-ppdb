<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    protected $fillable = ['id', 'name', 'sekolah_id', 'thn_id', 'program_id', 'payment_id'
	];

	public function payment()
	{
		return $this->belongsTo('App\PaymentType', 'payment_id', 'id');
	}

	public function candidate()
    {
        return $this->hasMany('App\Candidate', 'cost_id');
    }

    public function detailcandidate()
    {
        return $this->hasMany('App\DetailPaymentCandidate', 'cost_id');
    }

    public function siswa()
    {
        return $this->hasMany('App\Siswa', 'cost_id');
    }
}
