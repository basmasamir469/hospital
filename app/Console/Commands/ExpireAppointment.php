<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireAppointment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-appointment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Appointment::where('appointment_date','<',Carbon::today())
        ->update(['appointment_status'=>Appointment::EXPIRED]);
    }
}
