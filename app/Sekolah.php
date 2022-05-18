<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    protected $fillable = ['id', 'nama_sekolah'
	];
    
	public function tahun()
    {
        return $this->hasMany('App\Year', 'sekolah_id');
    }
}
