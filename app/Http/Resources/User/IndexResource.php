<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Role\IndexResource as RoleIndexResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => RoleIndexResource::collection($this->whenLoaded('roles')),
        ];
        return $data;
    }
}
