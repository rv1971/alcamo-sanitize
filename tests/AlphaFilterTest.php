<?php

namespace alcamo\sanitize;

use PHPUnit\Framework\TestCase;

class AlphaFilterTest extends TestCase
{
    /**
     * @dataProvider filterProvider
     */
    public function testFilter($input, $expectedOutput)
    {
        $filter = new AlphaFilter();

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
            [ 'foo', 'foo' ],
            [ 'foo42', null ],
            [ 42, null ],
            [ true, null ],
            [ false, null ],
            [ [], null ],
            [ [ null, '+', '' ], null ],
            [ [ 'foo', null, '', 'bar', '32' ], [ 'foo', 'bar' ] ]
        ];
    }
}
