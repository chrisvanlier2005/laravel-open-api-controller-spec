<?php

namespace ChrisVanLier2005\OpenApiGenerator\Mappers\Controllers;

use ChrisVanLier2005\OpenApiGenerator\Data\Operation;
use ChrisVanLier2005\OpenApiGenerator\Mappers\Mapper;
use Illuminate\Support\Str;
use PhpParser\Node;

final class PathMapper implements Mapper
{

    /**
     * Map the path of the endpoint.
     *
     * @param \PhpParser\Node $node
     * @param \ChrisVanLier2005\OpenApiGenerator\Data\Operation $operation
     * @return void
     */
    public function map(Node $node, Operation $operation): void
    {
        if (!$node instanceof Node\Stmt\ClassMethod) {
            return;
        }

        $resource = class_basename($operation->class);
        $resource = str_replace('Controller', '', $resource);

        $resourcePath = str($resource)
            ->headline()
            ->slug()
            ->plural();

        $modelBindingName = str($resource)
            ->headline()
            ->singular()
            ->slug();

        $endpointPath = str_replace(
            '{:resource}',
            "{$modelBindingName}",
            match ($node->name->name) {
                'show', 'update', 'destroy', 'restore' => '/{:resource}',
                default => '',
            }
        );

        $operation->path = "/$resourcePath$endpointPath";
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
        return $node instanceof Node\Stmt\ClassMethod
            && Str::endsWith($node->name->name, [
                'index',
                'show',
                'store',
                'update',
                'destroy',
                'restore',
                '__invoke',
            ]);
    }
}