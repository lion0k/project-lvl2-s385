<?php

namespace Gendiff\Differ;

use function Gendiff\Parse\parseFile;
use function Gendiff\Render\render;
use function Gendiff\Utils\getDataFromFile;
use function Gendiff\Utils\getExtension;
use function Gendiff\Utils\booleanToStr;

function setChangeInfo($typeInfo, $nameKey, $oldValue, $newValue)
{
    return ['typeInfo' => $typeInfo
    , 'nameKey' => $nameKey
    , 'oldValue' => booleanToStr($oldValue)
    , 'newValue' => booleanToStr($newValue)];
}

function genDiff($pathToFile1, $pathToFile2, $format = 'pretty')
{
    try {
        $file1 = getDataFromFile($pathToFile1);
        $file2 = getDataFromFile($pathToFile2);

        $dataFile1 = parseFile($file1, getExtension($pathToFile1));
        $dataFile2 = parseFile($file2, getExtension($pathToFile2));
    } catch (\Exception $e) {
        return $e->getMessage() . PHP_EOL;
    }

    $arrUniqKeys = array_unique(array_merge(array_keys($dataFile1), array_keys($dataFile2)));
    $result = array_reduce($arrUniqKeys, function ($acc, $key) use ($dataFile1, $dataFile2) {
        if (array_key_exists($key, $dataFile1) && array_key_exists($key, $dataFile2)) {
            if ($dataFile1[$key] == $dataFile2[$key]) {
                $acc[] = setChangeInfo('unchange', $key, $dataFile1[$key], null);
            } else {
                $acc[] = setChangeInfo('changed', $key, $dataFile1[$key], $dataFile2[$key]);
            }
        } else {
            if (array_key_exists($key, $dataFile1)) {
                $acc[] = setChangeInfo('removed', $key, $dataFile1[$key], null);
            } else {
                $acc[] = setChangeInfo('added', $key, null, $dataFile2[$key]);
            }
        }
        return $acc;
    }, []);

    return render($result, $format);
}
