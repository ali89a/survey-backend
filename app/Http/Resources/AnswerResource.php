<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
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
            'answer' => $this->answer,
            'question' => $this->question->question,
            'survey_id' => $this->survey_id,
            'created_at'=>date('d M Y g:i A', strtotime($this->created_at)),
        ];

    }
}
