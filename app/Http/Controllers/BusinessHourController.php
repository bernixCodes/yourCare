<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BusinessHour;
use Illuminate\Http\Request;

class BusinessHourController extends Controller
{
    public function index(){
       return BusinessHour::all();
    }

    public function update(Request $request){
        $data = $request->all();

        foreach ($data as $businessHour) {
            BusinessHour::updateOrCreate(
                ['day' => $businessHour['day']], 
                [
                    'from' => $businessHour['from'],
                    'to' => $businessHour['to'],
                    'step' => $businessHour['step'],
                    'off' => $businessHour['off'],
                    'updated_at' => now()
                ]
            );
        }
    
        return response()->json(['success' => 'Business hours updated'], 200);
    }
}
