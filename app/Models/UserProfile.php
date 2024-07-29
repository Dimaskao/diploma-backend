<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserProfile extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'admin_id',
        'company_id',
        'regular_user_id',
    ];

    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class, 'id', 'admin_id');
    }

    public function company(): HasOne
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function regularUser(): HasOne
    {
        return $this->hasOne(RegularUser::class, 'id', 'regular_user_id');
    }
}
