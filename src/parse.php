<?php

namespace Gendiff\Parse;

use \Symfony\Component\Yaml\Yaml;

function parseFile($data, $type)
{
    switch ($type) {
        case 'json':
            return parseJson($data);

        case 'yaml':
            return parseYaml($data);

        default:
            throw new \Exception("Unsupported content type '{$type}'");
    }
}

function parseJson($json)
{
    $data = json_decode($json, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new \Exception("File having error data structure");
    }
    return $data;
}

function parseYaml($yaml)
{
    return Yaml::parse($yaml);
}
