<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserContact extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';
    protected $fillable = [
        'subscriber_id',
        'subscription_id'
    ];

    public function subscriber(): HasOne
    {
        return $this->hasOne(RegularUser::class, 'subscriber_id');
    }

    public function subscription(): HasMany
    {
        return $this->hasMany(User::class, 'subscription_id');
    }
}
