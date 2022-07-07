<?php

namespace alcamo\sanitize;

use alcamo\exception\DataValidationFailed;
use PHPUnit\Framework\TestCase;

class RangeFilterTest extends TestCase
{
    /**
     * @dataProvider filterProvider
     */
    public function testFilter($range, $input, $expectedOutput)
    {
        $filter = new RangeFilter(null, $range);

        $this->assertSame(
            $expectedOutput,
            $filter->filter($input)
        );
    }

    public function filterProvider()
    {
        return [
            [ [ 0, 1 ], null, null ],
            [ [ 0, 1 ], '', null ],
            [ [ 0, 1 ], '0', '0' ],
            [ [ -1, 1 ], 0, 0 ],
            [ [ -1, 1 ], 2, null ],
            [ [ 'bar', 'foo' ], 'foo', 'foo' ],
            [ [ 'bar', 'foo' ], 'barr', 'barr' ],
            [ [ 'bar', 'foo' ], 'baq', null ],
            [ [ 'bar', 'foo' ], [], null ],
            [
                [ 7, 43 ],
                [ null, '1', '', '0', 7, '7', 43, true, 44, 42 ],
                [ 7, '7', 43, true, 42 ]
            ]
        ];
    }

    public function testException()
    {
        $this->expectException(DataValidationFailed::class);

        $this->expectExceptionMessage(
            'Validation failed, expected one of "numerically indexed array with two va..." in [1, 2, 3]'
        );

        new RangeFilter(null, [ 1, 2, 3 ]);
    }
}
