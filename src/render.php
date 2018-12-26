<?php

namespace Gendiff\Render;

const SPACE_INFO = ['nested' => '    '
                    , 'unchanged' => '    '
                    , 'added' => '  + '
                    , 'removed' => '  - '
                    , 'specialEmpty' => '    '];

function render($data, $format):string
{
    switch ($format) {
        case 'pretty':
            return '{' . PHP_EOL . renderPretty($data) . '}' . PHP_EOL;
            break;
    }
}

function renderPretty($data, $specialSpace = ''):string
{
    $renderData = array_reduce($data, function ($acc, $node) use ($specialSpace) {
        switch ($node['typeInfo']) {
            case 'unchanged':
                $acc[] = makeStrings(
                    $node['typeInfo'],
                    SPACE_INFO['unchanged'],
                    $node['nameKey'],
                    $node['oldValue'],
                    $specialSpace
                );
                break;

            case 'changed':
                $acc[] = makeStrings(
                    $node['typeInfo'],
                    SPACE_INFO['added'],
                    $node['nameKey'],
                    $node['newValue'],
                    $specialSpace
                );
                $acc[] = makeStrings(
                    $node['typeInfo'],
                    SPACE_INFO['removed'],
                    $node['nameKey'],
                    $node['oldValue'],
                    $specialSpace
                );
                break;

            case 'removed':
                $acc[] = makeStrings(
                    $node['typeInfo'],
                    SPACE_INFO['removed'],
                    $node['nameKey'],
                    $node['oldValue'],
                    $specialSpace
                );
                break;

            case 'added':
                $acc[] = makeStrings(
                    $node['typeInfo'],
                    SPACE_INFO['added'],
                    $node['nameKey'],
                    $node['newValue'],
                    $specialSpace
                );
                break;

            case 'nested':
                $acc[] = makeStrings(
                    $node['typeInfo'],
                    SPACE_INFO['nested'],
                    $node['nameKey'],
                    $node['child'],
                    $specialSpace
                );
                break;
        }
        return $acc;
    }, []);

    return implode('', $renderData);
}

function is_nested($type):bool
{
    return ($type === 'nested') ? true : false;
}

function makeStrings($typeInfo, $spaceInfo, $name, $value, $specialSpace):string
{
    $acc = [];
    if (is_array($value)) {
        $acc[] = buildString($spaceInfo, $name, '{', $specialSpace);

        if (is_nested($typeInfo)) {
            $acc[] = renderPretty($value, $specialSpace . SPACE_INFO['specialEmpty']);
        } else {
            $acc[] = makeGroupStrings($value, $specialSpace . SPACE_INFO['specialEmpty']);
        }

        $acc[] = buildString(SPACE_INFO['specialEmpty'], '', '}', $specialSpace);
    } else {
        return buildString($spaceInfo, $name, $value, $specialSpace);
    }

    return implode('', $acc);
}

function buildString($spaceInfo, $name, $value, $specialSpace):string
{
    if ($name) {
        return $specialSpace . $spaceInfo . $name . ': ' . $value . PHP_EOL;
    } else {
        return $specialSpace . $spaceInfo . $name . $value . PHP_EOL;
    }
}

function makeGroupStrings($data, $specialSpace):string
{
    $specialSpace .= SPACE_INFO['specialEmpty'];
    $dataGroup = array_map(function ($key) use ($data, $specialSpace) {
        return "{$specialSpace}{$key}: {$data[$key]}" . PHP_EOL;
    }, array_keys($data));

    return implode('', $dataGroup);
}
