<?php

namespace alcamo\sanitize;

use PHPUnit\Framework\TestCase;

class HexStringFilterTest extends TestCase
{
    /**
     * @dataProvider filterProvider
     */
    public function testFilter($input, $expectedOutput)
    {
        $filter = new HexStringFilter();

        $this->assertSame(
            $expectedOutput,
            $filter->filter($input)
        );
    }

    public function filterProvider()
    {
        return [
            [ null, null ],
            [ '', null ],
            [ "\nAb\r\r Cd\t  23", 'AbCd23' ],
            [ 42, '42' ],
            [ true, '1' ],
            [ false, null ],
            [ 'axbc', null ],
            [ [], null ],
            [ [ null, '' ], null ],
            [ [ 'fFe', null, '', 'ag', 123 ], [ 'fFe', '123' ] ]
        ];
    }
}
