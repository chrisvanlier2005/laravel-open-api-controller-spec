<?php

namespace ChrisVanLier2005\OpenApiGenerator\Tests\Feature;

use ChrisVanLier2005\OpenApiGenerator\Data\Content;
use ChrisVanLier2005\OpenApiGenerator\Data\Operation;
use ChrisVanLier2005\OpenApiGenerator\Data\Reference;
use ChrisVanLier2005\OpenApiGenerator\Data\Response;
use ChrisVanLier2005\OpenApiGenerator\OpenApiGenerator;
use ChrisVanLier2005\OpenApiGenerator\GeneratorOptions;
use ChrisVanLier2005\OpenApiGenerator\OpenApiGeneratorFactory;
use PhpParser\ParserFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use function ChrisVanLier2005\OpenApiGenerator\dd;

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

        $actual = OpenApiGeneratorFactory::make()->getOperationForMethod(UserController::class, 'store');

        $this->assertEquals($expected, $actual);
    }

    public function testItGeneratesTheExpectedJson(): void
    {
        $generator = OpenApiGeneratorFactory::make();

        $operation = $generator->getOperationForMethod(UserController::class, 'store');

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
        ], JSON_PRETTY_PRINT);

        $actual = $generator->convertToJson($operation);
        $actual = Yaml::dump(json_decode($actual, true), 10, 2);
        dd($actual);


        $this->assertEquals($expected, $actual);
    }
}

class User
{
    //
}

class UserResource
{
    public function toArray(): array
    {
        return [];
    }
}

class UserController
{
    public function show(User $user): UserResource
    {
        return new UserResource();
    }

    public function store(User $user): UserResource
    {
        return new UserResource($user);
    }
}

