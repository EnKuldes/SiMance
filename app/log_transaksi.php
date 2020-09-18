<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class log_transaksi extends Model
{
	# Berubah dari Log Transkasi menjadi Log Transaksi Verifikasi
    protected $table = 'log_transaksis_justifikasi';

    # daily_transaksi belongsTo
    public function layanan()
    {
    	return $this->belongsTo('App\layanan', 'id_layanan');
    }
    public function parameter()
    {
    	return $this->belongsTo('App\parameter', 'id_parameter');
    }
}
