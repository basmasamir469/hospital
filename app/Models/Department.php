<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;


class Department extends Model implements TranslatableContract
{
    use Translatable;
    protected $table = 'departments';
    public $translatedAttributes = ['name']; 
    public $timestamps = true;
    protected $guarded = [];


    public function doctors()
    {
        return $this->hasMany('App\Models\User');
    }

}