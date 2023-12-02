<?php

namespace App\Transformers;

use App\Models\Department;
use League\Fractal\TransformerAbstract;

class DepartmentTransformer extends TransformerAbstract
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
        'doctors'
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Department $department)
    {
        return [
            'id'        => $department->id,
            'name'      => $department->name,
        ];
    }

    public function includeDoctors(Department $department)
    {
       $doctors = $department->doctors;

       return $this->collection($doctors, new DoctorTransformer()); 
    }
}
