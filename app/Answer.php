<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    public function question()
    {
        return $this->belongsTo(Question::class); 
    }

    public function user()
    {
        return $this->belongsTo(User::class); 
    }

    // Display the question in plain text
    public function getBodyHtmlAttribute()
    {
        return  \Parsedown::instance()->text($this->body); 
    }

    // Displays date question was posted 
    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans(); 
    }
    
    public static function boot()
    {
        parent::boot(); 

        static::created(function($answer){ 
            $answer->question->increment('answers_count');
            
        }); 
    }
}
