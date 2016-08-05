<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
    	'name',
    	'email',
    	'company',
    	'address',
    	'phone',
    	'group_id',
        'photo'
    ];

    public function group()
    {
    	return $this->belongsTo('App\Group');
    }
}
