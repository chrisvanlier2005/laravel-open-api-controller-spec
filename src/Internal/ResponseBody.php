<?php

namespace ChrisVanLier2005\OpenApiGenerator\Internal;

readonly class ResponseBody
{
    public function __construct(
        public ResponseSchema $schema,
    ) {
        //
    }
}