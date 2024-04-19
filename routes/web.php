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
    
    // dd($request->month);
    if ($request->filled(['birth', 'count_of_month'])) {
        $collections = collect([
            Slavia::filterByRequest($request),

            Maxima::filterByRequest($request),
            Colonnade::filterByRequest($request),
            // SV::filterByAge(Carbon::parse($request->birth), $request->month, $request->is_student),
            VZP::filterByRequest($request),
        ]);
    
        $insurances = $collections->flatten()->sortBy('price');
    }
    
    return view('welcome', [
        'birth' => optional(Carbon::parse($request->birth)),
        'insurances' => $insurances,
    ]);
})->name('welcome');