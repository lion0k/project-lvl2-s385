<?php

namespace Gendiff\Differ;

use function Gendiff\Parse\parseFile;
use function Gendiff\Render\render;
use function Gendiff\Utils\getDataFromFile;
use function Gendiff\Utils\getExtension;
use function Gendiff\Utils\booleanToStr;

function buildDataStructure($typeInfo, $nameKey, $oldValue, $newValue, $child = null)
{
    return ['typeInfo' => $typeInfo
    , 'nameKey' => $nameKey
    , 'oldValue' => booleanToStr($oldValue)
    , 'newValue' => booleanToStr($newValue)
    , 'child' => $child];
}

function makeDiff($data1, $data2)
{
    $arrUniqKeys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    $result = array_reduce($arrUniqKeys, function ($acc, $key) use ($data1, $data2) {
        if (array_key_exists($key, $data1) && array_key_exists($key, $data2)) {
            if (is_array($data1[$key]) && is_array($data2[$key])) {
                $acc[] = buildDataStructure(
                    'nested',
                    $key,
                    $data1[$key],
                    null,
                    makeDiff($data1[$key], $data2[$key])
                );
            } elseif (is_array($data1[$key]) || is_array($data2[$key])) {
                $acc[] = buildDataStructure('unchanged', $key, $data1[$key], $data2[$key]);
            } else {
                if ($data1[$key] == $data2[$key]) {
                    $acc[] = buildDataStructure('unchanged', $key, $data1[$key], null);
                } else {
                    $acc[] = buildDataStructure('changed', $key, $data1[$key], $data2[$key]);
                }
            }
        } else {
            if (array_key_exists($key, $data1)) {
                $acc[] = buildDataStructure('removed', $key, $data1[$key], null);
            } else {
                $acc[] = buildDataStructure('added', $key, null, $data2[$key]);
            }
        }
        return $acc;
    }, []);

    return $result;
}

function genDiff($pathToFile1, $pathToFile2, $format = 'pretty')
{
    $data1 = parseFile(getDataFromFile($pathToFile1), getExtension($pathToFile1));
    $data2 = parseFile(getDataFromFile($pathToFile2), getExtension($pathToFile2));

    return render(makeDiff($data1, $data2), $format);
}
