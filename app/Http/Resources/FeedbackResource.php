<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'survey' => $this->survey->name,
            'answers' => $this->answers?AnswerResource::collection($this->answers):null,
            'created_at'=>date('d M Y g:i A', strtotime($this->created_at)),
        ];

    }
}
