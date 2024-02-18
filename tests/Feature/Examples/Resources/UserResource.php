<?php

namespace ChrisVanLier2005\OpenApiGenerator\Tests\Feature\Examples\Resources;

/**
 * @property-read \ChrisVanLier2005\OpenApiGenerator\Tests\Feature\Examples\Models\User $resource
 */
final class UserResource
{
    public function toArray(): array
    {
        return [
            'name' => $this->resource->name,
            'email' => $this->resource->email,
        ];
    }
}