<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Models\User;
use App\Transformers\DoctorTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $skip = $request->skip? $request->skip : 0;
        $take = $request->take? $request->take : 10;
        $doctors = User::whereHas('roles',function($q){
            return $q->where('name','doctor');
        })->skip($skip)
          ->take($take)
          ->get();

        $doctors = fractal()
            ->collection($doctors)
            ->transformWith(new DoctorTransformer())
            ->toArray();

        return $this->dataResponse($doctors, 'all doctors', 200);
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
    public function store(RegisterRequest $request)
    {
        $data = $request->validated();

        $doctor = User::create([
            'name'         =>$data['name'],
            'email'        =>$data['email'],
            'mobile'       =>$data['email'],
            'password'     =>Hash::make($data['password']),
            'department_id'=>$data['department_id']
        ]);
        $role = Role::where('name','doctor')->first();
        $doctor->assignRole($role);
        $doctor->addMedia($data['image'])
               ->toMediaCollection('users-images');
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
    public function update(UpdateDoctorRequest $request, string $id)
    {
        //
        $data = $request->validated();
        $doctor = User::findOrFail($id);
        $doctor->update([
            'name'         =>$data['name'],
            'email'        =>$data['email'],
            'mobile'       =>$data['email'],
            'department_id'=>$data['department_id']
        ]);
          try{
          $doctor->clearMediaCollection('users-images');
          $doctor->addMedia($data['image'])
          ->toMediaCollection('users-images');
        }
        catch(\Exception $e){
            
            return $this->dataResponse(null,__('failed to update'),500);
        }
            return $this->dataResponse(null,__('updated successfully'),200);
          
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $doctor = User::findOrFail($id);

        if($doctor->delete() && $doctor->clearMediaCollection('users-images')){

            return $this->dataResponse(null,__('deleted successfully'),200);
        }
        return $this->dataResponse(null,__('failed to delete'),500);
    }
}
