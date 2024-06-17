<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RegularUser extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'first_name',
        'last_name',
        'skills_desc',
        'experience'
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'user_id');
    }

    public function user_educations(): HasMany
    {
        return $this->hasMany(UserEducation::class);
    }

    public function work_experiences(): HasMany
    {
        return $this->hasMany(WorkExperience::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class);
    }
}
