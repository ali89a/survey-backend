<?php

namespace App\Http\Resources;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyResource extends JsonResource
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
            'description' => $this->description,
            'questions' =>$this->questions,
            'created_by'=>$this->created_by ? ($this->created_by == authUser(true) ? 'Self' : $this->createdBy->name) : '',
            'created_at'=>date('d M Y g:i A', strtotime($this->created_at)),
            'updated_at'=>date('d M Y g:i A', strtotime($this->updated_at)),

        ];

    }
}
