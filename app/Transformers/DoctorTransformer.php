<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class DoctorTransformer extends TransformerAbstract
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
    public function transform(User $doctor)
    {
        return [
            'id'           => $doctor->id,
            'name'         => $doctor->name,
            'mobile'       => $doctor->mobile,
            'email'        => $doctor->email
        ];
    }
}
