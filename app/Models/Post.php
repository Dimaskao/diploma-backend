<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *    @OA\Property(property="id", type="string", description="Post title"),
 *    @OA\Property(property="title", type="string", description="Post title"),
 *    @OA\Property(property="content", type="string", description="Post content"),
 *    @OA\Property(property="user_id", type="string", description="Post user_id"),
 * )
 */
class Post extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'content',
        'user_id'
    ];

    public function postImages() : HasMany
    {
        return $this->hasMany(PostImage::class);
    }

    public function comments() : HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
