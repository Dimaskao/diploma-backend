<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Company extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'email',
        'password',
        'desc',
        'contact_email',
        'contact_phone',
        'contact_url',
        'avatar_url'
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function job_offers(): HasMany
    {
        return $this->hasMany(JobOffer::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
