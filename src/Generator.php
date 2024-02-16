<?php

namespace ChrisVanLier2005\OpenApiGenerator;

use ChrisVanLier2005\OpenApiGenerator\Data\Operation;
use ChrisVanLier2005\OpenApiGenerator\Mappers\Sets\ControllerSet;
use Illuminate\Support\Arr;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use ReflectionClass;
use RuntimeException;

/**
 * @todo refactor this.
 */
class Generator
{
    private Operation $endpoint;


    /**
     * Create a new Generator instance.
     *
     * @param \PhpParser\Parser $parser
     * @return void
     */
    public function __construct(
        private readonly Parser $parser,
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
     * @throws \ReflectionException
     */
    public function getOperationForMethod(string $class): Operation
    {
        $parsed = $this->parser->parse($this->readClass($class));

        if ($parsed === null) {
            return $this->endpoint;
        }

        $this->buildTraverser($class)
            ->traverse($parsed);

        return $this->endpoint;
    }

    /**
     * Convert the given operation to a JSON string.
     *
     * @param \ChrisVanLier2005\OpenApiGenerator\Data\Operation $operation
     * @param int $flags
     * @return string
     */
    public function convertToJson(Operation $operation, int $flags = 0): string
    {
        $json = json_encode($operation, $flags);

        if ($json === false) {
            throw new RuntimeException('Failed to convert operation to JSON.');
        }

        return $json;
    }

    /**
     * @throws \ReflectionException
     */
    private function readClass(string $class): string
    {
        return file_get_contents((new ReflectionClass($class))->getFileName());
    }

    /**
     * Retrieve the correct traverser.
     *
     * @param string $class
     * @return NodeTraverser
     * @todo Remove class & method parameters
     */
    private function buildTraverser(string $class): NodeTraverser
    {
        $traverser = new NodeTraverser();

        $traverser->addVisitor(
            new NameResolver(
                options: [
                    'preserveOriginalNames' => true,
                    'replaceNodes' => true,
                ]
            )
        );

        $traverser->addVisitor(
            new ControllerVisitor(
                class: $class,
                endpoint: $this->endpoint,
                mappers: new ControllerSet,
            )
        );

        return $traverser;
    }
}

function dd(...$var)
{
    if (count($var) === 1) {
        $var = Arr::first($var);
    }

    var_dump($var);
    die();
}