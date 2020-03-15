<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class formulasi extends Model
{
    # Fprmulasi id_parameter belongsTo Parameter
    public function parameter()
    {
    	return $this->belongsTo('App\parameter', 'id_parameter');
    }
}
