<?php

namespace ChrisVanLier2005\OpenApiGenerator\Internal;

readonly class EndpointResponse
{
    /**
     * Create a new EndpointResponse instance.
     *
     * @param string $description
     * @param ResponseBody $content
     */
    public function __construct(
        public string $description,
        public ResponseBody $content,
    ) {
    }
}