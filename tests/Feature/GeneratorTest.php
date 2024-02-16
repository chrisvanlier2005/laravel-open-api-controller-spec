<?php

namespace Feature;

use ChrisVanLier2005\OpenApiGenerator\Data\Content;
use ChrisVanLier2005\OpenApiGenerator\Data\Operation;
use ChrisVanLier2005\OpenApiGenerator\Data\Reference;
use ChrisVanLier2005\OpenApiGenerator\Data\Response;
use ChrisVanLier2005\OpenApiGenerator\Generator;
use ChrisVanLier2005\OpenApiGenerator\GeneratorOptions;
use Illuminate\Support\Arr;
use PhpParser\ParserFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use function ChrisVanLier2005\OpenApiGenerator\dd;

final class GeneratorTest extends TestCase
{
    public function ItGeneratesCorrectIntermediateRepresentation(): void
    {
        $generator = new Generator(
            (new ParserFactory())->createForNewestSupportedVersion(),
        );

        $expected = new Operation(
            class: UserController::class,
            path: '/users',
            method: 'POST',
            operationId: 'users.store',
            parameters: [
                new Reference(
                    ref: User::class,
                ),
            ],
            responses: [
                new Response(
                    description: 'OK',
                    status: 201,
                    content: new Content(
                        'application/json',
                        new Reference(
                            ref: UserResource::class,
                        ),
                    ),
                ),
            ]
        );

        $actual = $generator->getOperationForMethod(UserController::class, 'store');

        $this->assertEquals($expected, $actual);
    }

    public function testItGeneratesTheExpectedJson(): void
    {
        $generator = new Generator(
            (new ParserFactory())->createForNewestSupportedVersion(),
        );

        $operation = $generator->getOperationForMethod(UserController::class, 'store');

        $expected = json_encode([
            '/users' => [
                'POST' => [
                    'operationId' => 'users.store',
                    'description' => '',
                    'parameters' => [
                        [
                            '$ref' => 'User'
                        ],
                    ],
                    'responses' => [
                        201 => [
                            'description' => 'OK',
                            'content' => [
                                'application/json' => [
                                    '$ref' => 'User',
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
