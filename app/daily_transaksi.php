<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class daily_transaksi extends Model
{
	# Model untuk setiap transaksi input daily formulasi per parameter layanan

    # daily_transaksi belongsTo
    public function layanan()
    {
    	return $this->belongsTo('App\layanan', 'id_layanan');
    }
    public function parameter()
    {
    	return $this->belongsTo('App\parameter', 'id_parameter');
    }
    public function formulasi()
    {
    	return $this->belongsTo('App\formulasi', 'id_formulasi');
    }
    public function user()
    {
    	return $this->belongsTo('App\User', 'user_input');
    }
}
