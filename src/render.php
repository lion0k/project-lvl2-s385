<?php

namespace Gendiff\Render;

function render($data, $format)
{
    switch ($format) {
        case 'pretty':
            return renderPretty($data);
            break;
    }
}

function renderPretty($data)
{
    $acc[] = '{' . PHP_EOL;
    $renderData = array_reduce($data, function ($acc, $item) {
        $acc[] = makeString($item);
        return $acc;
    }, $acc);
    $renderData[] = '}' . PHP_EOL;

    return implode('', $renderData);
}

function makeString($data)
{
    switch ($data['typeInfo']) {
        case 'unchange':
            return '    ' . $data['nameKey'] . ': ' . $data['oldValue'] . PHP_EOL;
            break;

        case 'changed':
            $before = '  - ' . $data['nameKey'] . ': ' . $data['oldValue'] . PHP_EOL;
            $after = '  + ' . $data['nameKey'] . ': ' . $data['newValue'] . PHP_EOL;
            return "{$after}{$before}";
            break;

        case 'removed':
            return '  - ' . $data['nameKey'] . ': ' . $data['oldValue'] . PHP_EOL;
            break;

        case 'added':
            return '  + ' . $data['nameKey'] . ': ' . $data['newValue'] . PHP_EOL;
            break;
    }
}
