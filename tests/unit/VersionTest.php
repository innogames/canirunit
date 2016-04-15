<?php

use App\Version;

class VersionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider semanticVersionsDataProvider
     */
    public function testVersionIsSemanticCompliant($requiredVersion, $hasVersion, $expected)
    {
        $required = new Version($requiredVersion);
        $has = new Version($hasVersion);


        $this->assertEquals($expected, $has->compareVersion($required));
    }

    public function semanticVersionsDataProvider()
    {
        return [
            ['1.2.*', '1.2.1', true],
            ['1.2.*', '1.2.99', true],
            ['1.2.*', '1.2.99-patch1', true],
            ['1.2.*', '1.1.1', false],
            ['1.2.*', '1.3.1', false],
            ['1.2.*', '1.3.1-patch', false],

            ['1.*', '1.1', true],
            ['1.*', '1.99', true],
            ['1.*', '1.99.99-patch1', true],
            ['1.*', '0.9', false],
            ['1.*', '2.1.1', false],
            ['1.*', '2.1.1-patch', false],

            ['*', '1.1', true],
            ['*', '1.99', true],
            ['*', '1.99.99-patch1', true],
            ['*', '0.9', true],
            ['*', '2.1.1', true],
            ['*', '2.1.1-patch', true],

            ['~1.2', '1.2.1', true],
            ['~1.2', '1.99.99', true],
            ['~1.2', '1.99.99-patch1', true],
            ['~1.2', '1.1.99', false],
            ['~1.2', '2.0.0', false],
            ['~1.2', '2.0.0-patch', false],
        ];
    }
}
