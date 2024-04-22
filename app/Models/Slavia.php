<?php

namespace App\Models;

use App\Models\Traits\GoogleSheets;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Revolution\Google\Sheets\Facades\Sheets;
use Illuminate\Support\Str;
use PhpParser\ErrorHandler\Collecting;
use Sushi\Sushi;

class Slavia extends Model
{
    use Sushi, GoogleSheets;

    public function getRows()
    {
        return $this->getRowsFromGoogleSheet();
    }

    public static function filterByRequest($request)
    {
        return self::where('month', $request->count_of_month)
            ->get()
            ->filter(function ($price) use ($request) {
                $birth   = Carbon::parse($request->birth);

                $min_age = self::calculateAge($request->start_date, $price->min_age);
                $max_age = self::calculateAge($request->start_date, $price->max_age)->subYear(1);

                return $birth->lessThanOrEqualTo($min_age) && $birth->greaterThanOrEqualTo($max_age) ;
            })
            ->when($request->type == 'standart', function(Collection $collection){
                return $collection->filter(function ($price) {
                    return $price->student == false AND $price->pregnant == false AND $price->sport == false;
                });
            })
            ->when($request->type == 'student', function(Collection $collection){
                return $collection->filter(function ($price) {
                    return ($price->student == true OR $price->student == false) AND $price->pregnant == false AND $price->sport == false;
                });
            })
            ->when($request->type == 'pregnant', function(Collection $collection){
                return $collection->filter(function ($price) {
                    return $price->pregnant == true;
                });
            })
            ->when($request->type == 'sport', function(Collection $collection){
                return $collection->filter(function ($price) {
                    return $price->sport == true;
                });
            })
            ->when($request->shengen, function(Collection $collection){
                return $collection->filter(function ($price) {
                    return $price->shengen == true;
                });
            });
    }
}
