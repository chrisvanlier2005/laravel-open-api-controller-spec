# Laravel OpenAPI specification generator

This is a PoC for generating OpenAPI specification from Laravel controllers.
Currently only supports generating basic endpoint based on a controller.

e.g.
```php
final class UserController 
{
    public function show(User $user): UserResource
    {
        return new UserResource::make($user);
    }
}
```

Can be converted to:
```yaml
/users:
  post:
    operationId: users.store
    description: null
    parameters:
      - $ref: User
    responses:
      201:
        description: store
        content:
          application/json:
            schema:
              $ref: User
```

There are still some problems with the current implementation, as you can see the description is null or does not make sense.
Also there is still work to be done to support more complex endpoints & responses.

For example:
* Support for other HTTP response codes. If a form request is used with validation, there should be a response for Unprocessable Entity.
* Collection return types. If a controller returns a collection, the response either be a reference to the collection or a array of references to the item.

## Setting up
This package depends on the PHP-Parser package to parse php files. However you can specify the parser to use when creating the generator.

```php
$generator = new OpenApiGenerator(
    (new ParserFactory())->createForNewestSupportedVersion(),
);
```

However you can also use the `OpenApiGeneratorFactory::make()` method to get the latest supported parser.

```php
$generator = OpenApiGeneratorFactory::make();
```

Please note that it is generally recommended to inject the generator into your classes. There are various containers to achieve this.

### Mappers
The package provides a few prepared mappers to map the parsed nodes to an OpenAPI operation. However you are not required to use these mappers.
You can create your own mappers as you see fit.

**Using specifified mappers or a set of mappers.**
```php
$generator = OpenApiGeneratorFactory::make([
    new OperationMapper(),
    new ParameterMapper(referencePrefix: '#/components/schemas/'),
    new ResponseMapper(),
    // ...
]);

// Reusable set of custom mappers.
$generator = OpenApiGeneratorFactory::make(
    new LegacyOperationMapper,
);
```
Note that the mappers should implement the `Mapper` interface and the sets should implement the `MapperSet` interface.

## Generating the Operation object
An endpoint is represented as an Operation object. This object will be built by the mappers and contains the required information
to generate the specification.

```php
/** @var \ChrisVanLier2005\OpenApiGenerator\OpenApiGenerator $generator */
$operation = $generator->makeOperationForMethod(UserController::class, 'store');
```

### Building the specification for an Operation
The Operation object can be used to build the specification for the endpoint.

```php
/** @var \ChrisVanLier2005\OpenApiGenerator\OpenApiGenerator $generator */
$json = $generator->toJson($operation);
```

You can then convert this to a YAML string if that is the preferred format.