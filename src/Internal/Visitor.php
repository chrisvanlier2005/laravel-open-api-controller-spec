<?php

namespace ChrisVanLier2005\OpenApiGenerator\Internal;

use ChrisVanLier2005\OpenApiGenerator\GeneratorOptions;
use Illuminate\Support\Str;
use PhpParser\Node;
use PhpParser\NodeVisitor;

/**
 * @todo make this class simpler and delegate the guessing to specific classes / external methods.
 */
readonly class Visitor implements NodeVisitor
{
    /**
     * Create a new visitor instance.
     *
     * @param class-string $class
     * @param string $method
     * @param Endpoint $endpoint
     * @param GeneratorOptions $options
     */
    public function __construct(
        private string $class,
        private string $method,
        private Endpoint $endpoint,
        private GeneratorOptions $options,
    ) {
        //
    }

    public function enterNode(Node $node): void
    {
        if ($node instanceof Node\Stmt\Class_ && $node->namespacedName->name === $this->class) {
            $this->endpoint->path = $this->guessPath();
        }

        if ($node instanceof Node\Stmt\ClassMethod && $node->name->name === $this->method) {
            $this->endpoint->method = $this->guessMethod($node);
            $this->endpoint->action = $this->guessAction($node);
            $this->endpoint->parameters = $this->guessParameters($node);
            $this->endpoint->responses = $this->guessResponses($node);
        }
    }

    /**
     * Guesses the path of the controller and method class.
     * If it's unable to guess the path, it will return null.
     *
     * @return string|null
     */
    private function guessPath(): ?string
    {
        $mapping = [
            'index'   => '',
            'show'    => '/{:resource}',
            'store'   => '',
            'update'  => '/{:resource}',
            'destroy' => '/{:resource}',
        ];

        $name = $this->getResourceName();

        $resourcePath = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $name)) . 's';

        $queryResource = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $name));

        $endpointPath = str_replace('{:resource}', $queryResource, $mapping[$this->method]);

        return "/$resourcePath$endpointPath";
    }

    private function getResourceName(): string
    {
        $name = last(explode('\\', $this->class));

        return str_replace('Controller', '', $name);
    }

    /**
     * Guesses the method based on laravel conventions.
     *
     * @param Node\Stmt\ClassMethod $node
     * @return string|null
     */
    private function guessMethod(Node\Stmt\ClassMethod $node): ?string
    {
        $mapping = [
            'index'   => 'GET',
            'show'    => 'GET',
            'store'   => 'POST',
            'update'  => 'PUT',
            'destroy' => 'DELETE',
        ];

        return $mapping[$node->name?->name] ?? null;
    }

    /**
     * Guesses the action/ route name based on laravel conventions.
     *
     * @param Node\Stmt\ClassMethod $node
     * @return string
     */
    private function guessAction(Node\Stmt\ClassMethod $node): string
    {
        $name = $this->getResourceName();

        $resource = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $name)) . 's';

        return "{$resource}.{$this->method}";
    }

    private function guessParameters(Node\Stmt\ClassMethod $node)
    {
        $parameters = $node->getParams();


        $parameters = array_map(function (Node\Param $parameter) {
            $type = $parameter->type;

            if ($type === null) {
                return null;
            }

            return new RouteParameter(
                name: $parameter->var->name,
                type: $type->name,
                description: null,
                // TODO: Figure out how to determine the description.
                ref: $this->getValidRef($type->name),
            );
        }, $parameters);

        return array_filter($parameters);
    }

    private function getValidRef(string $name): ?string
    {
        if (!Str::contains($name, $this->options->validReferenceNamespaces)) {
            return null;
        }

        $name = last(explode('\\', $name));

        return $this->options->routeParameterRefPrefix . mb_strtolower($name, 'UTF-8');
    }

    /**
     * Guesses the responses the endpoint will return.
     *
     * @param Node\Stmt\ClassMethod $node
     * @return array<int, Response>|null
     */
    private function guessResponses(Node\Stmt\ClassMethod $node): array
    {
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