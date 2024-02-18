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

        $resourceRootPath = $this->resourceRootPath($resource);

        $endpointPath = str_replace(
            '{:resource}',
            $this->modelBindingName($resource),
            match ($node->name->name) {
                'show', 'update', 'destroy', 'restore' => '/{:resource}',
                default => '',
            }
        );

        $operation->path = "/{$resourceRootPath}{$endpointPath}";
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

    /**
     * Get the root path for the given resource.
     *
     * @param string $resource
     * @return string
     */
    private function resourceRootPath(string $resource): string
    {
        $path = Str::headline($resource);
        $path = Str::slug($path);

        return Str::plural($path);
    }

    /**
     * Get the model binding name for the given resource.
     *
     * @param string $resource
     * @return string
     */
    private function modelBindingName(string $resource): string
    {
        $name = Str::headline($resource);
        $name = Str::singular($name);

        return Str::slug($name);
    }
}