<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkExperience extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'position',
        'company',
        'start_date',
        'end_date',
        'description'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(RegularUser::class);
    }
}
