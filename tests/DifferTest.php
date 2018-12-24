<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;
use function \Gendiff\Differ\genDiff;

class DifferTest extends TestCase
{
    public $jsonFile1 = __DIR__ . DIRECTORY_SEPARATOR . 'filesForTests' . DIRECTORY_SEPARATOR . 'before.json';
    public $jsonFile2 = __DIR__ . DIRECTORY_SEPARATOR . 'filesForTests' . DIRECTORY_SEPARATOR . 'after.json';
    public $result = __DIR__ . DIRECTORY_SEPARATOR . 'filesForTests' . DIRECTORY_SEPARATOR . 'resultJson';
    public $fakePath = __DIR__ . DIRECTORY_SEPARATOR . 'filesForTests' . DIRECTORY_SEPARATOR . 'json';
    public $errorJson = __DIR__ . DIRECTORY_SEPARATOR . 'filesForTests' . DIRECTORY_SEPARATOR . 'errorJson.json';

    public function testJson()
    {
        $this->assertEquals(
            'One or both files not found', (genDiff($this->jsonFile1, $this->fakePath))
        );

        $this->assertEquals(
            'One or both files undefined data structure or having errors'
            , (genDiff($this->jsonFile1, $this->errorJson))
        );

        $this->assertEquals(
            file_get_contents($this->result), json_encode(genDiff($this->jsonFile1, $this->jsonFile2))
        );
    }
}
