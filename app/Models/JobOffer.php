<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JobOffer extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'company_id',
        'position',
        'description',
        'requirements',
        'requirement_experience',
        'date_posted',
        'valid_until',
    ];

    //    public function skills(): BelongsToMany
    //    {
    //        return $this->belongsToMany(Skill::class);
    //    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
