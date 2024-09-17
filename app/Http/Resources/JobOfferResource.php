<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobOfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                     => $this->id,
            'title'                  => $this->title,
            'company_id'             => $this->company_id,
            'position'               => $this->position,
            'description'            => $this->description,
            'requirements'           => $this->requirements,
            'requirement_experience' => $this->requirement_experience,
            'valid_until'            => $this->valid_until,
            'date_posted'            => Carbon::createFromDate($this->date_posted)->setTimezone('Europe/Kyiv'),
            'updated_at'             => $this->updated_at,
            'created_at'             => $this->created_at,
        ];
    }
}
