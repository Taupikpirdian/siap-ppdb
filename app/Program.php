<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
	protected $fillable = ['id','sekolah_id','nama','thn_id'
	];

	public function Sekolah()
	{
		return $this->belongsTo('App\Year', 'thn_id', 'id');
	}

	public function payment()
	{
		return $this->hasMany('App\PaymentType', 'program_id');
	}
}
