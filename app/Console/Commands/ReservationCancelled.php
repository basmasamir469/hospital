<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReservationCancelled extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reservation-cancelled';

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
        Reservation::where('status',Reservation::PENDING)->whereHas('appointment',function($q){
          return $q->where('appointment_date','<',Carbon::today());
        })->update(['status'=>Reservation::CANCELLED]);
    }
}
