<?php

namespace Gendiff\Renderer;

use function Funct\Collection\flattenAll;

const SPACE_INFO = ['nested'       => '    ',
                    'unchanged'    => '    ',
                    'added'        => '  + ',
                    'removed'      => '  - ',
                    'specialEmpty' => '    '
                    ];

function renderPretty($data, $depth = 0)
{
    $result = array_map(function ($node) use ($depth) {

        ['typeInfo' => $typeInfo,
         'nameKey'  => $nameKey,
         'oldValue' => $oldValue,
         'newValue' => $newValue,
         'children' => $children] = $node;

        switch ($typeInfo) {
            case 'unchanged':
                return makeString($depth, 'unchanged', $nameKey, buildStrings($oldValue, $depth + 1));
                break;

            case 'changed':
                return [makeString($depth, 'added', $nameKey, buildStrings($newValue, $depth + 1)),
                    makeString($depth, 'removed', $nameKey, buildStrings($oldValue, $depth + 1))];
                break;

            case 'removed':
                return makeString($depth, 'removed', $nameKey, buildStrings($oldValue, $depth + 1));
                break;

            case 'added':
                return makeString($depth, 'added', $nameKey, buildStrings($newValue, $depth + 1));
                break;

            case 'nested':
                return makeString($depth, 'specialEmpty', $nameKey, renderPretty($children, $depth + 1));
                break;
        }
    }, $data);

    $outStr = implode(PHP_EOL, flattenAll($result)) . PHP_EOL;
    return '{' . PHP_EOL . $outStr . addSpace($depth) . '}';
}

function makeString($depth, $spaceInfo, $name, $key)
{
    return addSpace($depth) . SPACE_INFO[$spaceInfo] . $name . ': ' . $key;
}

function buildStrings($data, $depth = 0)
{
    if (empty($data)) {
        return $data;
    }

    if (is_array($data)) {
        $keys = array_keys($data);
        $strArr = array_reduce($keys, function ($acc, $key) use ($data, $depth) {
            $acc[] = addSpace($depth + 1) . $key . ': ' . $data[$key];
            return $acc;
        }, []);
        $outStr = implode(PHP_EOL, $strArr) . PHP_EOL;
        return '{' . PHP_EOL . $outStr . addSpace($depth) . '}';
    } else {
        return $data;
    }
}

function addSpace($depth)
{
    return str_repeat('    ', $depth);
}
