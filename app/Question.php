<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Question extends Model
{
    protected $fillable = ['title', 'body']; 

    public function user()
    {
        return $this->belongsTo(User::class); 
    }

    // Gets the title attribute and makes it into a slug
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value; 
        $this->attributes['slug'] = Str::slug($value); 
    }

    // Set the url for questions
    public function getUrlAttribute()
    {
        return route("questions.show", $this->slug); 
    }

    // Displays date question was posted 
    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans(); 
    }

    //Checks to see if the answer has been answered
    public function getStatusAttribute()
    {
        if($this->answers_count > 0){ 
            if($this->best_answer_id){ 
                return "answered-accepted"; 
            }
            return "answered"; 
        }
        return "unanswered"; 
    }

    // Display the question in plain text
    public function getBodyHtmlAttribute()
    {
        return  \Parsedown::instance()->text($this->body); 
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);  
    }

    public function acceptBestAnswer(Answer $answer)
    {
        $this->best_answer_id = $answer->id; 
        $this->save(); 
    }
}
