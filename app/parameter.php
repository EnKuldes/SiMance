<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class parameter extends Model
{
    # Parameter hasMany formulasis
    public function formulasi()
    {
        return $this->hasMany('App\formulasi', 'id_parameter', 'id');
    }
	# Parameter id_layanan belongsTo Layanan
    public function layanan()
    {
    	return $this->belongsTo('App\layanan', 'id_layanan');
    }
}
