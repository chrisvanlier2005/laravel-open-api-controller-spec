<?php

namespace ChrisVanLier2005\OpenApiGenerator;

use ChrisVanLier2005\OpenApiGenerator\Data\OpenApiGeneratorConfig;
use ChrisVanLier2005\OpenApiGenerator\Data\Operation;
use ChrisVanLier2005\OpenApiGenerator\Mappers\MapperSet;
use ChrisVanLier2005\OpenApiGenerator\Mappers\Sets\ControllerSet;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use ReflectionClass;
use RuntimeException;

class OpenApiGenerator
{
    private Operation $endpoint;

    /**
     * Create a new Generator instance.
     *
     * @param \PhpParser\Parser $parser
     * @param list<\ChrisVanLier2005\OpenApiGenerator\Mappers\Mapper>|\ChrisVanLier2005\OpenApiGenerator\Mappers\MapperSet|null $mappers
     * @return void
     */
    public function __construct(
        private readonly Parser $parser,
        public array|MapperSet|null $mappers = null,
    ) {
        $this->endpoint = new Operation(
            path: null,
            method: null,
            operationId: null,
            parameters: null,
            responses: null,
        );
    }

    /**
     * Parse the given method and return the virtual endpoint representation.
     *
     * @param class-string $class
     * @param string $method
     * @return \ChrisVanLier2005\OpenApiGenerator\Data\Operation
     */
    public function makeOperationForMethod(
        string $class,
        string $method = '__invoke'
    ): Operation {
        $parsed = $this->parser->parse($this->getClassContents($class));

        if ($parsed === null) {
            return $this->endpoint;
        }

        $this->buildTraverser($class, $method)
            ->traverse($parsed);

        return $this->endpoint;
    }

    /**
     * Retrieve the file contents of the given class.
     *
     * @param string $class
     * @return string
     * @throws \ReflectionException
     */
    private function getClassContents(string $class): string
    {
        return file_get_contents((new ReflectionClass($class))->getFileName());
    }

    /**
     * Build a prepared traverser instance.
     *
     * @param string $class
     * @param string $method
     * @return NodeTraverser
     * @todo Remove class & method parameters
     */
    private function buildTraverser(
        string $class,
        string $method
    ): NodeTraverser {
        $traverser = new NodeTraverser();

        $traverser->addVisitor(
            new NameResolver(
                options: [
                    'preserveOriginalNames' => true,
                    'replaceNodes' => true,
                ],
            ),
        );

        $traverser->addVisitor(
            new ControllerVisitor(
                class: $class,
                method: $method,
                endpoint: $this->endpoint,
                mappers: $this->mappers ?? new ControllerSet,
            ),
        );

        return $traverser;
    }

    /**
     * Convert the given operation to a JSON string.
     *
     * @param \ChrisVanLier2005\OpenApiGenerator\Data\Operation $operation
     * @param int $flags
     * @return string
     * @todo Determine if this is actually necessary as it's just a safe
     *     wrapper around `json_encode`
     */
    public function operationToJson(
        Operation $operation,
        int $flags = 0
    ): string {
        $json = json_encode($operation, $flags);

        if ($json === false) {
            throw new RuntimeException('Failed to convert operation to JSON.');
        }

        return $json;
    }
}