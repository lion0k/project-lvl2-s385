<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;
use function \Gendiff\Differ\genDiff;

class DifferTest extends TestCase
{
    const RESULT_SIMPLE = <<<DATA
{
    host: hexlet.io
  + timeout: 20
  - timeout: 50
  - proxy: 123.234.53.22
  + verbose: true
}

DATA;

    const RESULT_AST = <<<AST
{
    common: {
        setting1: Value 1
      - setting2: 200
        setting3: true
      - setting6: {
            key: value
        }
      + setting4: blah blah
      + setting5: {
            key5: value5
        }
    }
    group1: {
      + baz: bars
      - baz: bas
        foo: bar
    }
  - group2: {
        abc: 12345
    }
  + group3: {
        fee: 100500
    }
}

AST;

    private function getPathForFixture($fixtureFileName)
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . $fixtureFileName;
    }

    public function testDataJsonSimple()
    {
        $this->assertEquals(self::RESULT_SIMPLE
            , genDiff($this->getPathForFixture('file2.json')
            , $this->getPathForFixture('file1.json')));
    }

    public function testDataYamlSimple()
    {
        $this->assertEquals(self::RESULT_SIMPLE
            , genDiff($this->getPathForFixture('file2.yaml')
                , $this->getPathForFixture('file1.yaml')));
    }

    public function testDataJsonAST()
    {
        $this->assertEquals(self::RESULT_AST
            , genDiff($this->getPathForFixture('file1Ast.json')
                , $this->getPathForFixture('file2Ast.json')));
    }
}
