<?php

namespace ChrisVanLier2005\OpenApiGenerator;

use ChrisVanLier2005\OpenApiGenerator\Internal\Endpoint;
use ChrisVanLier2005\OpenApiGenerator\Internal\Visitor;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Parser;
use ReflectionClass;

class Generator
{
    private Endpoint $endpoint;


    public function __construct(
        private readonly GeneratorOptions $options,
        private readonly Parser $parser,
    ) {
        $this->endpoint = new Endpoint(
            path: null,
            method: null,
            action: null,
            parameters: null,
            responses: null,
        );
    }

    /**
     * Parse the given method and return the virtual endpoint representation.
     *
     * @param class-string $class
     * @param string $method
     * @return Endpoint
     */
    public function getEndpointForMethod(string $class, string $method = '__invoke'): Endpoint
    {
        $parsed = $this->parser->parse($this->readClass($class));

        if ($parsed === null) {
            return $this->endpoint;
        }

        $this->getTraverser($class, $method)->traverse($parsed);

        return $this->endpoint;
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
     * @param string $method
     * @return NodeTraverser
     */
    private function getTraverser(string $class, string $method): NodeTraverser
    {
        $traverser = new NodeTraverser();

        $traverser->addVisitor(new NameResolver(
            options: [
                'preserveOriginalNames' => true,
                'replaceNodes' => true,
            ]
        ));

        $traverser->addVisitor(new Visitor(
            class: $class,
            method: $method,
            endpoint: $this->endpoint,
            options: $this->options,
        ));

        return $traverser;
    }
}

function dd($var) {
    var_dump($var);
    die();
}