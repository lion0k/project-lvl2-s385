<?php

namespace Gendiff\Render;

function render($data, $format)
{
    switch ($format) {
        case 'json':
            return json_encode($data);
            break;

        case 'pretty':
            return json_encode($data);
            break;
    }
}
