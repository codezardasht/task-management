<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;


    public function users()
    {
        return $this->belongsToMany(User::class, 'task_users', 'task_id', 'user_id');
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
