<?php

namespace App\Transformers;

use App\Models\Appointment;
use League\Fractal\TransformerAbstract;

class AppointmentTransformer extends TransformerAbstract
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
    public function transform(Appointment $appointment)
    {
        return [
            'id'                 => $appointment->id,
            'appointment_time'   => $appointment->appointment_time,
            'appointment_date'   => $appointment->appointment_date,
            'appointment_status' => $appointment->status,
        ];
    }
}
