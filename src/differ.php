<?php

namespace Gendiff\differ;

use function Gendiff\parse\parse;
use function Gendiff\render\render;
use function Gendiff\utils\getDataFromFile;
use function Gendiff\utils\getExtension;
use function Gendiff\utils\booleanToStr;

function buildDataStructure($typeInfo, $nameKey, $oldValue, $newValue, $children = null)
{
    return ['typeInfo' => $typeInfo,
            'nameKey' => $nameKey,
            'oldValue' => booleanToStr($oldValue),
            'newValue' => booleanToStr($newValue),
            'children' => $children];
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
    $data1 = parse(getDataFromFile($pathToFile1), getExtension($pathToFile1));
    $data2 = parse(getDataFromFile($pathToFile2), getExtension($pathToFile2));

    return render(makeDiff($data1, $data2), $format);
}
