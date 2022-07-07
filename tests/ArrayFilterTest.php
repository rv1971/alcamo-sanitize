<?php

namespace alcamo\sanitize;

use Ds\Set;
use PHPUnit\Framework\TestCase;

class ArrayFilterTest extends TestCase
{
    /**
     * @dataProvider filterProvider
     */
    public function testFilter($input, $expectedOutput)
    {
        $filter = new ArrayFilter();

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
            [ [], null ],
            [ new Set(), null ],
            [ 'foo', [ 'foo' ] ],
            [ 12, [ 12 ] ],
            [ [ true, 'qux', 'baz', 44 ], [ true, 'qux', 'baz', 44 ] ],
            [
                new Set([ 'foo', 'bar', 12, true ]),
                [ 'foo', 'bar', 12, true ]
            ]
        ];
    }
}
