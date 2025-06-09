<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'stars' => $this->rating,
            'text' => $this->review_comment,
            'user' => $this->reviewer->user_name ?? 'user',
            'date' => $this->created_at->translatedFormat('F Y'),       
            'helpful' => 0,
            'avatar' => '<span class="text-primary">' . mb_substr($this->reviewer->user_name ?? 'U', 0, 1) . '</span>',
        ];
    }
}
