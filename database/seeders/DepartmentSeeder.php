<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->delete();
        $departments_ar = [' قلب و أوعية دموية	',' هضمية، و مناظير	','نسائية وتوليد','طب العيون','اختصاص أطفال','طب الأعصاب','اختصاص أمراض الكلى'];
        $departments_en = ['Cardiology ','Gastroenterology','Obstetrics and gynecology','Ophthalmology','Pediatrics','neurology','nephrology'];
        foreach($departments_ar as $key => $department)
        {
            Department::create([
              'en'=>['name'=>$departments_en[$key]],
              'ar'=>['name'=>$departments_ar[$key]]
            ]);
        }

    }
}
