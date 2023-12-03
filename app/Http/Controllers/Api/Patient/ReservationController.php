<?php

namespace App\Http\Controllers\Api\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Reservation;
use App\Models\Token;
use App\Transformers\AppointmentTransformer;
use App\Transformers\ReservationTransformer;
use App\Transformers\NotificationTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function appointments(Request $request)
    {
        // list appointments according to selected doctor
         $appointments = Appointment::query();
         $appointments = $appointments->when(request('doctor_id'),function($q){
                         return $q->whereHas('doctor',function($query){
                             return $query->where('id',request('doctor_id'));
                         });
         })->get();
         $appointments = fractal()
                        ->collection($appointments)
                        ->transformWith(new AppointmentTransformer)
                        ->toArray();
        return $this->dataResponse($appointments,'appointments according to selected doctor',200);
    }

    public function bookAppointment(Request $request,$appointment_id)
    {
        // book selected appointment
        $appointment = Appointment::findOrFail($appointment_id);
     if($appointment->appointment_status == Appointment::AVAILABLE)
      {
        DB::beginTransaction();
        $reservation = Reservation::create([
         'doctor_id'    =>$appointment->doctor_id,
         'patient_id'   =>$request->user()->id,
         'appoinment_id'=>$appointment_id
        ]);
        // send notification to the doctor of appointment 
        $notification = $reservation->notifications()->create([
            'en'=>['title'=>'New reservation','description'=>'the patient '.$request->user()->name.'wants to book this appointment'],
            'ar'=>['title'=>'حجز جديد','description'=>' يريد ان يحجز هذا الموعد'.$request->user()->name.' المريض'],
            'user_id'      =>$appointment->doctor_id,
        ]);
        DB::commit();
        $token = Token::where('user_id',$reservation->doctor_id)->first();
        $data = [
            'title'      => $notification->title,
            'body'       => $notification->description,
            'action_id'  => $reservation->id,
            'action_type'=>'new-reservation'
        ];
        $this->notifyByFirebase($token?->token,$data,$token?->device_type);
        return $this->dataResponse(null,__('your request is sent successfully please wait for confirmation'),200);
      }
      return $this->dataResponse(null,__('failed to book this appointment it is already reserved'),422);
    }

    public function reservations(Request $request)
    {
        // list reservations of auth patient according to history 
        $skip = $request->skip? $request->skip : 0;
        $take = $request->take? $request->take : 10;
        $reservations = Reservation::where('patient_id',$request->user()->id)
                       ->filterByHistory()
                       ->skip($skip)->take($take)->get();
        $reservations = fractal()
         ->collection($reservations)
         ->transformWith(new ReservationTransformer())
         ->toArray();
         return $this->dataResponse($reservations,__('reservations list'),200);
    }

    public function notifications(Request $request)
    {
        // get notifications of auth user
        $skip          = $request->skip? $request->skip : 0;
        $take          = $request->take? $request->take : 10;
        $notifications = $request->user()->notifications()
                         ->skip($skip)
                         ->take($take)
                         ->latest()
                         ->get();
        $notifications   = fractal()
        ->collection($notifications)
        ->transformWith(new NotificationTransformer())
        ->toArray();           
        return $this->dataResponse($notifications,'your notifications',200);      
                 
    }
}
