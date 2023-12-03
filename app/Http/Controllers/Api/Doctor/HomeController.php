<?php

namespace App\Http\Controllers\Api\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Transformers\ReservationTransformer;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function reservations(Request $request)
    {
        // get list reservations of auth doctor according to selected status
        $skip = $request->skip? $request->skip : 0;
        $take = $request->take? $request->take : 10;
        $reservations = Reservation::where('doctor_id',$request->user()->id)
                       ->filterByStatus()
                       ->skip($skip)->take($take)->get();
        $reservations = fractal()
         ->collection($reservations)
         ->transformWith(new ReservationTransformer())
         ->toArray();
         return $this->dataResponse($reservations,__('reservations list'),200);

    }
}
