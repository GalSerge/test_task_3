<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class NotebookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'note_id' => $this->id,
            'fio' => $this->fio,
            'company' => $this->company,
            'email' => $this->email,
            'date_birth' => $this->date_birth,
            'phone' => $this->phone,
            'photo' => $this->photo
        ];
    }
}
