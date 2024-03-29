<?php

namespace ChrisVanLier2005\OpenApiGenerator\Mappers;

use ChrisVanLier2005\OpenApiGenerator\Data\Operation;
use PhpParser\Node;

interface Mapper
{
    /**
     * Map the given node to the operation.
     *
     * @param \PhpParser\Node $node
     * @param \ChrisVanLier2005\OpenApiGenerator\Data\Operation $operation
     * @return void
     */
    public function map(Node $node, Operation $operation): void;

    /**
     * Determine whether mapper should be applied.
     *
     * @param \PhpParser\Node $node
     * @param \ChrisVanLier2005\OpenApiGenerator\Data\Operation $operation
     * @return bool
     */
    public function shouldMap(Node $node, Operation $operation): bool;
}