<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class kpi extends Model
{
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
