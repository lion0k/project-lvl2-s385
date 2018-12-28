<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;
use function \Gendiff\differ\genDiff;

class DifferTest extends TestCase
{
    private function getPathForFixture($fixtureFileName)
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . $fixtureFileName;
    }

    public function testDataJsonSimple()
    {
        $expected = file_get_contents($this->getPathForFixture('result.json'));
        $actual = genDiff($this->getPathForFixture('file2.json'),
                          $this->getPathForFixture('file1.json'));

        $this->assertEquals($expected, $actual);
    }

    public function testDataYamlSimple()
    {
        $expected = file_get_contents($this->getPathForFixture('result.json'));
        $actual = genDiff($this->getPathForFixture('file2.yaml'),
                          $this->getPathForFixture('file1.yaml'));

        $this->assertEquals($expected, $actual);
    }

    public function testDataJsonAST()
    {
        $expected = file_get_contents($this->getPathForFixture('resultAst.json'));
        $actual = genDiff($this->getPathForFixture('file1Ast.json'),
                          $this->getPathForFixture('file2Ast.json'));

        $this->assertEquals($expected, $actual);
    }

    public function testDataFormatJson()
    {
        $expected = file_get_contents($this->getPathForFixture('resultJson.json'));
        $actual = genDiff($this->getPathForFixture('file2.json'),
                          $this->getPathForFixture('file1.json'), 'json');

        $this->assertEquals($expected, $actual);
    }
}
