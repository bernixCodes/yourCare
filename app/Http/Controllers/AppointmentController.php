<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\BusinessHour;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

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
                // Use the accessor to get the time periods
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
                    // 'off' => $businessHours->off
                ];
            } else {
                $appointments[] = [
                    'day_name' => $dayName,
                    'date' => $date->format('d M'),
                    'full_date' => $date->format('Y-m-d'),
                    'available_hours' => [],
                    // 'off' => 1 // Assuming the day is off if no business hours are found
                ];
            }
        }

        return response()->json($appointments);
    }

    public function reserve(Request $request){
        dd($request->user());
        // dd($request->all());

        $data = $request->merge(['user_id' =>auth()->id()])->toArray();
        dd($data);
        Appointment::create($data);
        return response()->json(['msg'=> "You have successfully reserved at appointment"] );
    }
}
