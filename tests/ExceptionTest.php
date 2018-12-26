<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;
use function \Gendiff\Differ\genDiff;

class ExceptionTest extends TestCase
{
    private function getPathForFixture($fixtureFileName)
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . $fixtureFileName;
    }

    public function testNotFound()
    {
        $this->expectException('Exception');
        genDiff($this->getPathForFixture('file1.json')
            , $this->getPathForFixture('jsonp'));
    }

    public function testNotSupportExtension()
    {
        $this->expectException('Exception');
        genDiff($this->getPathForFixture('file1.json')
            , $this->getPathForFixture('errorExt.json2'));
    }

    public function testJsonErrorFileStructure()
    {
        $this->expectException('Exception');
        genDiff($this->getPathForFixture('file2.json')
            , $this->getPathForFixture('errorJson.json'));
    }

    public function testYamlErrorFileStructure()
    {
        $this->expectException('Exception');
        genDiff($this->getPathForFixture('file1.yaml')
            , $this->getPathForFixture('errorYaml.yaml'));
    }
}