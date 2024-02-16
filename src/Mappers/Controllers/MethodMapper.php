<?php

namespace ChrisVanLier2005\OpenApiGenerator\Mappers\Controllers;

use ChrisVanLier2005\OpenApiGenerator\Data\Operation;
use ChrisVanLier2005\OpenApiGenerator\Mappers\Mapper;
use PhpParser\Node;

final class MethodMapper implements Mapper
{
    /**
     * Map the request method of the endpoint.
     *
     * @param Node $node
     * @param Operation $endpoint
     * @return void
     */
    public function map(Node $node, Operation $endpoint): void
    {
        if (!$node instanceof Node\Stmt\ClassMethod) {
            return;
        }

        $endpoint->method = match ($node->name->name) {
            'store' => 'post',
            'update' => 'put',
            'destroy' => 'delete',
            default => 'get',
        };
    }

    /**
     * Determine whether the mapper should apply.
     *
     * @param \PhpParser\Node $node
     * @param \ChrisVanLier2005\OpenApiGenerator\Data\Operation $operation
     * @return bool
     */
    public function shouldMap(Node $node, Operation $operation): bool
    {
        return $node instanceof Node\Stmt\ClassMethod;
    }
}