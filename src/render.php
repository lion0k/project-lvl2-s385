<?php

namespace Gendiff\Render;

use function Gendiff\Renderers\renderPretty;
use function Gendiff\Renderers\renderPlain;

function render($data, $format):string
{
    switch ($format) {
        case 'pretty':
            return renderPretty($data) . PHP_EOL;

        case 'plain':
            return renderPlain($data) . PHP_EOL;

        default:
            throw new \Exception('Unsupported output format');
    }
}
