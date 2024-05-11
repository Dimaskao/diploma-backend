<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'password' => $this->password,
            'avatar_url' => $this->avatar_url,
            'skills_desc' => $this->skills_desc,
            'experience' => $this->experience,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
