<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PembayaranSiswaAktif extends Model
{
    protected $table = "pembayaran_siswa_aktifs";
    protected $fillable = ['npm', 'tgl_bayar','sekolah_id', 'amount', 'ket', 'payment_id', 'semester'];
}
