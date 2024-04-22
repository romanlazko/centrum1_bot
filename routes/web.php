<?php

use App\Models\Colonnade;
use App\Models\Maxima;
use App\Models\Slavia;
use App\Models\SV;
use App\Models\VZP;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Revolution\Google\Sheets\Facades\Sheets;

Route::get('/', function (Request $request) {
    $insurances = null;
    if ($request->filled(['birth'])) {

        dump($request->start_date);

        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);
        $count_of_month = ceil($start_date->diffInMonths($end_date));

        $data = (object)[
            'type' => $request->type,
            'shengen' => $request->shengen,
            'start_date' => $request->start_date,
            'end_date'  => $request->end_date,
            'count_of_month' => $count_of_month,
            'birth' => $request->birth,
        ];
        $collections = collect([
            Slavia::filterByRequest($data),
            Maxima::filterByRequest($data),
            Colonnade::filterByRequest($data),
            VZP::filterByRequest($data),
        ]);
    
        $insurances = $collections->flatten()->sortBy('price');
    }
    
    return view('welcome', [
        'insurances' => $insurances,
        'data' => $data
    ]);
})->name('welcome');