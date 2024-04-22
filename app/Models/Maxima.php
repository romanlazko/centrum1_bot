<?php

namespace App\Models;

use App\Models\Traits\GoogleSheets;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Sushi\Sushi;

class Maxima extends Model
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
                $birth = Carbon::parse($request->birth)->diff(Carbon::parse($request->start_date))->y;

                return $birth >= $price->min_age AND $birth <= $price->max_age;
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

    public function getMaxAgeAttribute()
    {
        return (int)filter_var($this->attributes['max_age'], FILTER_SANITIZE_NUMBER_INT);
    }

    public function getMinAgeAttribute()
    {
        return (int)filter_var($this->attributes['min_age'], FILTER_SANITIZE_NUMBER_INT);
    }
}
