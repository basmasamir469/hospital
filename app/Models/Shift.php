<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model 
{

    protected $table = 'shifts';
    public $timestamps = true;
    protected $guarded = [];


    public function doctors()
    {
        return $this->belongsToMany('App\Models\User','user_shift');
    }

}