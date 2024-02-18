<?php

namespace ChrisVanLier2005\OpenApiGenerator\Tests\Feature;

use ChrisVanLier2005\OpenApiGenerator\Data\Content;
use ChrisVanLier2005\OpenApiGenerator\Data\Operation;
use ChrisVanLier2005\OpenApiGenerator\Data\Reference;
use ChrisVanLier2005\OpenApiGenerator\Data\Response;
use ChrisVanLier2005\OpenApiGenerator\OpenApiGeneratorFactory;
use ChrisVanLier2005\OpenApiGenerator\Tests\Feature\Examples\Controllers\UserController;
use PHPUnit\Framework\TestCase;

final class GeneratorTest extends TestCase
{
    public function testItGeneratesCorrectIntermediateRepresentation(): void
    {
        $expected = new Operation(
            class: UserController::class,
            classMethod: 'store',
            path: '/users',
            method: 'post',
            operationId: 'users.store',
            parameters: [
                new Reference(
                    ref: 'User',
                ),
            ],
            responses: [
                new Response(
                    description: 'store',
                    status: 201,
                    content: new Content(
                        type: 'application/json',
                        schema: new Reference(
                            ref: 'User',
                        ),
                    ),
                ),
            ]
        );

        $actual = OpenApiGeneratorFactory::make()
            ->getOperationForMethod(UserController::class, 'store');

        $this->assertEquals($expected, $actual);
    }

    public function testItGeneratesTheExpectedJson(): void
    {
        $generator = OpenApiGeneratorFactory::make();

        $operation = $generator->getOperationForMethod(
            UserController::class,
            'store'
        );

        $expected = json_encode([
            '/users' => [
                'post' => [
                    'operationId' => 'users.store',
                    'description' => null,
                    'parameters' => [
                        [
                            '$ref' => 'User',
                        ],
                    ],
                    'responses' => [
                        201 => [
                            'description' => 'store',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => 'User',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $actual = $generator->convertToJson($operation);

        $this->assertEquals($expected, $actual);
    }
}
