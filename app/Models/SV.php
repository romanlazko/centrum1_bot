<?php

namespace App\Models;

use App\Models\Traits\GoogleSheets;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class SV extends Model
{
    use Sushi, GoogleSheets;

    public function getRows()
    {
        return $this->getRowsFromGoogleSheet();
    }

    public static function filterByAge(Carbon $birth, int $month = 12, $isStudent = false)
    {
        return self::where('month', $month)
            ->get()
            ->filter(function ($price) use ($birth) {
                $birth      = $birth->diff(now())->y;
                $min_age    = self::calculateAge($price->min_age)->diff(now())->y;
                $max_age    = self::calculateAge($price->max_age)->diff(now())->y;

                return $birth >= $min_age && $birth <= $max_age;
            });
    }
}
