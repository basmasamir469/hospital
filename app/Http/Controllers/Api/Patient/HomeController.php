<?php

namespace App\Http\Controllers\Api\Patient;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use App\Transformers\DepartmentTransformer;
use App\Transformers\DoctorTransformer;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function departments(Request $request)
    {
        $skip          = $request->skip? $request->skip : 0;
        $take          = $request->take? $request->take : 10;
        $departments   = Department::skip($skip)
                                    ->take($take)
                                    ->get();
        $count = Department::all()->count();
        $departments   = fractal()
        ->collection($departments)
        ->transformWith(new DepartmentTransformer())
        ->parseIncludes('doctors')
        ->toArray();
        
        return $this->dataResponse(['departments'=>$departments,'count'=>$count],'all departments',200);
    }

    public function doctors(Request $request)
    {
        $skip    = $request->skip? $request->skip : 0;
        $take    = $request->take? $request->take : 10;
        $doctors = User::whereHas('roles',function($q){
                         return $q->where('name','doctor');
                 })->when(request('department_id'),function($q){
                    return $q->whereHas('department',function($query){
                           return $query->Where('id',request('department_id'));
                    });
                });
        $count   = $doctors->count();
        $doctors = $doctors->skip($skip)
                   ->take($take)
                   ->get();

        $doctors   = fractal()
        ->collection($doctors)
        ->transformWith(new DoctorTransformer())
        ->toArray();           
        return $this->dataResponse(['doctors'=>$doctors,'count'=>$count],'doctors that belong to this department',200);      
    }

}
