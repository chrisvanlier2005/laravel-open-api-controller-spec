<?php

namespace ChrisVanLier2005\OpenApiGenerator\Mappers\Controllers;

use ChrisVanLier2005\OpenApiGenerator\Data\Operation;
use ChrisVanLier2005\OpenApiGenerator\Mappers\Mapper;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;

class DescriptionMapper implements Mapper
{
    /**
     * Map the request description of the operation.
     *
     * @param \PhpParser\Node $node
     * @param \ChrisVanLier2005\OpenApiGenerator\Data\Operation $operation
     * @return void
     */
    public function map(Node $node, Operation $operation): void
    {
        if (!$node instanceof ClassMethod) {
            return;
        }

        $description = match ($operation->classMethod) {
            'index' => 'Display a listing of the {resource}',
            'show' => 'Display the specified {resource}',
            'store' => 'Store a newly created {resource} in storage',
            'update' => 'Update the specified {resource} in storage',
            'destroy' => 'Remove the specified {resource} from storage',
        };

        $operation->description = str_replace(
            '{resource}',
            $this->getResourceName($operation) ?? 'resource',
            $description
        );
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
        return $node instanceof ClassMethod;
    }

    /**
     * Get the resource name.
     *
     * @param \ChrisVanLier2005\OpenApiGenerator\Data\Operation $operation
     * @return string
     */
    private function getResourceName(Operation $operation): ?string
    {
        $class = class_basename($operation->class);

        return str_replace('Controller', '', $class);
    }
}