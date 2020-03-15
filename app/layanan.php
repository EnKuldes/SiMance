<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class layanan extends Model
{
	# Layanan hasMany Users 
    public function user()
    {
        return $this->hasMany('App\User', 'layanan', 'id');
    }
    # Layanan hasMany Parameters
    public function parameter()
    {
        return $this->hasMany('App\parameter', 'id_layanan', 'id');
    }

}
