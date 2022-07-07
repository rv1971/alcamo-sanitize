<?php

namespace alcamo\sanitize;

use PHPUnit\Framework\TestCase;

class DefaultFilterTest extends TestCase
{
    /**
     * @dataProvider filterProvider
     */
    public function testFilter($default, $input, $expectedOutput)
    {
        $filter = new DefaultFilter(null, $default);

        $this->assertSame(
            $expectedOutput,
            $filter->filter($input)
        );
    }

    public function filterProvider()
    {
        return [
            [ null, null, null ],
            [ null, '', null ],
            [ 1, null, 1 ],
            [ 'foo', '', 'foo' ],
            [ 'foo', 'bar', 'bar' ],
            [ 1, 0, 0 ],
            [ true, null, true ],
            [ false, '', false ],
            [ false, 'x', 'x' ],
            [ 'qux', [], 'qux' ],
            [ 7, [ null, '', '' ], 7 ],
            [ [ 'foo' ], [ null, '', '' ], [ 'foo' ] ],
            [ 'foo', [ null, '', '', 'bar' ], [ 'bar' ] ]
        ];
    }
}
