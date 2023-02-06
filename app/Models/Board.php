<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class Board extends Model implements Auditable
{
    use HasFactory,SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public function status_board()
    {
        return $this->hasMany(StatusBoard::class,'board_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class,'board_id');
    }

    public function scopeCheckRole($query)
    {
        $query->when((Auth::user()->hasRole('developer') || Auth::user()->hasRole('testers')) , function ($query){
            $query->whereHas('tasks' , function ($query){
                $query->whereHas('user' , function ($query){
                    $query->where('user_id', Auth::id());
                });
            });
        });
    }

    public function scopeSearch($query)
    {
        $query->when(request('search') != "" , function ($query){
            $query->where(function ($query) {
                $query->where('name','LIKE' , '%'.request('search').'%');
            });
        });
    }
}
