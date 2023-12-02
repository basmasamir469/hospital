<?php

namespace App\Jobs;

use App\Models\Token;
use App\Traits\SendNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CancelReservation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, SendNotification;
    private $reservations;
    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->reservations = $data;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach($this->reservations as $reservation)
        {
            $reservation->notifications()->create([
               'en'=>['title'=>'reservation is cancelled ','description'=>'we are sorry to reject your request'],
               'ar'=>['title'=>'تم الغاء الحجز','description'=>'ناسف لعدم قبول طلبك'],
               'user_id'      =>$reservation->patient_id,
           ]);    
        }

        // send notification to other reservations with the same appointment that are not accepted
        $canceled_patients = $this->reservations->pluck('patient_id')->toArray();   
        $android_tokens = Token::whereIn('user_id',$canceled_patients)->where('device_type','android')->pluck('token')->toArray();
        $ios_tokens = Token::whereIn('user_id',$canceled_patients)->where('device_type','ios')->pluck('token')->toArray();
        $cancel_data = [
            'title'      => 'reservation is cancelled',
            'body'       => 'we are sorry to reject your request',
        ];
        $this->sendNotification($ios_tokens,$cancel_data,'ios');
        $this->sendNotification($android_tokens,$cancel_data,'android');       
    }
}
