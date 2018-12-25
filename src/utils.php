<?php

namespace Gendiff\Utils;

function getDataFromFile($pathToFile)
{
    if (file_exists($pathToFile) && is_readable($pathToFile)) {
        $body = file_get_contents($pathToFile);

        if (empty($body)) {
            throw new \Exception("File '{$pathToFile}' empty or not supported");
        }
        return $body;
    }

    throw new \Exception("File '{$pathToFile}' not exists or not supported");
}

function getExtension($pathToFile)
{
    return pathinfo($pathToFile, PATHINFO_EXTENSION);
}

function booleanToStr($item)
{
    if (is_bool($item)) {
        return ($item) ? 'true' : 'false';
    } else {
        return $item;
    }
}
