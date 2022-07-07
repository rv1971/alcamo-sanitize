<?php

namespace alcamo\sanitize;

use PHPUnit\Framework\TestCase;

class BoolFilterTest extends TestCase
{
    /**
     * @dataProvider filterProvider
     */
    public function testFilter($input, $expectedOutput)
    {
        $filter = new BoolFilter();

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
            [ '0', false ],
            [ '1', true ],
            [ 'foo', true ],
            [ 0, false ],
            [ 1, true ],
            [ false, false ],
            [ true, true ],
            [ [], null ],
            [ [ null, '1', '', '0', 'bar' ], [ true, false, true ] ]
        ];
    }
}
