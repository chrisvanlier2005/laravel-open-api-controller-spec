<?php

namespace ChrisVanLier2005\OpenApiGenerator\Tests\Feature\Examples\Controllers;

use ChrisVanLier2005\OpenApiGenerator\Tests\Feature\Examples\Models\User;
use ChrisVanLier2005\OpenApiGenerator\Tests\Feature\Examples\Resources\UserResource;

final class UserController
{
    public function show(User $user): UserResource
    {
        return new UserResource();
    }

    public function store(User $user): UserResource
    {
        return new UserResource($user);
    }
}
