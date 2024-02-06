<?php

namespace ChrisVanLier2005\OpenApiGenerator\Internal;

final class RouteParameter
{
    /**
     * Create a new route parameter instance.
     *
     * @param string|null $name
     * @param string|null $type
     * @param string|null $description
     * @param string|null $ref
     * @return void
     */
    public function __construct(
        public ?string $name = null,
        public ?string $type = null,
        public ?string $description = null,
        public ?string $ref = null,
    ) {
        //
    }
}