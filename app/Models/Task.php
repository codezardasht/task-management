<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory;


    public function users()
    {
        return $this->belongsToMany(User::class, 'task_users', 'task_id', 'user_id');
    }

    public function labels()
    {
        return $this->hasMany(TaskLabel::class, 'task_id');
    }

    public function task_status()
    {
        return $this->hasMany(TaskStatus::class, 'task_id');
    }

    public function user()
    {
        return $this->hasOne(TaskUser::class, 'task_id');
    }

    public function scopeCheckRole($query)
    {

        $query->when((Auth::user()->hasRole('developer') || Auth::user()->hasRole('testers')) , function ($query){
            $query->whereHas('user' , function ($query){
               $query->where('user_id', Auth::id());
            });
        });
    }

    public function scopeSearch($query)
    {
        $query->when(request('search') != "" , function ($query){
            $query->where(function ($query) {
                $query->where('title','LIKE' , '%'.request('search').'%');
            });
        });
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $max = Task::where('board_id' , $model->board_id)->where('status_board_id' , $model->status_board_id)->max('order') + 1;
            $model->order = $max;
        });
    }
}
