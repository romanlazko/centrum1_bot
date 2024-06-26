<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Revolution\Google\Sheets\Facades\Sheets;

trait GoogleSheets
{
    public function getRowsFromGoogleSheet(): array
    {
        $rows = Sheets::spreadsheet(env('GOOGLE_SPREADSHEET_ID'))->sheet(class_basename(__CLASS__))->get();

        $header = $rows->pull(0);

        return Sheets::collection(header: $header, rows: $rows)->values()->toArray();
    }

    protected static function calculateAge(string $start_date, string $ageString): Carbon
    {
        if (str_contains($ageString, 'year')) {
            return Carbon::parse($start_date)->subYears((int)filter_var($ageString, FILTER_SANITIZE_NUMBER_INT));
        }
        if (str_contains($ageString, 'days')) {
            return Carbon::parse($start_date)->subDays((int)filter_var($ageString, FILTER_SANITIZE_NUMBER_INT));
        }
    }

    public function getShengenAttribute(): mixed
    {
        if ($this->attributes['shengen'] == "TRUE") {
            return true;
        }

        return false;
    }

    public function getStudentAttribute(): mixed
    {
        if ($this->attributes['student'] == "TRUE") {
            return true;
        }

        return false;
    }

    public function getPregnantAttribute(): mixed
    {
        if ($this->attributes['pregnant'] == "TRUE") {
            return true;
        }

        return false;
    }

    public function getSportAttribute(): mixed
    {
        if ($this->attributes['sport'] == "TRUE") {
            return true;
        }

        return false;
    }
}