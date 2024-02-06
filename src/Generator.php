<?php

namespace ChrisVanLier2005\OpenApiGenerator;

use Illuminate\Support\Facades\App;
use PhpParser\NodeTraverser;
use PhpParser\Parser;

class Generator
{
    private NodeTraverser $traverser;

    /**
     * The classes to traverse.
     *
     * @var array<int, class-string>
     */
    private array $classMap = [];

    private array $allowedSuffixes = [
        'Controller',
        'Resource',
        'Request',
    ];

    public function __construct(
        private readonly GeneratorOptions $options,
        private readonly Parser $parser,
    ) {
        //
    }

    /**
     * Create the intermediate representation before compilation
     * to the final OpenAPI specification.
     *
     * @return array<int, \PhpParser\Node>
     */
    public function getIntermediate(): array
    {
        $this->setTraverser();



    }

    /**
     * Set the node traverser.
     *
     * @return void
     */
    private function setTraverser(): void
    {
        $this->traverser = App::make(NodeTraverser::class);
    }

    private function parse(): array
    {

    }
}