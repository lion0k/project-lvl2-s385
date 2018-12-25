<?php

namespace Gendiff\Parse;

use \Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

function parseFile($data, $type)
{
    try {
        switch ($type) {
            case 'json':
                return parseJson($data);
                break;

            case 'yaml':
                return parseYaml($data);
                break;

            default:
                throw new \Exception("Unsupported content type '{$type}'");
        }
    } catch (\Exception $e) {}
}

function parseJson($json)
{
    $data = json_decode($json, true);
    if (json_last_error() != JSON_ERROR_NONE) {
        throw new \Exception("File having error data structure");
    }
    return $data;
}

function parseYaml($yaml)
{
    try {
        return Yaml::parse($yaml);
    } catch (ParseException $e) {
        throw new \Exception("File having error data structure");
    }
}