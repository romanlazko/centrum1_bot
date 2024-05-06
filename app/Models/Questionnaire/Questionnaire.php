<?php

namespace App\Models\Questionnaire;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Questionnaire extends Model
{
    use HasFactory; use SoftDeletes;

    protected $guarded = [];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function getNextQuestion($current_question_id)
    {
        return $this->questions()->where('id', '>', $current_question_id)->first();
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
