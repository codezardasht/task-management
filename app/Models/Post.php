<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Post extends Model  implements Auditable
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

      use HasFactory,SoftDeletes;
      use \OwenIt\Auditing\Auditable;

       public function User() {
              return $this->belongsTo(Admin::class, 'created_by');
          }

    protected $table = 'posts';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [''];

    
}
