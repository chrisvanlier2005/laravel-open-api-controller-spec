<?php

namespace ChrisVanLier2005\OpenApiGenerator\Mappers\Controllers;

use ChrisVanLier2005\OpenApiGenerator\Data\Operation;
use ChrisVanLier2005\OpenApiGenerator\Data\Reference;
use ChrisVanLier2005\OpenApiGenerator\Data\RequestParameter;
use ChrisVanLier2005\OpenApiGenerator\Mappers\Mapper;
use Illuminate\Support\Str;
use PhpParser\Node;
use function ChrisVanLier2005\OpenApiGenerator\dd;

final class ParameterMapper implements Mapper
{
    /**
     * Guess the request method of the endpoint.
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

        $parameters = array_map(function (Node\Param $param) {
            if ($param->type === null) {
                return null;
            }

            $name = class_basename($param->type->name);

            if (!in_array($param->type->name, ['int', 'string', 'bool', 'float'])) {
                return new Reference($name);
            }

            return new RequestParameter(
                name: $param->var->name,
                in: 'query',
                required: !$param->default,
                description: '',
                type: $param->type->name,
                schema: new Reference($name),
            );
        }, $node->getParams());

        $endpoint->parameters = array_filter($parameters);
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