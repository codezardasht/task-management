<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $max = Task::where('board_id' , $model->board_id)->where('list_id' , $model->list_id)->max('order') + 1;
            $model->order = $max;
        });
    }
}
