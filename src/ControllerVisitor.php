<?php

namespace ChrisVanLier2005\OpenApiGenerator;

use ChrisVanLier2005\OpenApiGenerator\Data\Operation;
use ChrisVanLier2005\OpenApiGenerator\Data\RouteParameter;
use ChrisVanLier2005\OpenApiGenerator\Mappers\Mapper;
use ChrisVanLier2005\OpenApiGenerator\Mappers\MapperSet;
use PhpParser\Node;
use PhpParser\NodeVisitor;

/**
 * @todo Refactor this class.
 */
class ControllerVisitor implements NodeVisitor
{
    /**
     * Create a new visitor instance.
     *
     * @param class-string $class
     * @param Operation $endpoint
     * @param list<class-string<Mapper>> $mappers
     */
    public function __construct(
        private readonly string $class,
        private readonly Operation $endpoint,
        private readonly array|MapperSet $mappers,
    ) {
    }

    public function enterNode(Node $node): void
    {
        $this->endpoint->class = $this->class;

        $mappers = $this->mappers instanceof MapperSet
            ? $this->mappers->__invoke()
            : $this->mappers;

        foreach ($mappers as $mapperIdentifier) {
            $mapper = new $mapperIdentifier;

            if (!$mapper->shouldMap($node, $this->endpoint)) {
                continue;
            }

            $mapper->map($node, $this->endpoint);
        }
    }

    public function beforeTraverse(array $nodes): void
    {
        //
    }

    public function leaveNode(Node $node): void
    {
        //
    }

    public function afterTraverse(array $nodes): void
    {
        //
    }
}