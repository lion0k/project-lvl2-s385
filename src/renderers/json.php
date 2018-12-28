<?php

namespace Gendiff\Renderers;

function renderJson($data)
{
    $result = json_encode($data);
    if ($result === false) {
        throw new \Exception('Error. Cannot render json format');
    }

    return $result;
}
