<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileQuestionnaire extends Model
{
    use HasFactory; use SoftDeletes;

    protected $guarded = [];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function questionnaire()
    {
        $this->morphTo('questionnaire', 'questionnaire_type', 'questionnaire_id', 'id');
    }
}
