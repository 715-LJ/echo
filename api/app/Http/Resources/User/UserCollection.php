<?php

namespace App\Http\Resources\User;

use App\Constants\Constant;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use JsonSerializable;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array|Collection|JsonSerializable|Arrayable
     */
    public function toArray(Request $request): array|Collection|JsonSerializable|Arrayable
    {
        return $this->collection->map(function ($item) {
            return [
                'id'    => $item->id,
                'name'  => $item->name,
                'email' => $item->email,
            ];
        });

    }
}
