<?php

namespace alcamo\sanitize;

use PHPUnit\Framework\TestCase;

class MaxFilterTest extends TestCase
{
    /**
     * @dataProvider filterProvider
     */
    public function testFilter($min, $input, $expectedOutput)
    {
        $filter = new MaxFilter(null, $min);

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
            [ 1, '1', '1' ],
            [ 1, 1, 1 ],
            [ 2, '3', null ],
            [ 2, 3, null ],
            [ 'bar', 'foo', null ],
            [ 'bar', 'bar', 'bar' ],
            [ 'bar', 'barr', null ],
            [ 'bar', 'baq', 'baq' ],
            [ 'qux', [], null ],
            [
                'qux',
                [ null, '1', '', '0', 7, '7', 43, true, 'quxx' ],
                [ '1', '0', '7', true ]
            ]
        ];
    }
}
