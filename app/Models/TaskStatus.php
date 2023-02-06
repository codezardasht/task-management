<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    use HasFactory;

    public function status()
    {
        return $this->belongsTo(StatusBoard::class , 'status_board_id');
    }
}
