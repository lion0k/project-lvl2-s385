<?php

namespace Gendiff\Differ;

use function Gendiff\Parse\parseFile;
use function Gendiff\Render\render;

function getDataFromFile($pathToFile)
{
    if (empty(trim($pathToFile)) || ! file_exists($pathToFile)) {
        return null;
    }
    return file_get_contents($pathToFile);
}

function getExtension($pathToFile)
{
    return pathinfo($pathToFile, PATHINFO_EXTENSION);
}

function booleanToStr($item)
{
    if (is_bool($item)) {
        return ((bool) $item) ? 'true' : 'false';
    } else {
        return $item;
    }
}

function genDiff($pathToFile1, $pathToFile2, $format = 'json')
{
    $file1 = getDataFromFile($pathToFile1);
    $file2 = getDataFromFile($pathToFile2);
    if (is_null($file1) || is_null($file2)) {
        return 'One or both files not found';
    }

    $dataFile1 = parseFile($file1, getExtension($pathToFile1));
    $dataFile2 = parseFile($file2, getExtension($pathToFile2));
    if (is_null($dataFile1) || is_null($dataFile2)) {
        return 'One or both files undefined data structure or having errors';
    }

    $arrUniqKeys = array_unique(array_merge(array_keys($dataFile1), array_keys($dataFile2)));
    $result = array_reduce($arrUniqKeys, function ($acc, $key) use ($dataFile1, $dataFile2) {
        if (array_key_exists($key, $dataFile1) && array_key_exists($key, $dataFile2)) {
            $value1 = booleanToStr($dataFile1[$key]);
            $value2 = booleanToStr($dataFile2[$key]);
            if ($dataFile1[$key] == $dataFile2[$key]) {
                $acc[] = "  {$key}: {$value1}";
            } else {
                $acc[] = "+ {$key}: {$value2}";
                $acc[] = "- {$key}: {$value1}";
            }
        } else {
            if (array_key_exists($key, $dataFile1)) {
                $value = booleanToStr($dataFile1[$key]);
                $acc[] = "- {$key}: {$value}";
            } else {
                $value = booleanToStr($dataFile2[$key]);
                $acc[] = "+ {$key}: {$value}";
            }
        }
        return $acc;
    }, []);

    return render($result, $format);
}
