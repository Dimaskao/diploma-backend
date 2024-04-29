<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class UserProject extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'user_id',
        'desc',
        'start_date',
        'end_date',
        'url'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
