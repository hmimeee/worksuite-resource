<?php

namespace Modules\Resource\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'user_id',
        'file',
        'name',
        'ext',
        'details',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
