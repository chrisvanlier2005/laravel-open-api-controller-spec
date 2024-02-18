<?php

namespace ChrisVanLier2005\OpenApiGenerator\Mappers\Controllers;

use ChrisVanLier2005\OpenApiGenerator\Data\Content;
use ChrisVanLier2005\OpenApiGenerator\Data\Operation;
use ChrisVanLier2005\OpenApiGenerator\Data\Reference;
use ChrisVanLier2005\OpenApiGenerator\Data\Response;
use ChrisVanLier2005\OpenApiGenerator\Mappers\Mapper;
use Illuminate\Support\Str;
use PhpParser\Node;

class ResponseMapper implements Mapper
{
    /**
     * Map the given node to the operation.
     *
     * @param \PhpParser\Node $node
     * @param \ChrisVanLier2005\OpenApiGenerator\Data\Operation $operation
     * @return void
     */
    public function map(Node $node, Operation $operation): void
    {
        // TODO: replace this with an Assertion.
        if (!$node instanceof Node\Stmt\ClassMethod) {
            return;
        }

        if (!str_ends_with($node->getReturnType()->name, 'Resource')) {
            // TODO: Add support for Collection types.

            return;
        }

        $name = $this->getResourceName($node);

        if ($operation->responses === null) {
            $operation->responses = [];
        }

        $operation->responses[] = new Response(
            description: null,
            status: $this->status($node->name->name),
            content: new Content(
                'application/json',
                new Reference($name),
            ),
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
        return $node instanceof Node\Stmt\ClassMethod
            && Str::endsWith($node->name->name, ['index', 'show', 'store', 'update', 'destroy', 'restore', '__invoke']);
    }

    /**
     * Get the name of the resource that should be used.
     *
     * @param \PhpParser\Node $node
     * @return string
     */
    private function getResourceName(Node $node): string
    {
        return Str::of($node->getReturnType()?->name ?? '')
            ->classBasename()
            ->replace('Resource', '')
            ->toString();
    }

    /**
     * Get the status code that should be used for the given method name.
     *
     * @param string $name
     * @return int
     */
    private function status(string $name): int
    {
        return match ($name) {
            'store' => 201,
            'destroy' => 204,
            default => 200,
        };
    }
}