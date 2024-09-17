<?php

namespace App\Http\Resources;

use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $name = match ($this->user->role->name) {
            UserRole::REGULAR_USER => ['first_name' => $this->user->userProfile->regularUser->first_name, 'last_name' => $this->user->userProfile->regularUser->last_name],
            UserRole::COMPANY      => ['first_name' => $this->user->userProfile->company->name],
            UserRole::ADMIN        => ['first_name' => $this->user->userProfile->admin]
        };

        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'content'    => $this->content,
            'status'     => $this->status,
            'visibility' => $this->visibility,
            'user'       => [
                'id'         => $this->user_id,
                'first_name' => $name['first_name'],
                'last_name'  => $name['last_name'] ?? null,
                'role'       => $this->user->role->name,
            ],
            'images'     => $this->getMedia('postsImages')->mapWithKeys(function ($image) {
                return [$image->id => $image->getFullUrl()];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
