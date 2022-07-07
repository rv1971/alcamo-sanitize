<?php

namespace alcamo\sanitize;

use Ds\Set;
use PHPUnit\Framework\TestCase;

class SetFilterTest extends TestCase
{
    /**
     * @dataProvider filterProvider
     */
    public function testFilter($input, $expectedOutput)
    {
        $filter = new SetFilter();

        $this->assertEquals(
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
            [ 'foo', new Set([ 'foo' ]) ],
            [ 12, new Set([ 12 ]) ],
            [
                [ true, 'qux', 'baz', 44, 44, 'baz' ],
                new Set([ true, 'qux', 'baz', 44 ])
            ],
            [
                new Set([ 'foo', 'bar', 12, true ]),
                new Set([ 'foo', 'bar', 12, true ])
            ]
        ];
    }
}
