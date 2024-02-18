<?php

namespace ChrisVanLier2005\OpenApiGenerator\Mappers\Controllers;

use ChrisVanLier2005\OpenApiGenerator\Data\Content;
use ChrisVanLier2005\OpenApiGenerator\Data\Operation;
use ChrisVanLier2005\OpenApiGenerator\Data\Reference;
use ChrisVanLier2005\OpenApiGenerator\Data\Response;
use ChrisVanLier2005\OpenApiGenerator\Mappers\Mapper;
use Illuminate\Support\Str;
use PhpParser\Node;
use function ChrisVanLier2005\OpenApiGenerator\dd;

class ResponseMapper implements Mapper
{
    public function map(Node $node, Operation $operation): void
    {
        if (!$node instanceof Node\Stmt\ClassMethod) {
            return;
        }

        if (!str_ends_with($node->getReturnType()->name, 'Resource')) {
            // TODO: Add support for Collection types.
            return;
        }

        $name = Str::of($node->getReturnType()->name)
            ->classBasename()
            ->replace('Resource', '')
            ->toString();

        if ($operation->responses === null) {
            $operation->responses = [];
        }

        $operation->responses[] = new Response(
            description: $node->name->name,
            status: $this->status($node->name->name),
            content: new Content(
                'application/json',
                new Reference($name),
            ),
        );
    }

    public function shouldMap(Node $node, Operation $operation): bool
    {
        return $node instanceof Node\Stmt\ClassMethod
            && Str::endsWith($node->name->name, ['index', 'show', 'store', 'update', 'destroy', 'restore', '__invoke']);
    }

    private function status(string $name): int
    {
        return match ($name) {
            'store' => 201,
            'destroy' => 204,
            default => 200,
        };
    }
}