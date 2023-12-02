<?php

namespace App\Http\Controllers\Api\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Reservation;
use App\Models\Token;
use App\Transformers\NotificationTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function acceptReservation(Request $request,$reservation_id)
    {
        $reservation = Reservation::findOrFail($reservation_id);
      if($reservation->status == Reservation::PENDING && $reservation->doctor_id == $request->user()->id)
      {
        DB::beginTransaction();
        $reservation->update([
          'status'=>Reservation::ACCEPTED
        ]);
        Appointment::findOrFail($reservation->appoinment_id)->update([
            'appointment_status' => Appointment::RESERVED
        ]);
        // old reservations that not accepted will be cancelled
        Reservation::whereNot('id',$reservation->id)->where('appoinment_id',$reservation->appoinment_id)->update(['status'=>Reservation::CANCELLED]);

        $confirm_notification = $reservation->notifications()->create([
            'en'=>['title'=>'reservation is confirmed ','description'=>'your reservation is accepted successfully!'],
            'ar'=>['title'=>'تم تاكيد الحجز','description'=>'تم قبول حجزك بنجاح'],
            'user_id'      =>$reservation->patient_id,
        ]);
        $canceled_reservations = Reservation::where('id','!=',$reservation->id)->where('appoinment_id',$reservation->appoinment_id)->get();
        $canceled_reservations->map(function($reservation){
             $reservation->notifications()->create([
                'en'=>['title'=>'reservation is cancelled ','description'=>'we are sorry to reject your request'],
                'ar'=>['title'=>'تم الغاء الحجز','description'=>'ناسف لعدم قبول طلبك'],
                'user_id'      =>$reservation->patient_id,
            ]);    
        });
        DB::commit();
        // send notification that reservation is confirmed
        $token  = Token::where('user_id',$reservation->patient_id)->first();
        $confirm_data = [
            'title'      => $confirm_notification->title,
            'body'       => $confirm_notification->description,
        ];
        $this->notifyByFirebase($token?->token,$confirm_data,$token?->device_type);
        
        // send notification to other reservations with the same appointment that are not accepted
         $canceled_patients = $canceled_reservations->pluck('patient_id')->toArray();

        $android_tokens = Token::whereIn('user_id',$canceled_patients)->where('device_type','android')->pluck('token')->toArray();
        $ios_tokens = Token::whereIn('user_id',$canceled_patients)->where('device_type','ios')->pluck('token')->toArray();
        $cancel_data = [
            'title'      => 'reservation is cancelled',
            'body'       => 'we are sorry to reject your request',
        ];
        $this->sendNotification($ios_tokens,$cancel_data,'ios');
        $this->sendNotification($android_tokens,$cancel_data,'android');
        return $this->dataResponse(null,__('your request is confirmed successfully'),200);
      }
      return $this->dataResponse(null,__('cannot confirm request'),200);
        
    }

    public function rejectReservation(Request $request,$reservation_id)
    {
        $reservation = Reservation::findOrFail($reservation_id);
      if($reservation->status == Reservation::PENDING)
      {
        DB::beginTransaction();
        $reservation->update([
          'status'=>Reservation::CANCELLED
        ]);
        $notification = $reservation->notifications()->create([
            'en'=>['title'=>'reservation is cancelled ','description'=>'your reservation is cancelled'],
            'ar'=>['title'=>'تم الغاء الحجز','description'=>'ناسف لعدم قبول طلبك'],
            'user_id'      =>$reservation->patient_id,
        ]);
        DB::commit();
        $token  = Token::where('user_id',$reservation->patient_id)->first();
        $data = [
            'title'      => $notification->title,
            'body'       => $notification->description,
        ];
        $this->notifyByFirebase($token?->token,$data,$token?->device_type);
        return $this->dataResponse(null,__('your request is cancelled'),200);
      }
      return $this->dataResponse(null,__('can not cancel the request'),200);

    }

    public function notifications(Request $request)
    {
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
