<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;


class Notification extends Model implements TranslatableContract
{
    use Translatable;
    protected $table = 'notifications';
    public $translatedAttributes = ['title','description']; 
    public $timestamps = true;
    protected $guarded = [];


    public function reservation()
    {
        return $this->belongsTo('App\Models\Reservation');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}