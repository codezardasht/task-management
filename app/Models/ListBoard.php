<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ListBoard extends Model  implements Auditable
{
    use HasFactory , SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $max = ListBoard::where('board_id' , $model->board_id)->max('order') + 1;
            $model->order = $max;
        });
    }
}
