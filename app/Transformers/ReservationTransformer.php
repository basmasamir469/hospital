<?php

namespace App\Transformers;

use App\Models\Reservation;
use League\Fractal\TransformerAbstract;

class ReservationTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Reservation $reservation)
    {
        return [
            'id'     => $reservation->id,
            'doctor' => $reservation->doctor?->name,
            'patient'=> $reservation->patient?->name,
            'reservation_date'=>$reservation->appointment?->appointment_date,
            'time'=>$reservation->appointment?->appointment_time,
            'reservation_status'=>$reservation->reservation_status
        ];
    }
}
