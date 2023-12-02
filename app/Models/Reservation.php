<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model 
{

    protected $table = 'reservations';
    public $timestamps = true;
    protected $guarded = [];

    Const CANCELLED    = 0;
    Const PENDING      = 1;
    Const ACCEPTED     = 2;
    Const COMPLETED    = 3;

    public function appointment()
    {
        return $this->belongsTo('App\Models\Appointment','appoinment_id');
    }

    public function doctor()
    {
        return $this->belongsTo('App\Models\User','doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo('App\Models\User','patient_id');
    }

    public function notifications()
    {
        return $this->hasMany('App\Models\Notification');
    }

    public function getReservationStatusAttribute()
    {
       if($this->attributes['status'] == self::ACCEPTED)
       {
        return 'Accepted';
       }
       if($this->attributes['status'] == self::PENDING)
       {
        return 'Pending';
       }
       if($this->attributes['status'] == self::CANCELLED)
       {
        return 'Cancelled';
       }
       if($this->attributes['status'] == self::COMPLETED)
       {
        return 'Completed';
       }
    }

    public function scopeFilterByHistory($q)
    {
        return $q->when(request('status'),function() use($q){
            if(request('status') == "current")
            {
                return $q->whereIn('status',[self::ACCEPTED,self::PENDING]);
            }
            if(request('status') == "history")
            {
                return $q->whereIn('status',[self::COMPLETED,self::CANCELLED]);
            }
        });
    }

    public function scopeFilterByStatus($q)
    {
        return $q->when(request('status'),function() use($q){
            if(request('status') == "pending")
            {
                return $q->where('status',self::PENDING)->whereDate('created_at',Carbon::today());
            }
            if(request('status') == "accepted")
            {
                return $q->where('status',self::ACCEPTED)->whereHas('appointment',function($query){
                    return $query->where('appointment_date',Carbon::today());
                });
            }
            if(request('status') == "history")
            {
                return $q->whereIn('status',[self::COMPLETED,self::CANCELLED]);
            }
        });
    }

}