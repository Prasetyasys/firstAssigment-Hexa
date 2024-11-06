<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classes extends Model
{
    use SoftDeletes;
    protected $table = 'classes';
    protected $fillable = ['name', 'description'];


    public function members(): HasMany
    {
        return $this->hasMany(Members::class, 'class_id');
    }
}
