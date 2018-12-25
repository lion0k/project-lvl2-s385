<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;
use function \Gendiff\Differ\genDiff;

class DifferTest extends TestCase
{
    const RESULT = <<<DATA
{
    host: hexlet.io
  + timeout: 20
  - timeout: 50
  - proxy: 123.234.53.22
  + verbose: true
}

DATA;

    private function getPathForFixture($fixtureFileName)
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . $fixtureFileName;
    }

    public function testNotFound()
    {
        try {
            genDiff($this->getPathForFixture('file1.json')
                  , $this->getPathForFixture('json'));
            $this->fail('Expected exception');
        } catch (\Exception $e) {}
    }

    public function testNotSupportExtension()
    {
        try {
            genDiff($this->getPathForFixture('file1.json')
                  , $this->getPathForFixture('errorExt.json2'));
            $this->fail('Expected exception');
        } catch (\Exception $e) {
            $this->assertTrue(True);
        }
    }

    public function testJsonErrorFileStructure()
    {
        try {
            genDiff($this->getPathForFixture('file2.json')
                  , $this->getPathForFixture('errorJson.json'));
            $this->fail('Expected exception');
        } catch (\Exception $e) {
            $this->assertTrue(True);
        }
    }

    public function testYamlErrorFileStructure()
    {
        try {
            genDiff($this->getPathForFixture('file1.yaml')
                  , $this->getPathForFixture('errorYaml.yaml'));
            $this->fail('Expected exception');
        } catch (\Exception $e) {
            $this->assertTrue(True);
        }
    }

    public function testDataJson()
    {
        $this->assertEquals(self::RESULT
            , genDiff($this->getPathForFixture('file2.json')
            , $this->getPathForFixture('file1.json')));
    }

    public function testDataYaml()
    {
        $this->assertEquals(self::RESULT
            , genDiff($this->getPathForFixture('file2.yaml')
                , $this->getPathForFixture('file1.yaml')));
    }
}
