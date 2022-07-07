<?php

namespace alcamo\sanitize;

use PHPUnit\Framework\TestCase;

class MinFilterTest extends TestCase
{
    /**
     * @dataProvider filterProvider
     */
    public function testFilter($min, $input, $expectedOutput)
    {
        $filter = new MinFilter(null, $min);

        $this->assertSame(
            $expectedOutput,
            $filter->filter($input)
        );
    }

    public function filterProvider()
    {
        return [
            [ 1, null, null ],
            [ null, '', null ],
            [ -1, '0', '0' ],
            [ -1, 0, 0 ],
            [ 2, '1', null ],
            [ 2, 1, null ],
            [ 'bar', 'foo', 'foo' ],
            [ 'bar', 'barr', 'barr' ],
            [ 'bar', 'baq', null ],
            [ 'qux', [], null ],
            [
                7,
                [ null, '1', '', '0', 7, '7', 43, true ],
                [ 7, '7', 43, true ]
            ]
        ];
    }
}
