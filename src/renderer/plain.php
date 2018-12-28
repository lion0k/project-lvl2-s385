<?php

namespace Gendiff\Renderer;

function buildMessages($data, $parents = []):array
{
    $plain = array_reduce($data, function ($acc, $node) use ($parents) {
        $parents[] = $node['nameKey'];
        $parentsPath = implode('.', $parents);
        switch ($node['typeInfo']) {
            case 'changed':
                $acc[] = "Property '{$parentsPath}' was {$node['typeInfo']}. " .
                         "From '{$node['oldValue']}' to '{$node['newValue']}'";
                break;

            case 'removed':
                $acc[] = "Property '{$parentsPath}' was {$node['typeInfo']}";
                break;

            case 'added':
                if (is_array($node['newValue'])) {
                    $acc[] = "Property '{$parentsPath}' was {$node['typeInfo']} " .
                             "with value: 'complex value'";
                } else {
                    $acc[] = "Property '{$parentsPath}' was {$node['typeInfo']} " .
                             "with value: '{$node['newValue']}'";
                }
                break;

            case 'nested':
                return array_merge($acc, buildMessages($node['children'], $parents));
        }
        return $acc;
    }, []);

    return $plain;
}

function renderPlain($data):string
{
    return implode(PHP_EOL, buildMessages($data));
}
