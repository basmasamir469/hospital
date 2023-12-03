<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model 
{

    protected $table = 'appointments';
    public $timestamps = true;
    protected $guarded = [];

    Const RESERVED   = 0;
    Const AVAILABLE  = 1;
    Const EXPIRED    = 2;
    
    public function doctor()
    {
        return $this->belongsTo('App\Models\User','doctor_id');
    }

    public function reservation()
    {
        return $this->hasOne('App\Models\Reservation');
    }

    public function getStatusAttribute()
    {
       if($this->attributes['appointment_status'] == self::AVAILABLE)
       {
        return 'Available';
       }
       if($this->attributes['appointment_status'] == self::RESERVED)
       {
        return 'Reserved';
       }
    }

}