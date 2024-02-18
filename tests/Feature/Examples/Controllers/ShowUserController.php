<?php

namespace ChrisVanLier2005\OpenApiGenerator\Tests\Feature\Examples\Controllers;

use ChrisVanLier2005\OpenApiGenerator\Tests\Feature\Examples\Models\User;
use ChrisVanLier2005\OpenApiGenerator\Tests\Feature\Examples\Resources\UserResource;

final class ShowUserController
{
    /**
     * Display the specified resource.
     *
     * @param \ChrisVanLier2005\OpenApiGenerator\Tests\Feature\Examples\Models\User $user
     * @return \ChrisVanLier2005\OpenApiGenerator\Tests\Feature\Examples\Resources\UserResource
     */
    public function __invoke(User $user): UserResource
    {
        return new UserResource();
    }
}
