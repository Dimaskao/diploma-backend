<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';
    protected $fillable = ['name'];
}
