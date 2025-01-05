<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ContactsResource extends ResourceCollection
{

    public function toArray($request)
    {
        return [
            'status' => 1,
            'message' => 'contacts fetched successfully',
            'data' => $this->collection->toArray(),
        ];
    }
}
