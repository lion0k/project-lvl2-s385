<?php

namespace Gendiff\Parse;

function parseFile($data, $extension)
{
    switch ($extension) {
        case 'json':
            $data = json_decode($data, true);
            return (json_last_error() == JSON_ERROR_NONE) ? $data : null;
            break;

        default:
            return null;
    }
}
