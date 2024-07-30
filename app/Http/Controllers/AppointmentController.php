<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\BusinessHour;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationConfirmed;

class AppointmentController extends Controller
{
    public function index()
    {
        $datePeriod = CarbonPeriod::create(now(), now()->addDays(6));
        $appointments = [];

        foreach ($datePeriod as $date) {
            $dayName = $date->format('l');
            $businessHours = BusinessHour::where('day', $dayName)->first();

            if ($businessHours) {
                $hours = $businessHours->times_period;

                $currentAppointments = Appointment::where('date', $date->toDateString())
                    ->pluck('time')
                    ->map(function ($time) {
                        return Carbon::parse($time)->format('H:i');
                    })->toArray();

                $availableHours = array_diff($hours, $currentAppointments);

                $appointments[] = [
                    'day_name' => $dayName,
                    'date' => $date->format('d M'),
                    'full_date' => $date->format('Y-m-d'),
                    'available_hours' => $availableHours,
                    'curent_appointments' => $currentAppointments
                    // 'off' => $businessHours->off
                ];
            } else {
                $appointments[] = [
                    'day_name' => $dayName,
                    'date' => $date->format('d M'),
                    'full_date' => $date->format('Y-m-d'),
                    'available_hours' => [],
                    // 'off' => 1 
                ];
            }
        }

        return response()->json($appointments);
    }

    public function reserve(Request $request){
        $data = $request->merge(['user_id' =>auth()->id()])->toArray();
        $appointment = Appointment::create($data);

        $emailData = [
            'user_name' => auth()->user()->name,
            'user_id' => auth()->user()->id,
            'date' => $appointment->date,
            'time' => $appointment->time,
            "appointment_id"=>$appointment->id,
            'location' => "https://meet.google.com/wtp-asxr-acv",
            'payment_link' => route('payment', ['appointment_id' => $appointment->id]),
        ];

        Mail::to(auth()->user()->email)->send(new ReservationConfirmed($emailData));
        return response()->json(['msg'=> "Reservation made. Please check your email to complete payment."] );
    }
}
