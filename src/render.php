<?php

namespace Gendiff\render;

use function Gendiff\Renderer\renderPretty;
use function Gendiff\Renderer\renderPlain;
use function Gendiff\Renderer\renderJson;

function render($data, $format):string
{
    switch ($format) {
        case 'pretty':
            return renderPretty($data) . PHP_EOL;

        case 'plain':
            return renderPlain($data) . PHP_EOL;

        case 'json':
            return renderJson($data) . PHP_EOL;

        default:
            throw new \Exception("Unsupported output format '{$format}'");
    }
}
