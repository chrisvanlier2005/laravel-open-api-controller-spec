<?php

namespace Feature;

use ChrisVanLier2005\OpenApiGenerator\Generator;
use ChrisVanLier2005\OpenApiGenerator\GeneratorOptions;
use ChrisVanLier2005\OpenApiGenerator\Internal\Endpoint;
use ChrisVanLier2005\OpenApiGenerator\Internal\EndpointResponse;
use ChrisVanLier2005\OpenApiGenerator\Internal\ResponseBody;
use ChrisVanLier2005\OpenApiGenerator\Internal\ResponseSchema;
use ChrisVanLier2005\OpenApiGenerator\Internal\RouteParameter;
use PhpParser\ParserFactory;
use PHPUnit\Framework\TestCase;

final class GeneratorTest extends TestCase
{
    public function testItGeneratesCorrectIntermediateRepresentation(): void
    {
        $generator = new Generator(
            new GeneratorOptions(
                validReferenceNamespaces: ['Feature'],
            ),
            (new ParserFactory())->createForNewestSupportedVersion(),
        );

        $expected = new Endpoint(
            path: '/users',
            method: 'POST',
            action: 'users.store',
            parameters: [
                new RouteParameter(
                    name: 'user',
                    type: User::class,
                    description: null,
                    ref: 'user',
                )
            ],
            responses: [
                200 => new EndpointResponse(
                    description: 'OK',
                    content: new ResponseBody(
                        format: 'application/json',
                        schema: new ResponseSchema(
                            properties: ['$ref' => 'UserResource']
                        ),
                    )
                ),
                /*200 => [
                    'description' => 'OK',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                '$ref' => 'UserResource',
                            ],
                        ],
                    ],
                ],*/
            ]
        );

        $actual = $generator->getEndpointForMethod(UserController::class, 'store');

        $this->assertEquals($expected, $actual);
    }
}


class User {
    //
}

class UserResource {
    public function toArray(): array
    {
        return [];
    }
}

class UserController {
    public function store(User $user): UserResource
    {
        return new UserResource($user);
    }
}
