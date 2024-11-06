<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Members extends Model
{
    protected $table = 'members';
    protected $fillable = ['class_id', 'first_name', 'last_name', 'email', 'gender', 'address'];

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
}
