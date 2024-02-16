<?php

namespace ChrisVanLier2005\OpenApiGenerator\Mappers\Controllers;

use ChrisVanLier2005\OpenApiGenerator\Data\Operation;
use ChrisVanLier2005\OpenApiGenerator\Mappers\Mapper;
use PhpParser\Node;

final class OperationIdMapper implements Mapper
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

        $resource = class_basename($endpoint->class);
        $resource = str_replace('Controller', '', $resource);
        $resource = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $resource)) . 's';

        $endpoint->operationId = match ($node->name->name) {
            'index' => "{$resource}.index",
            'show' => "{$resource}.show",
            'store' => "{$resource}.store",
            'update' => "{$resource}.update",
            'destroy' => "{$resource}.destroy",
            default => "{$resource}.{$node->name->name}",
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