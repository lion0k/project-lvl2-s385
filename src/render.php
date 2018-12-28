<?php

namespace Gendiff\Render;

use function Gendiff\Renderers\renderPretty;
use function Gendiff\Renderers\renderPlain;
use function Gendiff\Renderers\renderJson;

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
            throw new \Exception('Unsupported output format');
    }
}
