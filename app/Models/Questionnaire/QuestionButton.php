<?php

namespace App\Models\Questionnaire;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionButton extends Model
{
    use HasFactory; use SoftDeletes;

    protected $guarded = [];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function questionnaire()
    {
        return $this->hasOneThrough(Questionnaire::class, Question::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
