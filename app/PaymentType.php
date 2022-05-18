<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
	protected $fillable = ['id', 'name', 'sekolah_id', 'thn_id', 'program_id'
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

	public function cost()
	{
	    return $this->hasMany('App\Cost', 'payment_id');
	}
}
