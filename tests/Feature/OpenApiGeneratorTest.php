<?php

namespace ChrisVanLier2005\OpenApiGenerator\Tests\Feature;

use ChrisVanLier2005\OpenApiGenerator\OpenApiGenerator;
use ChrisVanLier2005\OpenApiGenerator\OpenApiGeneratorFactory;
use ChrisVanLier2005\OpenApiGenerator\Tests\Feature\Examples\Controllers\ShowUserController;
use ChrisVanLier2005\OpenApiGenerator\Tests\Feature\Examples\Controllers\UserController;
use PHPUnit\Framework\TestCase;

final class OpenApiGeneratorTest extends TestCase
{
    private OpenApiGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $this->generator = OpenApiGeneratorFactory::make();
    }

    public function testItGeneratesTheExpectedJSONForStoreMethod(): void
    {
        $expected = json_encode([
            '/users' => [
                'post' => [
                    'operationId' => 'users.store',
                    'description' => 'Store a newly created User in storage',
                    'parameters' => [
                        [
                            '$ref' => '#/components/parameters/User',
                        ],
                    ],
                    'responses' => [
                        201 => [
                            'description' => 'Created',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/User',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], JSON_PRETTY_PRINT);

        $operation = $this->generator->makeOperationForMethod(
            UserController::class,
            'store',
        );

        $actual = $this->generator->operationToJson($operation, JSON_PRETTY_PRINT);

        $this->assertEquals($expected, $actual);
    }

    public function testItGeneratesTheExpectedJSONForInvokableControllers(): void
    {
        $this->markTestSkipped('WIP');

        $expected = json_encode([
            '/users/{user}' => [
                '~' => [
                    'operationId' => 'users.show',
                    'description' => null,
                    'parameters' => [
                        [
                            '$ref' => 'User',
                        ],
                    ],
                    'responses' => [
                        200 => [
                            'description' => 'show',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => 'User',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
            ]
        ], JSON_PRETTY_PRINT);

        $operation = $this->generator->makeOperationForMethod(
            ShowUserController::class,
        );

        $actual = $this->generator->operationToJson($operation, JSON_PRETTY_PRINT);

        $this->assertEquals($expected, $actual);
    }
}
