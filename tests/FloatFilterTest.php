<?php

namespace alcamo\sanitize;

use PHPUnit\Framework\TestCase;

class FloatFilterTest extends TestCase
{
    /**
     * @dataProvider filterProvider
     */
    public function testFilter($input, $expectedOutput)
    {
        $filter = new FloatFilter();

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
            [ '0', 0. ],
            [ '1', 1. ],
            [ '42', 42. ],
            [ 'foo', 0. ],
            [ 0, 0. ],
            [ 1, 1. ],
            [ 42, 42. ],
            [ true, 1. ],
            [ false, 0. ],
            [ '42.43', 42.43 ],
            [ 42.43, 42.43 ],
            [ [], null ],
            [
                [ null, '1', '', '12.34', 'bar', 43, true ],
                [ 1., 12.34, 0., 43., 1. ]
            ]
        ];
    }
}
