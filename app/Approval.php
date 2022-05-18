<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $fillable = ['no_urut', 'npm', 'tgl_bayar','sekolah_id', 'amount', 'ket', 'payment_id', 'semester'];    
}
