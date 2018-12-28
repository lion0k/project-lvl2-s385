<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;
use function \Gendiff\Differ\genDiff;

class DifferTest extends TestCase
{
    private function getPathForFixture($fixtureFileName)
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . $fixtureFileName;
    }

    public function testDataJsonSimple()
    {
        $this->assertEquals(file_get_contents($this->getPathForFixture('result.json'))
            , genDiff($this->getPathForFixture('file2.json')
            , $this->getPathForFixture('file1.json')));
    }

    public function testDataYamlSimple()
    {
        $this->assertEquals(file_get_contents($this->getPathForFixture('result.json'))
            , genDiff($this->getPathForFixture('file2.yaml')
                , $this->getPathForFixture('file1.yaml')));
    }

    public function testDataJsonAST()
    {
        $this->assertEquals(file_get_contents($this->getPathForFixture('resultAst.json'))
            , genDiff($this->getPathForFixture('file1Ast.json')
                , $this->getPathForFixture('file2Ast.json')));
    }

    public function testDataFormatJson()
    {
        $this->assertEquals(file_get_contents($this->getPathForFixture('resultJson.json'))
            , genDiff($this->getPathForFixture('file2.json')
                , $this->getPathForFixture('file1.json'), 'json'));
    }
}
