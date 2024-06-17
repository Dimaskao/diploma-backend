<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSkill extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'skill_id'
    ];

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(RegularUser::class);
    }
}
