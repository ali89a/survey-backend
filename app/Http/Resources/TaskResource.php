<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'deadline' => $this->deadline,
            'status' => $this->status,
            'created_by'=>$this->created_by ? ($this->created_by == authUser(true) ? 'Self' : $this->createdBy->name) : '',
            'assigned_by'=>$this->assigned_by ? ($this->assigned_by == authUser(true) ? 'Self' : $this->assignedBy->name) : 'Not Yet',
            'assigned_to'=>$this->assigned_to ? ($this->assigned_to == authUser(true) ? 'Self' : $this->assignedTo->name) : 'Not Yet',
            'created_at'=>date('d M Y g:i A', strtotime($this->created_at)),
            'updated_at'=>date('d M Y g:i A', strtotime($this->updated_at)),

        ];

    }
}
