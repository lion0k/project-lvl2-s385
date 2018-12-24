<?php

namespace Gendiff\Render;

use function cli\line;

function render(array $data)
{
    if (! empty($data)) {
        foreach ($data as $item) {
            line($item);
        }
    }
}
