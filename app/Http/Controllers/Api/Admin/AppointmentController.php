<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Models\Appointment;
use App\Transformers\AppointmentTransformer;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $skip = $request->skip? $request->skip : 0;
        $take = $request->take? $request->take : 10;
        $appointments = Appointment::query()->when($request->doctor_id,function($q) use($request){
          return $q->where('doctor_id',$request->doctor_id);
        })->skip($skip)
          ->take($take)
          ->get();

        $appointments = fractal()
            ->collection($appointments)
            ->transformWith(new AppointmentTransformer())
            ->toArray();

        return $this->dataResponse($appointments, 'all appointments', 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AppointmentRequest $request)
    {
        $data = $request->validated();

        $appointment = Appointment::create([
            'appointment_date'  =>$data['date'],
            'appointment_time'  =>$data['time'],
            'doctor_id'         =>$data['doctor_id']
        ]);
        return $this->dataResponse(null,__('stored successfully'),200);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AppointmentRequest $request, string $id)
    {
                
         $data = $request->validated();
         $appointment = Appointment::findOrFail($id);
         $appointment->update([
            'appointment_date'  =>$data['date'],
            'appointment_time'  =>$data['time'],
            'doctor_id'         =>$data['doctor_id']
         ]);

         return $this->dataResponse(null,__('updated successfully'),200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $appointment = Appointment::findOrFail($id);

        if($appointment->delete()){

            return $this->dataResponse(null,__('deleted successfully'),200);
        }
        return $this->dataResponse(null,__('failed to delete'),500);
    }
}
