<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'description',
        'contact_email',
        'contact_phone',
        'contact_url'
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'company_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function job_offers(): HasMany
    {
        return $this->hasMany(JobOffer::class);
    }
}
