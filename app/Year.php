<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    protected $fillable = ['id','sekolah_id','tahun'
	];

	public function sekolah()
	{
		return $this->belongsTo('App\Sekolah', 'sekolah_id', 'id');
	}

	public function program()
    {
        return $this->hasMany('App\Program', 'thn_id');
    }
}
